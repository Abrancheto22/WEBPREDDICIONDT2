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
                'embarazos' => 'required|numeric|min:0',
                'glucosa' => 'required|numeric|min:0',
                'presion_sanguinea' => 'required|numeric|min:0',
                'grosor_piel' => 'required|numeric|min:0',
                'insulina' => 'required|numeric|min:0',
                'BMI' => 'required|numeric|min:0',
                'pedigree' => 'required|numeric|min:0',
                'edad' => 'required|numeric|min:0',
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
                // Puedes devolver también los datos originales si los necesitas en JS
                // 'inputData' => $validated
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

    // El método saveConfirmedPrediction es el mismo que te di en la respuesta anterior.
    // Solo asegúrate de que el request->validate para saveConfirmedPrediction
    // tenga los campos 'probability_diabetes', 'prediction_label', y 'diagnosis'
    // ya que vienen de los hidden inputs del segundo formulario.
    public function saveConfirmedPrediction(Request $request)
    {
        $validated = $request->validate([
            'idcita' => 'required|exists:cita,idcita',
            'embarazos' => 'required|numeric|min:0',
            'glucosa' => 'required|numeric|min:0',
            'presion_sanguinea' => 'required|numeric|min:0',
            'grosor_piel' => 'required|numeric|min:0',
            'insulina' => 'required|numeric|min:0',
            'BMI' => 'required|numeric|min:0',
            'pedigree' => 'required|numeric|min:0',
            'edad' => 'required|numeric|min:0',
            'observacion' => 'nullable|string',
            'probability_diabetes' => 'required|numeric|min:0|max:1',
            'prediction_label' => 'required|in:0,1', // 0 o 1
            'diagnosis' => 'required|string',
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
            $prediccion->prediction_label = $validated['prediction_label'];
            $prediccion->diagnosis = $validated['diagnosis'];

            $prediccion->save();

            // Actualizar el estado de la cita
            $cita = Cita::find($validated['idcita']);
            if ($cita) {
                $cita->estado = 'realizado'; // O el estado que corresponda
                $cita->save();
            }

            return redirect()->route('predicciones.index')->with('success', 'Predicción guardada exitosamente y cita actualizada.');

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
        $prediccion = Prediccion::with('cita')->findOrFail($idprediccion);
        return view('predicciones.edit', compact('prediccion'));
    }

    public function update(Request $request, $idprediccion)
    {
        $validated = $request->validate([
            'glucosa' => 'required|numeric|min:0',
            'presion_sanguinea' => 'required|numeric|min:0',
            'grosor_piel' => 'required|numeric|min:0',
            'embarazos' => 'required|numeric|min:0',
            'BMI' => 'required|numeric|min:0',
            'pedigree' => 'required|numeric|min:0',
            'edad' => 'required|numeric|min:0',
            'insulina' => 'required|numeric|min:0',
            'observacion' => 'nullable|string',
            'resultado' => 'required|numeric' // Este campo aún se valida si lo actualizas manualmente
        ]);

        $prediccion = Prediccion::findOrFail($idprediccion);
        $prediccion->update($validated);

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción actualizada exitosamente');
    }

    public function destroy($idprediccion)
    {
        $prediccion = Prediccion::findOrFail($idprediccion);
        $prediccion->delete();

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción eliminada exitosamente');
    }
}