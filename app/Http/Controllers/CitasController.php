<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Enfermera;

class CitasController
{
    public function index()
    {
        $citas = Cita::with(['paciente', 'doctor', 'enfermera'])
            ->get()
            ->map(function ($cita) {
                return $cita
                    ->setAttribute('paciente_nombre', $cita->paciente ? $cita->paciente->nombre : 'N/A')
                    ->setAttribute('paciente_apellido', $cita->paciente ? $cita->paciente->apellido : 'N/A')
                    ->setAttribute('doctor_nombre', $cita->doctor ? $cita->doctor->nombre : 'N/A')
                    ->setAttribute('doctor_apellido', $cita->doctor ? $cita->doctor->apellido : 'N/A')
                    ->setAttribute('enfermera_nombre', $cita->enfermera ? $cita->enfermera->nombre : 'N/A')
                    ->setAttribute('enfermera_apellido', $cita->enfermera ? $cita->enfermera->apellido : 'N/A');
            });
        
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        $pacientes = Paciente::all();
        $doctores = Doctor::all();
        $enfermeras = Enfermera::all();
        
        // Obtener el ID de la enfermera si el usuario es enfermera
        $enfermera_id = null;
        $user = auth()->user();
        
        // Verificar si el usuario tiene rol de enfermera
        if ($user->rol && $user->rol->nombre === 'enfermera') {
            $enfermera_id = $user->enfermera ? $user->enfermera->idenfermera : null;
        }

        return view('citas.create', compact('pacientes', 'doctores', 'enfermeras', 'enfermera_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paciente' => 'required|exists:paciente,idpaciente',
            'doctor' => 'required|exists:doctor,iddoctor',
            'enfermera' => 'required|exists:efermera,idenfermera',
            'fecha_cita' => 'required|date|after:today',
            'hora_cita' => 'required',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:Pendiente,Cancelada,Completada'
        ]);

        Cita::create([
            'idpaciente' => $request->paciente,
            'iddoctor' => $request->doctor,
            'idenfermera' => $request->enfermera,
            'fecha_cita' => $request->fecha_cita,
            'hora_cita' => $request->hora_cita,
            'motivo' => $request->motivo,
            'estado' => $request->estado
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita creada exitosamente');
    }

    public function show($idcita)
    {
        $cita = Cita::with(['paciente', 'doctor', 'enfermera'])
            ->findOrFail($idcita);
        
        return view('citas.show', compact('cita'));
    }

    public function edit($idcita)
    {
        $cita = Cita::with(['paciente', 'doctor', 'enfermera'])
            ->findOrFail($idcita);
        
        // Formatear la hora para el input de tiempo
        $cita->hora_cita = \Carbon\Carbon::parse($cita->hora_cita)->format('H:i');
        
        $pacientes = Paciente::all();
        $doctores = Doctor::all();
        $enfermeras = Enfermera::all();
        return view('citas.edit', compact('cita', 'pacientes', 'doctores', 'enfermeras'));
    }

    public function update(Request $request, $idcita)
    {
        $cita = Cita::findOrFail($idcita);
        $request->validate([
            'paciente' => 'required|exists:paciente,idpaciente',
            'doctor' => 'required|exists:doctor,iddoctor',
            'enfermera' => 'required|exists:efermera,idenfermera',
            'fecha_cita' => 'required|date|after:today',
            'hora_cita' => 'required',
            'motivo' => 'required|string|max:255',
            'estado' => 'required|in:Pendiente,Cancelada,Completada'
        ]);

        $cita->update([
            'idpaciente' => $request->paciente,
            'iddoctor' => $request->doctor,
            'idenfermera' => $request->enfermera,
            'fecha_cita' => $request->fecha_cita,
            'hora_cita' => $request->hora_cita,
            'motivo' => $request->motivo,
            'estado' => $request->estado
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada exitosamente');
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();
        return redirect()->route('citas.index')
            ->with('success', 'Cita eliminada exitosamente');
    }
}
