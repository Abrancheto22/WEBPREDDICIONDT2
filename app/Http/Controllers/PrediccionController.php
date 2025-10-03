<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prediccion;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Importar la clase Session
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PrediccionesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Importar la clase Carbon

class PrediccionController extends Controller
{
    public function index()
    {
        $predicciones = Prediccion::with('cita')->get();
        return view('predicciones.index', compact('predicciones'));
    }

    public function create($idcita = null)
{
    if ($idcita) {
        $cita = Cita::with(['triaje', 'paciente', 'doctor', 'enfermera'])->find($idcita);
        if (! $cita) {
            return redirect()->back()->with('error', 'Cita no encontrada');
        }
        if (! $cita->triaje) {
            return redirect()->back()->with('error', 'La cita no tiene un triaje asociado');
        }
    }
    $citas = Cita::with(['triaje', 'paciente', 'doctor', 'enfermera'])->get();

    // Capturar el tiempo actual del servidor
    $startTime = now()->toIso8601String();

    return view('predicciones.create', compact('cita', 'citas'))
        ->with('paciente', $cita ? $cita->paciente : null)
        ->with('triaje', $cita ? $cita->triaje : null)
        ->with('timerInicio', $startTime); // Pasar el tiempo a la vista
}

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|numeric|min:0|max:500',
                'glucosa' => 'required|numeric|min:0|max:500', 
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0',
                'insulina' => 'required|numeric|min:0|max:200',
                'BMI' => 'required|numeric|min:0|max:180',
                'pedigree' => 'required|numeric|min:0|max:2',
                'edad' => 'required|numeric|min:18',
                'observacion' => 'nullable|string',
            ]);

            // **1. Capturar el tiempo de inicio y guardarlo en la sesión**
            $startTime = now();
            Session::put('prediccion_start_time', $startTime);

            $mlApiUrl = 'http://127.0.0.1:5000/predict';

            $response = Http::post($mlApiUrl, [
                'Pregnancies' => (float)$validated['embarazos'],
                'Glucose' => (float)$validated['glucosa'],
                'BloodPressure' => (float)$validated['presion_sanguinea'],
                'SkinThickness' => (float)$validated['grosor_piel'],
                'Insulin' => (float)$validated['insulina'],
                'BMI' => (float)$validated['BMI'],
                'DiabetesPedigreeFunction' => (float)$validated['pedigree'],
                'Age' => (float)$validated['edad'],
            ]);

            $response->throw();

            $predictionResult = $response->json();

            return response()->json([
                'success' => true,
                'message' => 'Predicción obtenida con éxito',
                'predictionResult' => $predictionResult,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Error al conectar con la API de ML: ' . $e->getMessage(), ['url' => $mlApiUrl, 'response' => $e->response ? $e->response->body() : 'N/A']);
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener la predicción del modelo de ML. Por favor, inténtelo de nuevo más tarde.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error inesperado al procesar la predicción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error inesperado al procesar la predicción. Por favor, inténtelo de nuevo.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function analyzeWithGemini(Request $request)
    {
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|numeric|min:0',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0',
                'insulina' => 'required|numeric|min:0|max:200',
                'BMI' => 'required|numeric|min:0|max:180',
                'pedigree' => 'required|numeric|min:0|max:2',
                'edad' => 'required|numeric|min:18',
                'observacion' => 'nullable|string',
            ]);

            // Obtener información del paciente
            $cita = Cita::with(['paciente', 'triaje'])->find($validated['idcita']);
            $paciente = $cita->paciente;
            
            // Ajustar embarazos según el género del paciente
            $embarazos = $validated['embarazos'];
            if ($paciente->sexo === 'Masculino' && $embarazos > 0) {
                $embarazos = 0; // Los hombres no pueden tener embarazos
            }

            // Verificar si ya existe una predicción para obtener el resultado
            $prediccionExistente = Prediccion::where('idcita', $validated['idcita'])->first();
            $resultadoPrediccion = $prediccionExistente ? $prediccionExistente->resultado : null;

            // Construir el prompt para Gemini incluyendo el resultado si está disponible
            $prompt = $this->buildGeminiPrompt($validated, $paciente, $embarazos, $resultadoPrediccion);

            // Llamar a la API de Gemini
            $geminiApiKey = 'AIzaSyAjT0tLtuKUN8FzbhmKiAFMN5EFP6FhrNg';
            $geminiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $geminiApiKey,
            ])->post($geminiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                throw new \Exception('Error al conectar con Gemini API: ' . $response->body());
            }

            $geminiResult = $response->json();
            $analysisText = $geminiResult['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo obtener análisis';

            // Procesar el texto de análisis para mejorar el formato
            $analysisText = $this->formatAnalysisText($analysisText);

            // Buscar si ya existe una predicción para esta cita
            $prediccion = Prediccion::where('idcita', $validated['idcita'])->first();
            
            if ($prediccion) {
                // Si existe, actualizar el análisis de IA
                $prediccion->analisis_ia = $analysisText;
                $prediccion->save();
            } else {
                // Si no existe, crear una nueva predicción temporal con solo el análisis de IA
                $prediccion = new Prediccion();
                $prediccion->idcita = $validated['idcita'];
                $prediccion->embarazos = $embarazos;
                $prediccion->glucosa = $validated['glucosa'];
                $prediccion->presion_sanguinea = $validated['presion_sanguinea'];
                $prediccion->grosor_piel = $validated['grosor_piel'];
                $prediccion->insulina = $validated['insulina'];
                $prediccion->BMI = $validated['BMI'];
                $prediccion->pedigree = $validated['pedigree'];
                $prediccion->edad = $validated['edad'];
                $prediccion->observacion = $validated['observacion'];
                $prediccion->analisis_ia = $analysisText;
                $prediccion->resultado = 0; // Valor temporal hasta que se haga la predicción ML
                $prediccion->timer = 0; // Valor temporal
                $prediccion->timer_inicio = now();
                $prediccion->timer_parada = now();
                $prediccion->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Análisis con IA completado exitosamente',
                'analysis' => $analysisText,
                'patient_data' => [
                    'nombre' => $paciente->nombre . ' ' . $paciente->apellido,
                    'sexo' => $paciente->sexo,
                    'embarazos_ajustados' => $embarazos,
                    'embarazos_originales' => $validated['embarazos']
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en análisis con Gemini: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al realizar el análisis con IA: ' . $e->getMessage()
            ], 500);
        }
    }

    private function buildGeminiPrompt($data, $paciente, $embarazosAjustados, $prediccionResultado = null)
    {
        $sexoInfo = $paciente->sexo === 'Masculino' && $data['embarazos'] > 0 
            ? " (Nota: El paciente es masculino, por lo que el número de embarazos se ajustó a 0 para el análisis)" 
            : "";

        // Información del resultado de la predicción si está disponible
        $prediccionInfo = "";
        if ($prediccionResultado !== null) {
            $probabilidad = round($prediccionResultado * 100, 2);
            $diagnostico = $prediccionResultado >= 0.5 ? 'POSITIVO para Diabetes Tipo 2' : 'NEGATIVO para Diabetes Tipo 2';
            $riesgo = $this->calculateRiskLevel($prediccionResultado);
            
            $prediccionInfo = "

**RESULTADO DE LA PREDICCIÓN DE IA:**
• Diagnóstico: {$diagnostico}
• Probabilidad: {$probabilidad}%
• Nivel de Riesgo: {$riesgo['nivel']}
• Descripción del Riesgo: {$riesgo['descripcion']}";
        }

        // Mejorar la presentación de las observaciones
        $observacionesInfo = !empty($data['observacion']) 
            ? "
**OBSERVACIONES MÉDICAS DEL PROFESIONAL:**
{$data['observacion']}" 
            : "";

        return "Actúa como un médico especialista en diabetes, proporcionando una evaluación clínica dirigida específicamente para APOYAR LA TOMA DE DECISIONES MÉDICAS del doctor tratante.

**CONTEXTO CLÍNICO:**
Paciente: {$paciente->nombre} {$paciente->apellido} | Sexo: {$paciente->sexo} | Edad: {$data['edad']} años

**PARÁMETROS EVALUADOS (Protocolo Pima Indians):**
• Embarazos: {$embarazosAjustados}{$sexoInfo}
• Glucosa plasmática: {$data['glucosa']} mg/dL
• Presión arterial diastólica: {$data['presion_sanguinea']} mmHg
• Grosor del pliegue cutáneo tricipital: {$data['grosor_piel']} mm
• Insulina sérica: {$data['insulina']} μU/mL
• Índice de Masa Corporal: {$data['BMI']} kg/m²
• Función Pedigrí Diabético: {$data['pedigree']}{$prediccionInfo}{$observacionesInfo}

**SOLICITUD DE ANÁLISIS MÉDICO:**

Como especialista, proporciona una evaluación estructurada que incluya:

**1. ESTRATIFICACIÓN DE RIESGO DIABÉTICO:**
- Clasificación: ALTO / MODERADO / BAJO riesgo
- Justificación basada en evidencia clínica
" . ($prediccionResultado !== null ? "
- Correlación con el resultado de la predicción de IA obtenido" : "") . "

**2. INTERPRETACIÓN CLÍNICA POR PARÁMETROS:**
- Análisis individual de cada biomarcador
- Correlaciones fisiopatológicas relevantes
- Valores de referencia y desviaciones significativas

**3. RECOMENDACIONES DIAGNÓSTICAS:**
- Estudios complementarios sugeridos (HbA1c, PTOG, etc.)
- Periodicidad de seguimiento recomendada
- Criterios de derivación a especialista si aplica

**4. PLAN TERAPÉUTICO SUGERIDO:**
- Intervenciones no farmacológicas prioritarias
- Consideraciones farmacológicas si corresponde
- Objetivos terapéuticos específicos

**5. FACTORES DE RIESGO MODIFICABLES:**
- Identificación de elementos intervenibles
- Estrategias de prevención primaria/secundaria
" . (!empty($data['observacion']) ? "

**6. CONSIDERACIONES SOBRE LAS OBSERVACIONES MÉDICAS:**
- Análisis de las observaciones del profesional de salud
- Integración de estos hallazgos con los parámetros clínicos
- Recomendaciones específicas basadas en estas observaciones" : "") . "

**FORMATO:** Respuesta estructurada, concisa y basada en evidencia científica actual, dirigida a facilitar la decisión clínica del médico tratante.

**IMPORTANTE PARA EL FORMATO:**

1. Usa títulos HTML apropiados:
   - Para secciones principales: <h3>TÍTULO PRINCIPAL</h3>
   - Para subsecciones: <h4>Subtítulo</h4>
   - Para puntos específicos: <h5>Punto específico</h5>
2. Para la sección 'INTERPRETACIÓN CLÍNICA POR PARÁMETROS', utiliza el siguiente formato de tabla HTML:

<table>
<tr>
<th>Biomarcador</th>
<th>Valor</th>
<th>Interpretación Clínica</th>
<th>Valor de Referencia</th>
<th>Desviación</th>
</tr>
<tr>
<td>Nombre del parámetro</td>
<td>Valor medido</td>
<td>Interpretación médica</td>
<td>Rango normal</td>
<td>Significancia</td>
</tr>
</table>

3. Usa listas con viñetas (-) para recomendaciones y puntos clave
4. Para información importante usa: <div class='info-block'>contenido destacado</div>
5. Para advertencias usa: <div class='warning-block'>contenido de advertencia</div>
6. Para información positiva usa: <div class='success-block'>contenido positivo</div>
7. NO uses asteriscos dobles (**) para títulos, usa únicamente las etiquetas HTML especificadas

Esto asegurará una presentación clara, profesional y visualmente atractiva en el reporte médico.";
    }

    public function saveConfirmedPrediction(Request $request)
    {
        $validated = $request->validate([
            'idcita' => 'required|exists:cita,idcita',
            'embarazos' => 'required|numeric|min:0',
            'glucosa' => 'required|numeric|min:0|max:500', 
            'presion_sanguinea' => 'required|numeric|min:0|max:180',
            'grosor_piel' => 'required|numeric|min:0',
            'insulina' => 'required|numeric|min:0|max:200',
            'BMI' => 'required|numeric|min:0|max:180',
            'pedigree' => 'required|numeric|min:0|max:2',
            'edad' => 'required|numeric|min:18',
            'observacion' => 'nullable|string',
            'probability_diabetes' => 'required|numeric|min:0|max:1',
            'timer' => 'required|string',
            'timer_duration_ms' => 'required|numeric|min:0',
        ]);

        try {
            // Buscar si ya existe una predicción para esta cita
            $prediccion = Prediccion::where('idcita', $validated['idcita'])->first();
            
            if (!$prediccion) {
                // Si no existe, crear una nueva
                $prediccion = new Prediccion();
                $prediccion->idcita = $validated['idcita'];
            }
            
            // Actualizar todos los campos (tanto para nueva como existente)
            $prediccion->embarazos = $validated['embarazos'];
            $prediccion->glucosa = $validated['glucosa'];
            $prediccion->presion_sanguinea = $validated['presion_sanguinea'];
            $prediccion->grosor_piel = $validated['grosor_piel'];
            $prediccion->insulina = $validated['insulina'];
            $prediccion->BMI = $validated['BMI'];
            $prediccion->pedigree = $validated['pedigree'];
            $prediccion->edad = $validated['edad'];
            $prediccion->observacion = $validated['observacion'];
            $prediccion->resultado = $validated['probability_diabetes'];
            
            // **Cálculo del timer_parada y timer_inicio**
            $stopTime = now();
            $startTime = now()->subMilliseconds($validated['timer_duration_ms']);
            
            $prediccion->timer_inicio = $startTime;
            $prediccion->timer_parada = $stopTime;

            $timeParts = explode(':', $validated['timer']);
            $minutes = (int)$timeParts[0];
            $seconds = (int)$timeParts[1];
            $milliseconds = (int)$timeParts[2];
            $totalSeconds = ($minutes * 60) + $seconds + ($milliseconds / 100);
            $prediccion->timer = $totalSeconds;

            $prediccion->save();

            $cita = Cita::find($validated['idcita']);
            if ($cita) {
                $cita->estado = 'Realizado';
                $cita->save();
            }

            return redirect()->route('citas_doctores.index')->with('success', 'Predicción guardada exitosamente y cita actualizada.');

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('error', 'Error de validación: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error al guardar la predicción confirmada: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al guardar la predicción. Detalles: ' . $e->getMessage());
        }
    } 

    public function show($idprediccion)
    {
        $prediccion = Prediccion::with('cita')->findOrFail($idprediccion);
        return view('predicciones.show', compact('prediccion'));
    }

    public function edit($idprediccion)
    {
        $prediccion = Prediccion::with(['cita.paciente', 'cita.triaje'])->findOrFail($idprediccion);
        return view('predicciones.edit', compact('prediccion'));
    }
    
    public function processEditedPrediction(Request $request, $idprediccion)
    {
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|numeric|min:0',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0',
                'insulina' => 'required|numeric|min:0|max:200',
                'BMI' => 'required|numeric|min:0|max:180',
                'pedigree' => 'required|numeric|min:0|max:2',
                'edad' => 'required|numeric|min:18',
                'observacion' => 'nullable|string',
            ]);

            // Capturar el tiempo de inicio para la re-predicción
            $startTime = now();
            Session::put('prediccion_start_time', $startTime);

            $mlApiUrl = 'http://127.0.0.1:5000/predict';

            $response = Http::post($mlApiUrl, [
                'Pregnancies' => (float)$validated['embarazos'],
                'Glucose' => (float)$validated['glucosa'],
                'BloodPressure' => (float)$validated['presion_sanguinea'],
                'SkinThickness' => (float)$validated['grosor_piel'],
                'Insulin' => (float)$validated['insulina'],
                'BMI' => (float)$validated['BMI'],
                'DiabetesPedigreeFunction' => (float)$validated['pedigree'],
                'Age' => (float)$validated['edad'],
            ]);

            $response->throw();

            $predictionResult = $response->json();

            return response()->json([
                'success' => true,
                'message' => 'Re-predicción obtenida con éxito',
                'predictionResult' => $predictionResult,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('Error al conectar con la API de Flask para re-predicción: ' . $e->getMessage(), ['url' => $mlApiUrl, 'response' => $e->response ? $e->response->body() : 'N/A']);
            return response()->json([
                'success' => false,
                'error' => 'No se pudo conectar con el servicio de predicción para re-evaluar. Asegúrese de que la API de ML esté en funcionamiento y accesible.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error inesperado al procesar la re-predicción: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error inesperado al procesar la re-predicción. Por favor, inténtelo de nuevo.'
            ], 500);
        }
    }

    public function updateConfirmedPrediction(Request $request, $idprediccion)
    {
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|numeric|min:0',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0',
                'insulina' => 'required|numeric|min:0|max:200',
                'BMI' => 'required|numeric|min:0|max:180',
                'pedigree' => 'required|numeric|min:0|max:2',
                'edad' => 'required|numeric|min:18',
                'observacion' => 'nullable|string',
                'timer' => 'required|string',
                'probability_diabetes' => 'required|numeric|min:0|max:1',
                // Agregamos este campo para un cálculo robusto
                'timer_duration_ms' => 'required|numeric|min:0', 
            ]);

            $prediccion = Prediccion::findOrFail($idprediccion);

            // Reemplazamos la dependencia de la sesión con un cálculo directo
            $stopTime = now();
            $startTime = now()->subMilliseconds($validated['timer_duration_ms']);

            $prediccion->idcita = $validated['idcita'];
            $prediccion->embarazos = $validated['embarazos'];
            $prediccion->glucosa = $validated['glucosa'];
            $prediccion->presion_sanguinea = $validated['presion_sanguinea'];
            $prediccion->grosor_piel = $validated['grosor_piel'];
            $prediccion->insulina = $validated['insulina'];
            $prediccion->BMI = $validated['BMI'];
            $prediccion->pedigree = $validated['pedigree'];
            $prediccion->edad = $validated['edad'];
            $prediccion->observacion = $validated['observacion'];
            
            $timeParts = explode(':', $validated['timer']);
            $minutes = (int)$timeParts[0];
            $seconds = (int)$timeParts[1];
            $milliseconds = (int)$timeParts[2];
            $totalSeconds = ($minutes * 60) + $seconds + ($milliseconds / 100);
            $prediccion->timer = $totalSeconds;
            $prediccion->resultado = $validated['probability_diabetes'];
            
            // Asignar los campos de fecha y hora
            $prediccion->timer_inicio = $startTime;
            $prediccion->timer_parada = $stopTime;

            $prediccion->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Predicción actualizada exitosamente con nuevos resultados de ML.',
                'redirect' => route('predicciones.index')
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar la predicción editada y confirmada: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al guardar los cambios en la predicción. Detalles: ' . $e->getMessage(),
                'details' => $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($idprediccion)
    {
        $prediccion = Prediccion::findOrFail($idprediccion);
        $prediccion->delete();

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción eliminada exitosamente');
    }

    public function pdf($id)
    {
        $prediccion = Prediccion::with(['cita.paciente'])->findOrFail($id);
        $pdf = Pdf::loadView('predicciones.pdf', compact('prediccion'));
        return $pdf->stream('reporte-prediccion-'.$id.'.pdf');
    }
}
