<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DoctorController
{
    public function index()
    {
        $doctores = Doctor::with('usuario')
            ->get()
            ->map(function ($doctor) {
                if ($doctor->imagen && Storage::disk('public')->exists($doctor->imagen)) {
                    $doctor->imagen_url = Storage::disk('public')->url($doctor->imagen);
                } else {
                    $doctor->imagen_url = null;
                }
                return $doctor;
            });
        
        return view('doctores.index', compact('doctores'));
    }

    public function create()
    {
        // Filtrar usuarios que tienen el rol doctor (idrol = 2)
        $usuarios = User::where('idrol', 2)->get();
        return view('doctores.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'DNI' => 'required|string|max:20|unique:doctor',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'especialidad' => 'required|string|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:doctor,iduser',
        ]);

        $doctor = new Doctor();
        $doctor->DNI = $request->DNI;
        $doctor->nombre = $request->nombre;
        $doctor->apellido = $request->apellido;
        $doctor->numero = $request->numero;
        $doctor->especialidad = $request->especialidad;
        $doctor->iduser = $request->iduser;

        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'doctor_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/doctores/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $doctor->imagen = 'images/doctores/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                $path = $file->move(public_path('images/doctores'), $nombreImagen);
                $doctor->imagen = 'images/doctores/' . $nombreImagen;
            }
        }

        $doctor->save();

        return redirect()->route('doctores.index')->with('success', 'Doctor creado exitosamente');
    }

    public function edit($iddoctor)
    {
        $doctor = Doctor::findOrFail($iddoctor);
        
        // Filtrar usuarios que tienen el rol doctor (idrol = 2)
        // Incluimos el usuario actual del doctor
        $usuarios = User::where('idrol', 2)
            ->orWhere('id', $doctor->iduser)
            ->get();
        
        return view('doctores.edit', compact('doctor', 'usuarios'));
    }

    public function show($iddoctor)
    {
        $doctor = Doctor::with('usuario')->findOrFail($iddoctor);
        return view('doctores.show', compact('doctor'));
    }

    public function update(Request $request, $iddoctor)
    {
        $doctor = Doctor::findOrFail($iddoctor);
        
        $request->validate([
            'DNI' => 'required|string|max:20|unique:doctor,DNI,' . $iddoctor . ',iddoctor',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'especialidad' => 'required|string|max:100',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:doctor,iduser,' . $iddoctor . ',iddoctor',
        ]);

        $doctor->DNI = $request->DNI;
        $doctor->nombre = $request->nombre;
        $doctor->apellido = $request->apellido;
        $doctor->numero = $request->numero;
        $doctor->especialidad = $request->especialidad;
        $doctor->iduser = $request->iduser;

        // Manejo de la imagen
        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'doctor_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/doctores/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $doctor->imagen = 'images/doctores/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                // Primero eliminar la imagen anterior si existe
                if ($doctor->imagen && Storage::disk('public')->exists($doctor->imagen)) {
                    Storage::disk('public')->delete($doctor->imagen);
                }
                
                $path = $file->move(public_path('images/doctores'), $nombreImagen);
                $doctor->imagen = 'images/doctores/' . $nombreImagen;
            }
        }

        $doctor->save();

        return redirect()->route('doctores.index')->with('success', 'Doctor actualizado exitosamente');
    }

    public function destroy($iddoctor)
    {
        $doctor = Doctor::findOrFail($iddoctor);
        if ($doctor->imagen) {
            Storage::disk('public')->delete($doctor->imagen);
        }
        $doctor->delete();
        return redirect()->route('doctores.index')->with('success', 'Doctor eliminado exitosamente');
    }
}
