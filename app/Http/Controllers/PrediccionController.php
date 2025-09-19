<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prediccion;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // DEBE ESTAR
use Illuminate\Support\Facades\Log;  // DEBE ESTAR

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
        return view('predicciones.create', compact('cita', 'citas'))
            ->with('paciente', $cita ? $cita->paciente : null)
            ->with('triaje', $cita ? $cita->triaje : null);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
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

            $mlApiUrl = 'http://127.0.0.1:5000/predict'; // Asegúrate de que esta URL sea correcta

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

            $response->throw(); // Lanza una excepción si la respuesta tiene un error de cliente o servidor

            $predictionResult = $response->json();

            // Devolver la respuesta como JSON
            return response()->json([
                'success' => true,
                'message' => 'Predicción obtenida con éxito',
                'predictionResult' => $predictionResult,
            ]);

        } catch (ValidationException $e) {
            // Si hay errores de validación de Laravel, devolverlos como JSON 422
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Error al conectar con la API de ML
            Log::error('Error al conectar con la API de ML: ' . $e->getMessage(), ['url' => $mlApiUrl, 'response' => $e->response ? $e->response->body() : 'N/A']);
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener la predicción del modelo de ML. Por favor, inténtelo de nuevo más tarde.',
                'details' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            // Otros errores inesperados
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
            $prediccion->resultado = $validated['probability_diabetes']; // Guardar la probabilidad
            // Convertir tiempo de formato MM:SS:ms a segundos con decimales
            $timeParts = explode(':', $validated['timer']);
            $minutes = (int)$timeParts[0];
            $seconds = (int)$timeParts[1];
            $milliseconds = (int)$timeParts[2];
            $totalSeconds = ($minutes * 60) + $seconds + ($milliseconds / 100);
            $prediccion->timer = $totalSeconds; // Guardar el tiempo en segundos con decimales
            $prediccion->save();

            // Actualizar el estado de la cita
            $cita = Cita::find($validated['idcita']);
            if ($cita) {
                $cita->estado = 'Realizado';
                $cita->save();
            }

            return redirect()->route('citas_doctores.index')->with('success', 'Predicción guardada exitosamente y cita actualizada.');

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
        // Asegúrate de cargar las relaciones necesarias para la vista
        $prediccion = Prediccion::with(['cita.paciente', 'cita.triaje'])->findOrFail($idprediccion);
        return view('predicciones.edit', compact('prediccion'));
    }
    
    public function processEditedPrediction(Request $request, $idprediccion)
    {
        try {
            // Validar los datos de entrada (los mismos que en store)
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

            $mlApiUrl = 'http://127.0.0.1:5000/predict'; // Asegúrate de que esta URL sea correcta

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

            $response->throw(); // Lanza una excepción si la respuesta tiene un error de cliente o servidor

            $predictionResult = $response->json();

            // Devolver la respuesta como JSON
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

    /**
     * Guarda la predicción editada y confirmada en la base de datos.
     * Este método reemplaza la lógica original del método 'update'.
     */
    public function updateConfirmedPrediction(Request $request, $idprediccion)
    {
        // Validar todos los datos, incluyendo los resultados de la ML
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
            
            // Convertir tiempo de formato MM:SS:ms a segundos con decimales
            $timeParts = explode(':', $validated['timer']);
            $minutes = (int)$timeParts[0];
            $seconds = (int)$timeParts[1];
            $milliseconds = (int)$timeParts[2];
            $totalSeconds = ($minutes * 60) + $seconds + ($milliseconds / 100);
            $prediccion->timer = $totalSeconds; // Guardar el tiempo en segundos con decimales
            $prediccion->resultado = $validated['probability_diabetes'];
            $prediccion->save();

            return redirect()->route('predicciones.index')->with('success', 'Predicción actualizada exitosamente con nuevos resultados de ML.');

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