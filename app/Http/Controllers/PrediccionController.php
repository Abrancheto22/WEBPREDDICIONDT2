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
use Illuminate\Support\Facades\Storage;
use App\Exports\PrediccionesExport;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Carbon\Carbon; // Importar la clase Carbon
use Illuminate\Support\Facades\Mail;

class PrediccionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $query = Prediccion::with(['cita.paciente']);

        if ((int) $user->idrol === 2) {
            $doctorId = $user->doctor?->iddoctor;
            abort_unless($doctorId, 403, 'La cuenta no tiene un perfil médico asociado.');
            $query->whereHas('cita', fn ($cita) => $cita->where('iddoctor', $doctorId));
        } elseif ((int) $user->idrol === 4) {
            $pacienteId = $user->paciente?->idpaciente;
            abort_unless($pacienteId, 403, 'La cuenta no tiene un perfil de paciente asociado.');
            $query->whereHas('cita', fn ($cita) => $cita->where('idpaciente', $pacienteId));
        } else {
            abort_unless((int) $user->idrol === 1, 403);
        }

        $predicciones = $query->get();
        return view('predicciones.index', compact('predicciones'));
    }

    public function create($idcita = null)
{
    $cita = null;
    if ($idcita) {
        
        $cita = Cita::with(['triaje', 'paciente', 'doctor', 'enfermera'])->find($idcita);
        if (! $cita) {
            return redirect()->back()->with('error', 'Cita no encontrada');
        }
        $this->authorizeAppointment($cita);
        if (! $cita->triaje) {
            return redirect()->back()->with('error', 'La cita no tiene un triaje asociado');
        }
    }
    $citasQuery = Cita::with(['triaje', 'paciente', 'doctor', 'enfermera']);
    if ((int) auth()->user()->idrol === 2) {
        $doctorId = auth()->user()->doctor?->iddoctor;
        abort_unless($doctorId, 403, 'La cuenta no tiene un perfil médico asociado.');
        $citasQuery->where('iddoctor', $doctorId);
    }
    $citas = $citasQuery->get();




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
                'embarazos' => 'required|integer|min:0|max:20',
                'glucosa' => 'required|numeric|min:0|max:500', 
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0|max:100',
                'insulina' => 'required|numeric|min:0|max:1000',
                'BMI' => 'required|numeric|min:0|max:100',
                'pedigree' => 'required|numeric|min:0|max:5',
                'edad' => $this->predictionAgeRule(),
                'observacion' => 'nullable|string|max:5000',
                'documentos_adjuntos' => 'nullable|array|max:5',
                'documentos_adjuntos.*' => 'file|mimes:pdf,docx,jpg,png|max:10240', // 10MB por archivo
            ]);

            $cita = Cita::with('paciente')->findOrFail($validated['idcita']);
            $this->authorizeAppointment($cita);

            // Manejo de archivos adjuntos
            $attachmentPaths = [];
            $attachmentNames = [];
            if ($request->hasFile('documentos_adjuntos')) {
                foreach ($request->file('documentos_adjuntos') as $file) {
                    $filePath = $file->store('attachments', config('filesystems.attachments_disk', 'local'));
                    $attachmentPaths[] = $filePath;
                    $attachmentNames[] = $file->getClientOriginalName();
                }
                Session::put('uploaded_attachment_paths', $attachmentPaths);
                Session::put('uploaded_attachment_names', $attachmentNames);
            }

            // **1. Capturar el tiempo de inicio y guardarlo en la sesión**
            $startTime = now();
            Session::put('prediccion_start_time', $startTime);

            $predictionResult = $this->requestModelPrediction($validated);

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
            Log::error('No se pudo obtener predicción del servicio ML.', ['status' => $e->response?->status()]);
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener la predicción del modelo de ML. Por favor, inténtelo de nuevo más tarde.',
            ], 500);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error inesperado al procesar la predicción.', ['exception' => get_class($e), 'message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => config('app.debug')
                    ? 'Error: ' . $e->getMessage()
                    : 'Ocurrió un error inesperado al procesar la predicción. Por favor, inténtelo de nuevo.',
            ], 500);
        }
    }
    
    public function analyzeWithGemini(Request $request)
    {
        set_time_limit(120);
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|integer|min:0|max:20',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0|max:100',
                'insulina' => 'required|numeric|min:0|max:1000',
                'BMI' => 'required|numeric|min:0|max:100',
                'pedigree' => 'required|numeric|min:0|max:5',
                'edad' => $this->predictionAgeRule(),
                'observacion' => 'nullable|string|max:5000',
                'documentos_adjuntos' => 'nullable|array|max:5',
                'documentos_adjuntos.*' => 'file|mimes:pdf,docx,jpg,png|max:10240', // 10MB por archivo
            ]);

            // Manejo de archivos adjuntos
            $attachmentPaths = [];
            $attachmentNames = [];
            if ($request->hasFile('documentos_adjuntos')) {
                foreach ($request->file('documentos_adjuntos') as $file) {
                    $filePath = $file->store('attachments', config('filesystems.attachments_disk', 'local'));
                    $attachmentPaths[] = $filePath;
                    $attachmentNames[] = $file->getClientOriginalName();
                }
                
                // Guardar en sesión para usar en el análisis
                Session::put('uploaded_attachment_paths', $attachmentPaths);
                Session::put('uploaded_attachment_names', $attachmentNames);
                
            }

            // Obtener información del paciente
            $cita = Cita::with(['paciente', 'triaje'])->find($validated['idcita']);
            abort_unless($cita && $cita->paciente, 404, 'Cita o paciente no encontrado.');
            $this->authorizeAppointment($cita);
            $paciente = $cita->paciente;
            
            // Ajustar embarazos según el género del paciente
            $embarazos = $validated['embarazos'];
            if ($paciente->sexo === 'Masculino' && $embarazos > 0) {
                $embarazos = 0; // Los hombres no pueden tener embarazos
            }

            // Verificar si ya existe una predicción para obtener el resultado
            $prediccionExistente = Prediccion::where('idcita', $validated['idcita'])->first();
            $resultadoPrediccion = $prediccionExistente ? $prediccionExistente->resultado : null;

            // Recuperar los archivos adjuntos de la sesión
            $attachmentPaths = Session::get('uploaded_attachment_paths', []);
            // Enviar solo variables clínicas necesarias. No enviar identificadores,
            // observaciones libres ni documentos que puedan contener datos identificables.
            $prompt = $this->buildGeminiPrompt($validated, $paciente, $embarazos, $resultadoPrediccion);
            $parts = [['text' => $prompt]];

            // Llamar a la API de Gemini
            // IMPORTANTE: Usar config() en lugar de env() porque en producción con caché env() devuelve null
            $geminiApiKey = config('services.gemini.api_key');
            $geminiUrl = config('services.gemini.api_url');

            // Fallback de seguridad: Si la URL está vacía o no se cargó, usar la predeterminada
            if (empty($geminiUrl)) {
                $geminiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
            }

            // Limpieza crítica: eliminar espacios en blanco accidentales
            $geminiApiKey = trim((string)$geminiApiKey);
            $geminiUrl = trim((string)$geminiUrl);

            // VERIFICACIÓN EXPLÍCITA DE LA KEY
            if (empty($geminiApiKey)) {
                throw new \RuntimeException('Servicio de análisis no configurado.');
            }

            // Construcción de la URL con la Key
            $urlWithKey = $geminiUrl;
            // Si la URL no tiene ?key=, se lo agregamos.
            if (strpos($urlWithKey, 'key=') === false) {
                $separator = strpos($urlWithKey, '?') === false ? '?' : '&';
                $urlWithKey .= $separator . 'key=' . $geminiApiKey;
            }

            // Log de depuración (Ocultando parte de la key por seguridad)
            // Validación final de URL antes de enviar
            if (!filter_var($urlWithKey, FILTER_VALIDATE_URL)) {
                throw new \RuntimeException('El servicio de análisis no está disponible.');
            }

            // Llamada HTTP limpia (usando solo URL params para la auth, como prefiere Google en llamadas simples)
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($urlWithKey, [
                'contents' => [
                    [
                        'parts' => $parts
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::warning('El proveedor de análisis devolvió un estado no exitoso.', ['status' => $response->status()]);
                throw new \RuntimeException('El análisis con IA no está disponible en este momento.');
            }

            $geminiResult = $response->json();
            // Check if 'candidates' key exists and is not empty
            if (!isset($geminiResult['candidates']) || empty($geminiResult['candidates'])) {
                throw new \RuntimeException('El análisis con IA no devolvió un resultado utilizable.');
            }
            $analysisText = $geminiResult['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo obtener análisis';

            // Procesar el texto de análisis para mejorar el formato
            $analysisText = $this->formatAnalysisText($analysisText);

            // Buscar si ya existe una predicción para esta cita
            $prediccion = Prediccion::where('idcita', $validated['idcita'])->first();
            
            if ($prediccion) {
                // Si existe, actualizar el análisis de IA
                $prediccion->analisis_ia = $analysisText;
                // No crear un resultado numérico ficticio por un análisis generativo.
                $prediccion->timer = 0;
                $prediccion->timer_inicio = now()->format('Y-m-d H:i:s');
                $prediccion->timer_parada = now()->format('Y-m-d H:i:s');
                
                // Guardar archivos adjuntos desde la sesión
                $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', []);
                $prediccion->attachment_names = Session::get('uploaded_attachment_names', []);
                
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
                $prediccion->observacion = $validated['observacion'] ?? null;
                $prediccion->analisis_ia = $analysisText;
                $prediccion->timer = 0; // Valor temporal
                $prediccion->timer_inicio = now();
                $prediccion->timer_parada = now();
                
                // Guardar archivos adjuntos desde la sesión
                $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', []);
                $prediccion->attachment_names = Session::get('uploaded_attachment_names', []);
                
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
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error durante el análisis con IA.', ['exception' => get_class($e)]);
            return response()->json([
                'success' => false,
                'error' => 'No se pudo completar el análisis con IA en este momento.'
            ], 500);
        }
    }

    private function getTextFromDocx($filePath)
    {
        $content = '';
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                $xmlData = $zip->getFromIndex($index);
                // Reemplazar cierres de párrafo con saltos de línea para mantener estructura básica
                $xmlData = str_replace('</w:p>', "\n", $xmlData);
                $content = strip_tags($xmlData);
            }
            $zip->close();
        }
        return $content;
    }

    private function buildGeminiPrompt($data, $paciente, $embarazos, $prediccionResultado = null, $attachmentPaths = [])
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
            
            $prediccionInfo = "\n\n**RESULTADO DE LA PREDICCIÓN DE IA:**\n- Diagnóstico: {$diagnostico}\n- Probabilidad: {$probabilidad}%\n- Nivel de Riesgo: {$riesgo['nivel']}\n- Descripción del Riesgo: {$riesgo['descripcion']}";
        }

        // Mejorar la presentación de las observaciones
        $observacionesInfo = !empty($data['observacion']) 
            ? "\n**OBSERVACIONES MÉDICAS DEL PROFESIONAL:**\n{$data['observacion']}" 
            : "";

        $prompt = "Eres un asistente médico experto en diabetes tipo 2. Analiza la siguiente información del paciente y los documentos adjuntos (si los hay) para proporcionar un análisis detallado, estratificación de riesgo, interpretación de resultados, recomendaciones y un plan terapéutico integral. Considera toda la información clínica proporcionada para tu análisis.\n\n" .
            "**INFORMACIÓN DEL PACIENTE:**\n" .
            "- Nombre: {$paciente->nombre} {$paciente->apellido}\n" .
            "- Sexo: {$paciente->sexo}\n" .
            "- Edad: {$data['edad']}\n" .
            "- Número de Embarazos: {$embarazos}{$sexoInfo}\n" .
            "- Glucosa: {$data['glucosa']} mg/dL\n" .
            "- Presión Sanguínea: {$data['presion_sanguinea']} mmHg\n" .
            "- Grosor de la Piel: {$data['grosor_piel']} mm\n" .
            "- Insulina: {$data['insulina']} muU/ml\n" .
            "- IMC: {$data['BMI']}\n" .
            "- Función Pedigree de Diabetes: {$data['pedigree']}\n" .
            $prediccionInfo .
            $observacionesInfo .
            "\n\n**REGLAS DE FORMATO:**\n" .
            "- NO uses símbolos ni fórmulas en formato LaTeX (NUNCA uses signos de dólar '$', ni '\\ge', '\\le', '\\text{}', '\\mu').\n" .
            "- Usa siempre símbolos médicos y numéricos limpios: '≥', '≤', '>', '<', 'µU/ml', 'mg/dL', 'mmHg', 'kg/m²'.\n" .
            "- Estructura tu respuesta directamente con los parámetros clínicos, interpretación, estratificación de riesgo y recomendaciones terapéuticas.\n" .
            "- NO incluyas bajo ninguna circunstancia bloques ni secciones de advertencia clínica sobre discrepancias con predicciones ni críticas de algoritmos.";

        return $prompt;
    }

    public function saveConfirmedPrediction(Request $request)
    {
        $validated = $request->validate([
            'idcita' => 'required|exists:cita,idcita',
            'embarazos' => 'required|integer|min:0|max:20',
            'glucosa' => 'required|numeric|min:0|max:500', 
            'presion_sanguinea' => 'required|numeric|min:0|max:180',
            'grosor_piel' => 'required|numeric|min:0|max:100',
            'insulina' => 'required|numeric|min:0|max:1000',
            'BMI' => 'required|numeric|min:0|max:100',
            'pedigree' => 'required|numeric|min:0|max:5',
            'edad' => $this->predictionAgeRule('numeric'),
            'observacion' => 'nullable|string|max:5000',
        ]);

        try {
            $cita = Cita::with('paciente')->findOrFail($validated['idcita']);
            $this->authorizeAppointment($cita);
            $predictionResult = $this->requestModelPrediction($validated);

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
            $prediccion->observacion = $validated['observacion'] ?? null;
            $prediccion->resultado = $predictionResult['probability_diabetes'];
            
            // **Cálculo del timer_parada y timer_inicio**
            $stopTime = now();
            $elapsedMilliseconds = $this->elapsedPredictionMilliseconds();
            $startTime = $stopTime->copy()->subMilliseconds($elapsedMilliseconds);
            
            $prediccion->timer_inicio = $startTime;
            $prediccion->timer_parada = $stopTime;

            $prediccion->timer = $elapsedMilliseconds / 1000;

            // Los adjuntos proceden de la sesión del flujo autenticado, no del cliente.
            $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', $prediccion->attachment_paths ?? []);
            $prediccion->attachment_names = Session::get('uploaded_attachment_names', $prediccion->attachment_names ?? []);

            DB::transaction(function () use ($prediccion, $cita) {
                $prediccion->save();
                $cita->estado = 'Realizado';
                $cita->save();
            });

            Session::forget(['uploaded_attachment_paths', 'uploaded_attachment_names', 'prediccion_start_time']);

            return redirect()->route('citas_doctores.index')->with('success', 'Predicción guardada exitosamente y cita actualizada.');

        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('No se pudo guardar la predicción confirmada.', ['exception' => get_class($e)]);
            return redirect()->back()->withInput()->with('error', 'No se pudo guardar la predicción. Verifique el servicio y los datos e inténtelo nuevamente.');
        }
    } 

    public function show($idprediccion)
    {
        $prediccion = $this->visiblePredictionOrFail($idprediccion)->load('cita');
        return view('predicciones.show', compact('prediccion'));
    }

    public function edit($idprediccion)
    {
        $prediccion = Prediccion::with(['cita.paciente', 'cita.triaje'])->findOrFail($idprediccion);
        $this->authorizeAppointment($prediccion->cita);
        return view('predicciones.edit', compact('prediccion'));
    }
    
    public function processEditedPrediction(Request $request, $idprediccion)
    {
        try {
            $validated = $request->validate([
                'idcita' => 'required|exists:cita,idcita',
                'embarazos' => 'required|integer|min:0|max:20',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0|max:100',
                'insulina' => 'required|numeric|min:0|max:1000',
                'BMI' => 'required|numeric|min:0|max:100',
                'pedigree' => 'required|numeric|min:0|max:5',
                'edad' => $this->predictionAgeRule(),
                'observacion' => 'nullable|string',
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif|max:5120',
                'removed_attachments' => 'nullable|string',
            ]);

            // Manejar archivos adjuntos eliminados
            $prediccion = Prediccion::findOrFail($idprediccion);
            $this->authorizeAppointment($prediccion->cita);
            abort_unless((int) $prediccion->idcita === (int) $validated['idcita'], 403, 'No se puede mover una predicción a otra cita.');
            $currentPaths = $prediccion->attachment_paths ?? [];
            $currentNames = $prediccion->attachment_names ?? [];
            
            if ($request->has('removed_attachments') && !empty($request->removed_attachments)) {
                $removedIndices = json_decode($request->removed_attachments, true);
                if (is_array($removedIndices)) {
                    // Eliminar archivos físicos
                    foreach ($removedIndices as $index) {
                        if (isset($currentPaths[$index])) {
                    Storage::disk(config('filesystems.attachments_disk', 'local'))->delete($currentPaths[$index]);
                        }
                    }
                    
                    // Filtrar arrays eliminando los índices marcados
                    $currentPaths = array_values(array_filter($currentPaths, function($key) use ($removedIndices) {
                        return !in_array($key, $removedIndices);
                    }, ARRAY_FILTER_USE_KEY));
                    
                    $currentNames = array_values(array_filter($currentNames, function($key) use ($removedIndices) {
                        return !in_array($key, $removedIndices);
                    }, ARRAY_FILTER_USE_KEY));
                }
            }

            // Manejar nuevos archivos adjuntos
            $newAttachmentPaths = [];
            $newAttachmentNames = [];
            
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $file->store('attachments', config('filesystems.attachments_disk', 'local'));
                    
                    $newAttachmentPaths[] = $path;
                    $newAttachmentNames[] = $originalName;
                }
            }

            // Combinar archivos existentes con nuevos
            $allAttachmentPaths = array_merge($currentPaths, $newAttachmentPaths);
            $allAttachmentNames = array_merge($currentNames, $newAttachmentNames);

            // Guardar en sesión para usar en la API de ML
            Session::put('uploaded_attachment_paths', $allAttachmentPaths);
            Session::put('uploaded_attachment_names', $allAttachmentNames);

            // Capturar el tiempo de inicio para la re-predicción
            $startTime = now();
            Session::put('prediccion_start_time', $startTime);

            $predictionResult = $this->requestModelPrediction($validated);

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
            Log::error('No se pudo obtener una re-predicción del servicio ML.', ['status' => $e->response?->status()]);
            return response()->json([
                'success' => false,
                'error' => 'No se pudo conectar con el servicio de predicción para re-evaluar. Asegúrese de que la API de ML esté en funcionamiento y accesible.'
            ], 500);
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error inesperado al procesar la re-predicción.', ['exception' => get_class($e)]);
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
                'embarazos' => 'required|integer|min:0|max:20',
                'glucosa' => 'required|numeric|min:0|max:500',
                'presion_sanguinea' => 'required|numeric|min:0|max:180',
                'grosor_piel' => 'required|numeric|min:0|max:100',
                'insulina' => 'required|numeric|min:0|max:1000',
                'BMI' => 'required|numeric|min:0|max:100',
                'pedigree' => 'required|numeric|min:0|max:5',
                'edad' => $this->predictionAgeRule('numeric'),
                'observacion' => 'nullable|string|max:5000',
            ]);

            $prediccion = Prediccion::findOrFail($idprediccion);
            $this->authorizeAppointment($prediccion->cita);
            abort_unless((int) $prediccion->idcita === (int) $validated['idcita'], 403, 'No se puede mover una predicción a otra cita.');
            $predictionResult = $this->requestModelPrediction($validated);

            // Reemplazamos la dependencia de la sesión con un cálculo directo
            $stopTime = now();
            $elapsedMilliseconds = $this->elapsedPredictionMilliseconds();
            $startTime = $stopTime->copy()->subMilliseconds($elapsedMilliseconds);

            $prediccion->idcita = $validated['idcita'];
            $prediccion->embarazos = $validated['embarazos'];
            $prediccion->glucosa = $validated['glucosa'];
            $prediccion->presion_sanguinea = $validated['presion_sanguinea'];
            $prediccion->grosor_piel = $validated['grosor_piel'];
            $prediccion->insulina = $validated['insulina'];
            $prediccion->BMI = $validated['BMI'];
            $prediccion->pedigree = $validated['pedigree'];
            $prediccion->edad = $validated['edad'];
            $prediccion->observacion = $validated['observacion'] ?? null;
            
            $prediccion->timer = $elapsedMilliseconds / 1000;
            $prediccion->resultado = $predictionResult['probability_diabetes'];
            
            // Asignar los campos de fecha y hora
            $prediccion->timer_inicio = $startTime;
            $prediccion->timer_parada = $stopTime;

            // Recuperar y guardar los archivos adjuntos de la sesión
            $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', $prediccion->attachment_paths ?? []);
            $prediccion->attachment_names = Session::get('uploaded_attachment_names', $prediccion->attachment_names ?? []);

            $prediccion->save();

            // Limpiar los datos de archivos adjuntos de la sesión
            Session::forget(['uploaded_attachment_paths', 'uploaded_attachment_names', 'prediccion_start_time']);
            
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
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al guardar la predicción editada y confirmada: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'No se pudieron guardar los cambios en la predicción.',
            ], 500);
        }
    }
    
    public function updateValidacion(Request $request, $idprediccion)
    {
        $validated = $request->validate([
            'validar_prediccion' => 'required|in:1,0',
        ]);

        $prediccion = Prediccion::findOrFail($idprediccion);
        $this->authorizeAppointment($prediccion->cita);
        $prediccion->validar_prediccion = (int) $validated['validar_prediccion'];
        $prediccion->save();

        return response()->json([
            'success' => true,
            'message' => 'Validación actualizada',
            'validar_prediccion' => (int) $prediccion->validar_prediccion,
        ]);
    }

    public function destroy($idprediccion)
    {
        $prediccion = Prediccion::findOrFail($idprediccion);
        $this->authorizeAppointment($prediccion->cita);
        $prediccion->delete();

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción eliminada exitosamente');
    }

    public function pdf($id)
    {
        $prediccion = $this->visiblePredictionOrFail($id)->load('cita.paciente');
        $pdf = DomPDF::loadView('predicciones.pdf', compact('prediccion'));
        return $pdf->stream('reporte-prediccion-'.$id.'.pdf');
    }

    public function downloadAttachment($id, $index)
    {
        try {
            $prediccion = $this->visiblePredictionOrFail($id);
            
            // Verificar que existan archivos adjuntos
            if (!$prediccion->attachment_paths || !isset($prediccion->attachment_paths[$index])) {
                return redirect()->back()->with('error', 'Archivo adjunto no encontrado.');
            }
            
            $attachmentPath = $prediccion->attachment_paths[$index];
            $attachmentName = $prediccion->attachment_names[$index] ?? basename($attachmentPath);
            
            // Construir la ruta completa del archivo
            $disk = Storage::disk(config('filesystems.attachments_disk', 'local'));
            if (!$disk->exists($attachmentPath)) {
                return redirect()->back()->with('error', 'El archivo adjunto no existe en el servidor.');
            }
            
            return $disk->download($attachmentPath, basename($attachmentName));
            
        } catch (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error al descargar archivo adjunto: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al descargar el archivo adjunto.');
        }
    }

    private function calculateRiskLevel($probabilidad)
    {
        if ($probabilidad >= 0.8) {
            return [
                'nivel' => 'MUY ALTO',
                'descripcion' => 'Riesgo muy elevado de diabetes tipo 2. Se recomienda evaluación médica inmediata y seguimiento especializado.'
            ];
        } elseif ($probabilidad >= 0.6) {
            return [
                'nivel' => 'ALTO',
                'descripcion' => 'Riesgo alto de diabetes tipo 2. Se sugiere consulta médica pronta y monitoreo regular de glucosa.'
            ];
        } elseif ($probabilidad >= 0.4) {
            return [
                'nivel' => 'MODERADO',
                'descripcion' => 'Riesgo moderado de diabetes tipo 2. Se recomienda adoptar hábitos saludables y controles periódicos.'
            ];
        } elseif ($probabilidad >= 0.2) {
            return [
                'nivel' => 'BAJO',
                'descripcion' => 'Riesgo bajo de diabetes tipo 2. Mantener estilo de vida saludable como medida preventiva.'
            ];
        } else {
            return [
                'nivel' => 'MUY BAJO',
                'descripcion' => 'Riesgo muy bajo de diabetes tipo 2. Continuar con hábitos saludables para mantener este estado.'
            ];
        }
    }

    private function authorizeAppointment(Cita $cita): void
    {
        $user = auth()->user();
        abort_unless($user, 401);

        if ((int) $user->idrol === 1) {
            return;
        }

        $doctorId = $user->doctor?->iddoctor;
        abort_unless(
            (int) $user->idrol === 2
                && $doctorId !== null
                && (int) $cita->iddoctor === (int) $doctorId,
            403,
            'No tiene permisos sobre esta cita.'
        );
    }

    private function visiblePredictionOrFail(int|string $id): Prediccion
    {
        $user = auth()->user();
        abort_unless($user, 401);

        $query = Prediccion::query();
        if ((int) $user->idrol === 1) {
            return $query->findOrFail($id);
        }

        if ((int) $user->idrol === 2) {
            $doctorId = $user->doctor?->iddoctor;
            abort_unless($doctorId, 403);
            return $query->whereHas('cita', fn ($cita) => $cita->where('iddoctor', $doctorId))
                ->findOrFail($id);
        }

        if ((int) $user->idrol === 4) {
            $pacienteId = $user->paciente?->idpaciente;
            abort_unless($pacienteId, 403);
            return $query->whereHas('cita', fn ($cita) => $cita->where('idpaciente', $pacienteId))
                ->findOrFail($id);
        }

        abort(403, 'No tiene permisos para ver predicciones.');
    }

    private function requestModelPrediction(array $data): array
    {
        $endpoint = config('services.ml.predict_url');
        if (!is_string($endpoint) || !filter_var($endpoint, FILTER_VALIDATE_URL) || !str_starts_with($endpoint, 'https://')) {
            throw new \RuntimeException('El endpoint HTTPS del modelo no está configurado.');
        }

        $apiKey = config('services.ml.api_key');
        $requestHttp = Http::asJson()->acceptJson()->connectTimeout(5)->timeout(20);
        if (is_string($apiKey) && strlen(trim($apiKey)) >= 32) {
            $requestHttp = $requestHttp->withToken(trim($apiKey));
        }

        $response = $requestHttp->post($endpoint, [
            'Pregnancies' => (float) $data['embarazos'],
            'Glucose' => (float) $data['glucosa'],
            'BloodPressure' => (float) $data['presion_sanguinea'],
            'SkinThickness' => (float) $data['grosor_piel'],
            'Insulin' => (float) $data['insulina'],
            'BMI' => (float) $data['BMI'],
            'DiabetesPedigreeFunction' => (float) $data['pedigree'],
            'Age' => (float) $data['edad'],
        ]);
        $response->throw();

        $result = $response->json();
        $probability = is_array($result) ? ($result['probability_diabetes'] ?? null) : null;
        if (!is_numeric($probability) || !is_finite((float) $probability) || (float) $probability < 0 || (float) $probability > 1) {
            throw new \UnexpectedValueException('El servicio de ML devolvió una probabilidad inválida.');
        }

        $probability = (float) $probability;
        $prediction = $probability >= 0.5 ? 1 : 0;
        [$riskLevel, $riskDescription] = match (true) {
            $probability < 0.30 => ['BAJO', 'Riesgo bajo de Diabetes Tipo 2.'],
            $probability < 0.50 => ['MODERADO', 'Riesgo moderado. Recomendable seguimiento preventivo.'],
            $probability < 0.70 => ['ALTO', 'Riesgo alto. Evaluación clínica prioritaria.'],
            default => ['MUY ALTO', 'Riesgo muy alto. Atención clínica inmediata recomendada.'],
        };

        return [
            'prediction' => $prediction,
            'resultado' => $probability,
            'probability_diabetes' => $probability,
            'probability_no_diabetes' => 1 - $probability,
            'diagnosis' => $prediction === 1 ? 'Diabetes' : 'No Diabetes',
            'threshold_used' => 0.5,
            'risk_level' => $riskLevel,
            'risk_description' => $riskDescription,
            'message' => 'Predicción procesada exitosamente.',
            'aviso_legal' => 'Estimación de apoyo al tamizaje; no reemplaza la evaluación ni el criterio del profesional de salud.',
        ];
    }

    private function predictionAgeRule(string $type = 'integer'): string
    {
        $minimum = (int) config('services.ml.age_min', 21);
        $maximum = (int) config('services.ml.age_max', 81);

        if ($minimum < 21 || $maximum < $minimum || $maximum > 81) {
            throw new \LogicException('El rango de edades configurado para el modelo no es válido.');
        }

        return sprintf('required|%s|min:%d|max:%d', $type, $minimum, $maximum);
    }

    private function elapsedPredictionMilliseconds(): int
    {
        $startedAt = Session::get('prediccion_start_time');
        if (!$startedAt) {
            return 0;
        }

        try {
            $startedAt = $startedAt instanceof Carbon ? $startedAt : Carbon::parse($startedAt);
            return max(0, (int) $startedAt->diffInMilliseconds(now()));
        } catch (\Throwable) {
            return 0;
        }
    }

    private function formatAnalysisText($text)
    {
        // 1. Limpieza profunda de notación LaTeX y fórmulas matemáticas
        $latexReplacements = [
            '\\ge' => '≥',
            '\\le' => '≤',
            '\\geq' => '≥',
            '\\leq' => '≤',
            '\\mu' => 'µ',
            '\\times' => '×',
            '\\pm' => '±',
            '\\text{kg/m}^2' => 'kg/m²',
            '\\text{kg/m²}' => 'kg/m²',
            'kg/m^2' => 'kg/m²',
            'mg/dl' => 'mg/dL',
            'mmhg' => 'mmHg',
            'muU/ml' => 'µU/ml',
        ];
        $text = str_ireplace(array_keys($latexReplacements), array_values($latexReplacements), $text);

        // Limpiar \text{algo} -> algo
        $text = preg_replace('/\\\\text\s*\{\s*([^{}]+)\s*\}/u', '$1', $text);
        $text = preg_replace('/\\\\text\s*\{\s*\}/u', ' ', $text);

        // Limpiar llaves { } residuales de LaTeX
        $text = str_replace(['{', '}'], '', $text);

        // Eliminar $ alrededor de números, fórmulas o unidades ($...$)
        $text = preg_replace('/\$([^$]+)\$/u', '$1', $text);
        $text = str_replace('$', '', $text);

        // 2. Normalizar saltos de línea (CRLF a LF)
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // 3. Filtrar y eliminar por completo secciones de advertencia clínica o críticas de predicción
        $text = preg_replace('/^#{2,4}\s*.*(?:cr[ií]tica|discrepancia|observaci[oó]n m[eé]rita).*$/miu', '', $text);

        // Eliminar bloques de cita (> ...) que contengan advertencias o discrepancias
        $lines = explode("\n", $text);
        $cleanLines = [];
        $inDiscardQuote = false;
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (str_starts_with($trimmed, '>')) {
                if (stripos($trimmed, 'ADVERTENCIA') !== false || stripos($trimmed, 'discrepancia') !== false || $inDiscardQuote) {
                    $inDiscardQuote = true;
                    continue; // Descartar línea de advertencia
                }
            } else {
                $inDiscardQuote = false;
            }
            $cleanLines[] = $line;
        }
        $text = implode("\n", $cleanLines);

        // Eliminar párrafos sueltos que empiecen con ¡ADVERTENCIA CLÍNICA! o ADVERTENCIA CLÍNICA
        $text = preg_replace('/^[¡!]?\s*ADVERTENCIA\s+CL[IÍ]NICA[^\n]*(?:\n(?![#\*\•\-])[^\n]+)*/miu', '', $text);

        // 4. Normalizar encabezados Markdown (## o ###)
        // Soportar tanto "### Encabezado" como "###\nEncabezado"
        $text = preg_replace('/^#{2,4}\s*\n\s*/m', '### ', $text);

        $text = preg_replace_callback(
            '/^#{2,4}\s+([^\n#]+)$/m',
            function ($m) {
                $title = trim($m[1]);
                // Si el título contiene crítica o advertencia, no mostrarlo
                if (stripos($title, 'crítica') !== false || stripos($title, 'discrepancia') !== false || stripos($title, 'advertencia') !== false) {
                    return '';
                }
                $icon = 'fa-stethoscope';
                if (stripos($title, 'parámetro') !== false || stripos($title, 'laboratorio') !== false) {
                    $icon = 'fa-flask';
                } elseif (stripos($title, 'recomend') !== false || stripos($title, 'plan') !== false || stripos($title, 'terap') !== false) {
                    $icon = 'fa-pills';
                } elseif (stripos($title, 'riesgo') !== false || stripos($title, 'estratific') !== false) {
                    $icon = 'fa-chart-pie';
                }
                return '<div class="ai-section-title mt-4 mb-3 d-flex align-items-center"><span class="ai-section-icon me-2"><i class="fas ' . $icon . '"></i></span><h5 class="mb-0 fw-bold text-dark">' . $title . '</h5></div>';
            },
            $text
        );

        // 5. Convertir divisores --- o ***
        $text = preg_replace('/^\s*[-*_]{3,}\s*$/m', '<hr class="ai-divider my-4">', $text);

        // 6. Formatear negritas Markdown (**texto**)
        $text = preg_replace('/\*\*(.*?)\*\*/su', '<strong>$1</strong>', $text);

        // 7. Formatear interpretaciones indentadas (* *Interpretación:* ... o * Interpretación: ...)
        $text = preg_replace_callback(
            '/^\s*[\*\•\-]\s*(?:\*|<strong>)?\s*(Interpretaci[oó]n|Nota cl[ií]nica|Comentario|Significado)\s*:?(?:\*|<\/strong>)?\s*:?\s*(.*?)$/miu',
            function ($m) {
                $label = $m[1];
                $body = $m[2];
                return '<div class="ai-interpretation-box my-2 ms-4 p-2 ps-3 rounded-2 shadow-sm">' .
                       '<small class="text-primary fw-bold text-uppercase d-block mb-1"><i class="fas fa-stethoscope me-1"></i>' . $label . ':</small>' .
                       '<span class="text-secondary-emphasis small">' . $body . '</span>' .
                       '</div>';
            },
            $text
        );

        // 8. Formatear viñetas de nivel superior (• o * o -)
        $text = preg_replace_callback(
            '/^\s*[\*\•\-]\s+(?!<div)(.*?)$/mu',
            function ($m) {
                $content = $m[1];
                return '<li class="ai-list-item mb-2 ps-2">' . $content . '</li>';
            },
            $text
        );

        // Agrupar <li> contiguos en <ul class="ai-param-list">
        $text = preg_replace('/((?:<li class="ai-list-item[^>]*>.*?<\/li>\s*)+)/su', '<ul class="ai-param-list list-unstyled ps-0 mb-3">$1</ul>', $text);

        // 9. Badges de estado clínico para valores después de ':'
        $text = preg_replace_callback(
            '/:\s*(ELEVAD[AO](?:\s*\([^\)]+\))?|ANORMAL|ALTERAD[AO]|NORMAL|OPTIM[AO])/iu',
            function ($matches) {
                $val = mb_strtoupper(trim($matches[1]));
                if (str_contains($val, 'ELEVAD') || str_contains($val, 'ANORMAL') || str_contains($val, 'ALTERAD')) {
                    return ': <span class="badge bg-danger text-white px-2 py-1 shadow-sm"><i class="fas fa-arrow-trend-up me-1"></i>' . $matches[1] . '</span>';
                } else {
                    return ': <span class="badge bg-success text-white px-2 py-1 shadow-sm"><i class="fas fa-check me-1"></i>' . $matches[1] . '</span>';
                }
            },
            $text
        );

        // Badges para categorías de riesgo en mayúsculas
        $text = preg_replace_callback(
            '/\b(ALTO RIESGO METABÓLICO Y CARDIOVASCULAR|ALTO RIESGO|MUY ALTO RIESGO|RIESGO MUY ALTO|RIESGO ALTO|RIESGO MODERADO|MODERADO RIESGO|RIESGO BAJO|BAJO RIESGO|RIESGO MUY BAJO)\b/u',
            function ($matches) {
                $val = mb_strtoupper(trim($matches[1]));
                if (str_contains($val, 'ALTO')) {
                    return '<span class="badge bg-danger text-white px-2 py-1 shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i>' . $matches[1] . '</span>';
                } elseif (str_contains($val, 'MODERADO')) {
                    return '<span class="badge bg-warning text-dark px-2 py-1 shadow-sm"><i class="fas fa-exclamation me-1"></i>' . $matches[1] . '</span>';
                } else {
                    return '<span class="badge bg-success text-white px-2 py-1 shadow-sm"><i class="fas fa-check-circle me-1"></i>' . $matches[1] . '</span>';
                }
            },
            $text
        );

        // 10. Limpieza de saltos de línea y párrafos
        $text = nl2br($text);
        $text = preg_replace('/<br\s*\/?>\s*(<\/?(?:ul|li|h[1-6]|hr|div|p))/iu', '$1', $text);
        $text = preg_replace('/(<\/(?:ul|li|h[1-6]|hr|div|p)>)\s*<br\s*\/?>/iu', '$1', $text);
        $text = preg_replace('/(<div[^>]*>)\s*<br\s*\/?>/iu', '$1', $text);
        $text = preg_replace('/<br\s*\/?>\s*(<\/div>)/iu', '$1', $text);
        $text = preg_replace('/(<br\s*\/?>){2,}/iu', '<br>', $text);

        return nl2br(htmlspecialchars(strip_tags(trim($text)), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
    }

    public function export(Request $request)
    {
        $query = Prediccion::with(['cita.paciente.usuario', 'cita.doctor.usuario']);
        return Excel::store($query, 'predicciones.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function sendEmail(Request $request, $idprediccion)
    {
        $prediccion = Prediccion::with(['cita.paciente', 'cita.doctor', 'cita.enfermera'])->findOrFail($idprediccion);
        $this->authorizeAppointment($prediccion->cita);
        $patientEmail = $prediccion->cita->paciente?->usuario?->email;
        abort_unless($patientEmail, 422, 'El paciente no tiene un correo registrado.');
        $request->validate([
            'email' => ['required', 'email', \Illuminate\Validation\Rule::in([$patientEmail])],
        ]);

        $pdf = DomPDF::loadView('predicciones.pdf', compact('prediccion'));

        try {
            Mail::send('emails.reporte', compact('prediccion'), function ($message) use ($request, $pdf) {
                $message->to($request->email)
                        ->subject('Reporte de Predicción Médica')
                        ->attachData($pdf->output(), 'reporte_prediccion.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });

            return redirect()->back()->with('success', 'Reporte enviado por correo electrónico exitosamente.');
        } catch (\Exception $e) {
            Log::warning('No se pudo enviar el reporte por correo.', ['exception' => get_class($e)]);
            return redirect()->back()->with('error', 'No se pudo enviar el reporte por correo.');
        }
    }
}
