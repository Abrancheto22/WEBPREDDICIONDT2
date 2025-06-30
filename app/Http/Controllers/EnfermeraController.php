<?php

namespace App\Http\Controllers;

use App\Models\Enfermera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EnfermeraController
{
    public function index()
    {
        $enfermeras = Enfermera::with('usuario')
            ->get()
            ->map(function ($enfermera) {
                $enfermera->imagen_url = $enfermera->imagen_url;
                return $enfermera;
            });
        
        return view('enfermeras.index', compact('enfermeras'));
    }

    public function create()
    {
        // Filtrar usuarios que tienen el rol enfermera (idrol = 3)
        $usuarios = User::where('idrol', 3)->get();
        
        if (request()->routeIs('enfermeras.create')) {
            return view('enfermeras.create', compact('usuarios'));
        }
        
        return abort(404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'DNI' => 'required|string|max:20|unique:efermera',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:efermera,iduser',
        ]);

        $enfermera = new Enfermera();
        $enfermera->DNI = $request->DNI;
        $enfermera->nombre = $request->nombre;
        $enfermera->apellido = $request->apellido;
        $enfermera->numero = $request->numero;
        $enfermera->iduser = $request->iduser;

        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'enfermera_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/enfermeras/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $enfermera->imagen = 'images/enfermeras/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                $path = $file->move(public_path('images/enfermeras'), $nombreImagen);
                $enfermera->imagen = 'images/enfermeras/' . $nombreImagen;
            }
        }

        $enfermera->save();

        return redirect()->route('enfermeras.index')->with('success', 'Enfermera creada exitosamente');
    }

    public function edit($idenfermera)
    {
        $enfermera = Enfermera::findOrFail($idenfermera);
        
        // Filtrar usuarios que tienen el rol enfermera
        // Incluimos el usuario actual de la enfermera
        $usuarios = User::where('idrol', 3)
            ->orWhere('id', $enfermera->iduser)
            ->get();
        
        return view('enfermeras.edit', compact('enfermera', 'usuarios'));
    }

    public function update(Request $request, $idenfermera)
    {
        $enfermera = Enfermera::findOrFail($idenfermera);

        $request->validate([
            'DNI' => 'required|string|max:20|unique:efermera,DNI,' . $idenfermera . ',idenfermera',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:efermera,iduser,' . $idenfermera . ',idenfermera',
        ]);

        $enfermera->DNI = $request->DNI;
        $enfermera->nombre = $request->nombre;
        $enfermera->apellido = $request->apellido;
        $enfermera->numero = $request->numero;
        $enfermera->iduser = $request->iduser;

        if ($request->hasFile('imagen')) {
            // Obtener el archivo
            $file = $request->file('imagen');
            
            // Generar un nombre para la imagen
            $nombreImagen = 'enfermera_' . $file->getClientOriginalName();
            $rutaImagen = public_path('images/enfermeras/' . $nombreImagen);
            
            // Verificar si la imagen ya existe
            if (file_exists($rutaImagen)) {
                // Si existe, usar la ruta existente
                $enfermera->imagen = 'images/enfermeras/' . $nombreImagen;
            } else {
                // Si no existe, mover la imagen
                $path = $file->move(public_path('images/enfermeras'), $nombreImagen);
                $enfermera->imagen = 'images/enfermeras/' . $nombreImagen;
            }
        }

        $enfermera->save();

        return redirect()->route('enfermeras.index')->with('success', 'Enfermera actualizada exitosamente');
    }

    public function destroy($idenfermera)
    {
        $enfermera = Enfermera::findOrFail($idenfermera);
        
        // Eliminar la imagen si existe
        if ($enfermera->imagen && Storage::disk('public')->exists($enfermera->imagen)) {
            Storage::disk('public')->delete($enfermera->imagen);
        }

        $enfermera->delete();

        return redirect()->route('enfermeras.index')->with('success', 'Enfermera eliminada exitosamente');
    }

    public function show($idenfermera)
    {
        $enfermera = Enfermera::with('usuario')->findOrFail($idenfermera);
        return view('enfermeras.show', compact('enfermera'));
    }
}
