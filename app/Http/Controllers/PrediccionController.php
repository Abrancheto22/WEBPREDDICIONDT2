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
            $prediccion = new Prediccion();
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
        ]);

        try {
            $prediccion = Prediccion::findOrFail($idprediccion);

            // Obtener el tiempo de inicio de la sesión
            $startTime = Session::pull('prediccion_start_time');

            // Capturar el tiempo de parada para la actualización
            $stopTime = now();

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
            if ($startTime) {
                $prediccion->timer_inicio = $startTime;
                $prediccion->timer_parada = $stopTime;
            } else {
                Log::warning('No se pudo encontrar el tiempo de inicio en la sesión para la edición de la predicción ' . $idprediccion);
            }

            $prediccion->save();

            return redirect()->route('predicciones.index')->with('success', 'Predicción actualizada exitosamente con nuevos resultados de ML.');

        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->with('error', 'Error de validación: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error al guardar la predicción editada y confirmada: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al guardar los cambios en la predicción. Detalles: ' . $e->getMessage());
        }
    }
    
    public function destroy($idprediccion)
    {
        $prediccion = Prediccion::findOrFail($idprediccion);
        $prediccion->delete();

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción eliminada exitosamente');
    }
}