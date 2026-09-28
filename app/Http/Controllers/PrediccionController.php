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
        $predicciones = Prediccion::with(['cita.paciente'])->get();
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
                'documentos_adjuntos' => 'nullable|array',
                'documentos_adjuntos.*' => 'file|mimes:pdf,docx,jpg,png|max:10240', // 10MB por archivo
            ]);

            // Manejo de archivos adjuntos
            $attachmentPaths = [];
            $attachmentNames = [];
            if ($request->hasFile('documentos_adjuntos')) {
                foreach ($request->file('documentos_adjuntos') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('attachments', $fileName, 'public'); // Guardar en storage/app/public/attachments
                    $attachmentPaths[] = $filePath;
                    $attachmentNames[] = $file->getClientOriginalName();
                }
                Session::put('uploaded_attachment_paths', $attachmentPaths);
                Session::put('uploaded_attachment_names', $attachmentNames);
            }

            // **1. Capturar el tiempo de inicio y guardarlo en la sesión**
            $startTime = now();
            Session::put('prediccion_start_time', $startTime);

            // Usa tu dominio de Vercel y el endpoint /predict
            $mlApiUrl = 'https://appml-tesis.vercel.app/predict';

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
        set_time_limit(120);
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
                'documentos_adjuntos' => 'nullable|array',
                'documentos_adjuntos.*' => 'file|mimes:pdf,docx,jpg,png|max:10240', // 10MB por archivo
            ]);

            // Manejo de archivos adjuntos
            $attachmentPaths = [];
            $attachmentNames = [];
            if ($request->hasFile('documentos_adjuntos')) {
                foreach ($request->file('documentos_adjuntos') as $file) {
                    $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('attachments', $fileName, 'public');
                    $attachmentPaths[] = $filePath;
                    $attachmentNames[] = $file->getClientOriginalName();
                }
                
                // Guardar en sesión para usar en el análisis
                Session::put('uploaded_attachment_paths', $attachmentPaths);
                Session::put('uploaded_attachment_names', $attachmentNames);
                
                Log::info('Archivos adjuntos procesados para análisis IA: ' . json_encode($attachmentPaths));
            }

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

            // Recuperar los archivos adjuntos de la sesión
            $attachmentPaths = Session::get('uploaded_attachment_paths', []);
            Log::info('Attachment Paths retrieved from session: ' . json_encode($attachmentPaths));

            // Construir el prompt para Gemini incluyendo el resultado si está disponible y los archivos adjuntos
            $prompt = $this->buildGeminiPrompt($validated, $paciente, $embarazos, $resultadoPrediccion, $attachmentPaths);
            Log::info('Gemini Prompt built: ' . $prompt);

            // Preparar el contenido para la API de Gemini
            $parts = [['text' => $prompt]];

            foreach ($attachmentPaths as $path) {
                $fullPath = storage_path('app/public/attachments/' . basename($path));
                Log::info('Processing attachment: ' . $fullPath);
                if (file_exists($fullPath)) {
                    $mimeType = mime_content_type($fullPath);
                    
                    // Procesar PDF e Imágenes como inline_data
                    if ($mimeType === 'application/pdf' || str_starts_with($mimeType, 'image/')) {
                        $fileContent = file_get_contents($fullPath);
                        if ($fileContent === false) {
                            Log::error('Failed to read file content for: ' . $fullPath);
                            continue;
                        }
                        $base64Content = base64_encode($fileContent);
                        
                        Log::info('File exists: ' . $fullPath . ', MIME Type: ' . $mimeType . ', Base64 content length: ' . strlen($base64Content));

                        $parts[] = [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $base64Content
                            ]
                        ];
                    } 
                    // Procesar DOCX extrayendo texto
                    elseif ($mimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                        $extractedText = $this->getTextFromDocx($fullPath);
                        if (!empty($extractedText)) {
                            Log::info('Extracted text from DOCX: ' . $fullPath);
                            $parts[] = [
                                'text' => "\n\n--- Contenido extraído del documento adjunto (" . basename($path) . ") ---\n" . $extractedText
                            ];
                        } else {
                            Log::warning('No text extracted from DOCX or empty: ' . $fullPath);
                        }
                    }
                    else {
                        Log::warning('Archivo adjunto con tipo MIME no soportado directamente: ' . $mimeType . ' - ' . $fullPath);
                    }
                } else {
                    Log::warning('Archivo adjunto no encontrado: ' . $fullPath);
                }
            }
            Log::info('Parts array before sending to Gemini: ' . json_encode($parts));

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
                Log::error('INTENTO DE LLAMADA A GEMINI SIN API KEY');
                throw new \Exception('La API Key de Gemini está vacía. Configure GEMINI_API_KEY en el .env y ejecute "php artisan config:cache"');
            }

            // Construcción de la URL con la Key
            $urlWithKey = $geminiUrl;
            // Si la URL no tiene ?key=, se lo agregamos.
            if (strpos($urlWithKey, 'key=') === false) {
                $separator = strpos($urlWithKey, '?') === false ? '?' : '&';
                $urlWithKey .= $separator . 'key=' . $geminiApiKey;
            }

            // Log de depuración (Ocultando parte de la key por seguridad)
            $maskedKey = substr($geminiApiKey, 0, 4) . '...' . substr($geminiApiKey, -4);
            Log::info("Intentando conectar a Gemini. URL Base: {$geminiUrl} | Key detectada: {$maskedKey}");

            // Validación final de URL antes de enviar
            if (!filter_var($urlWithKey, FILTER_VALIDATE_URL)) {
                Log::error('URL de Gemini malformada detectada: ' . $urlWithKey);
                throw new \Exception('La URL de la API de Gemini no tiene un formato válido. Verifique el archivo .env en el servidor.');
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
                Log::error('Error al conectar con Gemini API: ' . $response->status() . ' - ' . $response->body());
                Log::error('Gemini API Request Payload: ' . json_encode(['contents' => [['parts' => $parts]]]));
                throw new \Exception('Error al conectar con Gemini API: ' . $response->body());
            }

            $geminiResult = $response->json();
            Log::info('Gemini API Raw Response: ' . json_encode($geminiResult));
            // Check if 'candidates' key exists and is not empty
            if (!isset($geminiResult['candidates']) || empty($geminiResult['candidates'])) {
                Log::error('Gemini API did not return any candidates: ' . json_encode($geminiResult));
                throw new \Exception('Gemini API did not return any candidates. Full response: ' . json_encode($geminiResult));
            }
            $analysisText = $geminiResult['candidates'][0]['content']['parts'][0]['text'] ?? 'No se pudo obtener análisis';

            // Procesar el texto de análisis para mejorar el formato
            $analysisText = $this->formatAnalysisText($analysisText);

            // Buscar si ya existe una predicción para esta cita
            $prediccion = Prediccion::where('idcita', $validated['idcita'])->first();
            
            if ($prediccion) {
                // Si existe, actualizar el análisis de IA
                $prediccion->analisis_ia = $analysisText;
                $prediccion->resultado = 0; // Valor temporal hasta que se haga la predicción ML
                $prediccion->timer = 0; // Valor temporal
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
                $prediccion->observacion = $validated['observacion'];
                $prediccion->analisis_ia = $analysisText;
                $prediccion->resultado = 0; // Valor temporal hasta que se haga la predicción ML
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
        } catch (\Exception $e) {
            Log::error('Error en análisis con Gemini: ' . $e->getMessage() . ' Stack Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'error' => 'Error en el análisis con IA: ' . $e->getMessage()
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
            $observacionesInfo .
            "\n\n**REGLAS DE FORMATO:**\n" .
            "- NO uses símbolos ni fórmulas en formato LaTeX (NUNCA uses signos de dólar '$', ni '\\ge', '\\le', '\\text{}', '\\mu').\n" .
            "- Usa siempre símbolos médicos y numéricos limpios: '≥', '≤', '>', '<', 'µU/ml', 'mg/dL', 'mmHg', 'kg/m²'.\n" .
            "- Estructura tu respuesta directamente con los parámetros clínicos, interpretación, estratificación de riesgo y recomendaciones terapéuticas.\n" .
            "- NO incluyas bajo ninguna circunstancia bloques ni secciones de advertencia clínica sobre discrepancias con predicciones ni críticas de algoritmos.";

        if (!empty($attachmentPaths)) {
            $prompt .= "\n\n**DOCUMENTOS ADJUNTOS:**\nSe han proporcionado documentos adicionales para el análisis. Por favor, revisa estos documentos cuidadosamente y utiliza la información contenida en ellos para enriquecer tu análisis y tus recomendaciones.\n\n**CONTENIDO DE LOS ARCHIVOS ADJUNTOS (TEMPORAL):**\nPor favor, lista brevemente el contenido principal de cada archivo adjunto para confirmar su lectura.";
        }

        return $prompt;
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
            'attachment_paths' => 'nullable|array',
            'attachment_names' => 'nullable|array',
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

            // Guardar archivos adjuntos desde la sesión
            $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', []);
            $prediccion->attachment_names = Session::get('uploaded_attachment_names', []);

            $prediccion->save();

            // Limpiar los datos de archivos adjuntos de la sesión después de guardar
            Session::forget('uploaded_attachment_paths');
            Session::forget('uploaded_attachment_names');

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
                'attachments' => 'nullable|array',
                'attachments.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,gif|max:5120',
                'removed_attachments' => 'nullable|string',
            ]);

            // Manejar archivos adjuntos eliminados
            $prediccion = Prediccion::findOrFail($idprediccion);
            $currentPaths = $prediccion->attachment_paths ?? [];
            $currentNames = $prediccion->attachment_names ?? [];
            
            if ($request->has('removed_attachments') && !empty($request->removed_attachments)) {
                $removedIndices = json_decode($request->removed_attachments, true);
                if (is_array($removedIndices)) {
                    // Eliminar archivos físicos
                    foreach ($removedIndices as $index) {
                        if (isset($currentPaths[$index])) {
                            $filePath = storage_path('app/' . $currentPaths[$index]);
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
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
                    $path = $file->store('attachments', 'local');
                    
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

            $mlApiUrl = env('ML_API_URL', 'https://appml-tesis.vercel.app/predict');

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

            // Recuperar y guardar los archivos adjuntos de la sesión
            $prediccion->attachment_paths = Session::get('uploaded_attachment_paths', []);
            $prediccion->attachment_names = Session::get('uploaded_attachment_names', []);

            $prediccion->save();

            // Limpiar los datos de archivos adjuntos de la sesión
            Session::forget('uploaded_attachment_paths');
            Session::forget('uploaded_attachment_names');
            
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
    
    public function updateValidacion(Request $request, $idprediccion)
    {
        $validated = $request->validate([
            'validar_prediccion' => 'required|in:1,0',
        ]);

        $prediccion = Prediccion::findOrFail($idprediccion);
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
        $prediccion->delete();

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción eliminada exitosamente');
    }

    public function pdf($id)
    {
        $prediccion = Prediccion::with(['cita.paciente'])->findOrFail($id);
        $pdf = DomPDF::loadView('predicciones.pdf', compact('prediccion'));
        return $pdf->stream('reporte-prediccion-'.$id.'.pdf');
    }

    public function downloadAttachment($id, $index)
    {
        try {
            $prediccion = Prediccion::findOrFail($id);
            
            // Verificar que existan archivos adjuntos
            if (!$prediccion->attachment_paths || !isset($prediccion->attachment_paths[$index])) {
                return redirect()->back()->with('error', 'Archivo adjunto no encontrado.');
            }
            
            $attachmentPath = $prediccion->attachment_paths[$index];
            $attachmentName = $prediccion->attachment_names[$index] ?? basename($attachmentPath);
            
            // Construir la ruta completa del archivo
            $fullPath = storage_path('app/public/attachments/' . basename($attachmentPath));
            
            // Verificar que el archivo existe
            if (!file_exists($fullPath)) {
                return redirect()->back()->with('error', 'El archivo adjunto no existe en el servidor.');
            }
            
            // Descargar el archivo
            return response()->download($fullPath, $attachmentName);
            
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

        return $text;
    }

    public function export(Request $request)
    {
        $query = Prediccion::with(['cita.paciente.usuario', 'cita.doctor.usuario']);
        return Excel::store($query, 'predicciones.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    public function sendEmail(Request $request, $idprediccion)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $prediccion = Prediccion::with(['cita.paciente', 'cita.doctor', 'cita.enfermera'])->findOrFail($idprediccion);

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
            return redirect()->back()->with('error', 'Error al enviar el reporte: ' . $e->getMessage());
        }
    }
}
