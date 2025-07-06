<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::with('usuario')
            ->get()
            ->map(function ($paciente) {
                if ($paciente->imagen && Storage::disk('public')->exists($paciente->imagen)) {
                    $paciente->imagen_url = Storage::disk('public')->url($paciente->imagen);
                } else {
                    $paciente->imagen_url = null;
                }
                return $paciente;
            });
        
        return view('pacientes.index', compact('pacientes'));
    }

    public function create()
    {
        // Filtrar usuarios que tienen el rol paciente (idrol = 4)
        $usuarios = User::where('idrol', 4)->get();
        return view('pacientes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'DNI' => 'required|string|max:20|unique:paciente',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:paciente,iduser',
        ]);

        $paciente = new Paciente();
        $paciente->DNI = $request->DNI;
        $paciente->nombre = $request->nombre;
        $paciente->apellido = $request->apellido;
        $paciente->sexo = $request->sexo;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->direccion = $request->direccion;
        $paciente->telefono = $request->telefono;
        $paciente->iduser = $request->iduser;

        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'paciente_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/pacientes/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $paciente->imagen = 'images/pacientes/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                $path = $file->move(public_path('images/pacientes'), $nombreImagen);
                $paciente->imagen = 'images/pacientes/' . $nombreImagen;
            }
        }

        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente');
    }

    public function show($idpaciente)
    {
        $paciente = Paciente::with('usuario')->findOrFail($idpaciente);
        return view('pacientes.show', compact('paciente'));
    }

    public function edit($idpaciente)
    {
        $paciente = Paciente::findOrFail($idpaciente);
        
        // Filtrar usuarios que tienen el rol paciente (idrol = 4)
        // Incluimos el usuario actual del paciente
        $usuarios = User::where('idrol', 4)
            ->orWhere('id', $paciente->iduser)
            ->get();
        
        return view('pacientes.edit', compact('paciente', 'usuarios'));
    }

    public function update(Request $request, $idpaciente)
    {
        $paciente = Paciente::findOrFail($idpaciente);

        $request->validate([
            'DNI' => 'required|string|max:20|unique:paciente,DNI,' . $idpaciente . ',idpaciente',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:paciente,iduser,' . $idpaciente . ',idpaciente',
        ]);

        $paciente->DNI = $request->DNI;
        $paciente->nombre = $request->nombre;
        $paciente->apellido = $request->apellido;
        $paciente->sexo = $request->sexo;
        $paciente->fecha_nacimiento = $request->fecha_nacimiento;
        $paciente->direccion = $request->direccion;
        $paciente->telefono = $request->telefono;
        $paciente->iduser = $request->iduser;

        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'paciente_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/pacientes/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $paciente->imagen = 'images/pacientes/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                $path = $file->move(public_path('images/pacientes'), $nombreImagen);
                $paciente->imagen = 'images/pacientes/' . $nombreImagen;
            }
        }

        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy($idpaciente)
    {
        $paciente = Paciente::findOrFail($idpaciente);
        
        // Eliminar la imagen si existe
        if ($paciente->imagen && Storage::disk('public')->exists($paciente->imagen)) {
            Storage::disk('public')->delete($paciente->imagen);
        }
        
        $paciente->delete();
        
        return redirect()->route('pacientes.index')->with('success', 'Paciente eliminado exitosamente');
    }
}
