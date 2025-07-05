<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prediccion;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrediccionController
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
        $validated = $request->validate([
            'idcita' => 'required|exists:cita,idcita',
            'glucosa' => 'required|numeric|min:0',
            'presion_sanguinea' => 'required|numeric|min:0',
            'grosor_piel' => 'required|numeric|min:0',
            'embarazos' => 'required|numeric|min:0',
            'BMI' => 'required|numeric|min:0',
            'pedigree' => 'required|numeric|min:0',
            'edad' => 'required|numeric|min:0',
            'insulina' => 'required|numeric|min:0',
            'observacion' => 'nullable|string',
            'resultado' => 'required|numeric'
        ]);

        $cita = Cita::findOrFail($validated['idcita']);
        
        // Crear la predicción
        $prediccion = new Prediccion([
            'glucosa' => $validated['glucosa'],
            'presion_sanguinea' => $validated['presion_sanguinea'],
            'grosor_piel' => $validated['grosor_piel'],
            'embarazos' => $validated['embarazos'],
            'BMI' => $validated['BMI'],
            'pedigree' => $validated['pedigree'],
            'edad' => $validated['edad'],
            'insulina' => $validated['insulina'],
            'observacion' => $validated['observacion'],
            'resultado' => $validated['resultado'],
            'idcita' => $cita->idcita
        ]);

        $prediccion->save();

        // Actualizar el estado de la cita a Completada
        $cita->update(['estado' => 'Completada']);

        return redirect()->route('predicciones.index')
            ->with('success', 'Predicción creada exitosamente');
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
            'resultado' => 'required|numeric'
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
