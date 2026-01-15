<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Enfermera;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CitasController extends Controller
{
    public function index(Request $request)
    {
        $query = Cita::with(['paciente', 'doctor', 'enfermera']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->whereHas('paciente', function($q) use ($search) {
                $q->where('nombre', 'ilike', "%{$search}%")
                  ->orWhere('apellido', 'ilike', "%{$search}%")
                  ->orWhere('DNI', 'ilike', "%{$search}%");
            });
        }

        // Optimización: Paginación y ordenamiento por fecha descendente
        $citas = $query->orderBy('fecha_cita', 'desc')
            ->orderBy('hora_cita', 'desc')
            ->paginate(10);
        
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        // Se revierte la optimización temporalmente para descartar errores de nombres de columna
        $pacientes = Paciente::orderBy('apellido')->get();
        $doctores = Doctor::orderBy('apellido')->get();
        $enfermeras = Enfermera::orderBy('apellido')->get();
        
        // Obtener el ID de la enfermera si el usuario es enfermera
        $enfermera_id = null;
        $user = Auth::user();
        
        // Verificar si el usuario tiene rol de enfermera (validando que user no sea null)
        if ($user && $user->rol && $user->rol->nombre === 'enfermera') {
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

    public function destroy($idcita)
    {
        $cita = Cita::find($idcita);
        if (!$cita) {
            return redirect()->back()->with('error', 'Cita no encontrada');
        }

        try {
            DB::beginTransaction();
            
            $cita->delete();
            
            DB::commit();
            
            return redirect()->route('citas.index')
                ->with('success', 'Cita eliminada exitosamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar la cita');
        }
    }

    public function index_doctores(Request $request)
{
    // Start with the base query for the citas.
    $citasQuery = Cita::with(['paciente', 'doctor', 'enfermera', 'triaje']);

    // Check if the 'estado' filter is present in the request and not empty.
    if ($request->has('estado') && !empty($request->estado)) {
        // Apply the where clause to filter by the specified estado.
        $citasQuery->where('estado', $request->estado);
    }

    // Now, get the filtered citas and apply the mapping logic.
    $citas = $citasQuery->get()->map(function ($cita) {
        return $cita
            ->setAttribute('paciente_nombre', $cita->paciente ? $cita->paciente->nombre : 'N/A')
            ->setAttribute('paciente_apellido', $cita->paciente ? $cita->paciente->apellido : 'N/A')
            ->setAttribute('doctor_nombre', $cita->doctor ? $cita->doctor->nombre : 'N/A')
            ->setAttribute('doctor_apellido', $cita->doctor ? $cita->doctor->apellido : 'N/A')
            ->setAttribute('enfermera_nombre', $cita->enfermera ? $cita->enfermera->nombre : 'N/A')
            ->setAttribute('enfermera_apellido', $cita->enfermera ? $cita->enfermera->apellido : 'N/A')
            ->setAttribute('tiene_triaje', $cita->triaje ? true : false)
            ->setAttribute('idtriaje', $cita->triaje ? $cita->triaje->idtriaje : null);
    });

    // Pass the filtered data to the view, along with the selected estado to maintain the filter state in the dropdown.
    $selectedEstado = $request->estado;
    return view('citas_doctores.index', compact('citas', 'selectedEstado'));
}
}
