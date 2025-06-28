<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RolController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Rol::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:rols,nombre'
        ]);

        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->save();

        return redirect()->route('roles.index')
            ->with('success', 'Rol creado exitosamente');
    }
    public function edit($idrol)
    {
        try {
            $rol = Rol::findOrFail($idrol);
            return view('roles.edit', compact('rol'));
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->with('error', 'No se encontró el rol especificado');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $idrol)
    {
        try {
            // Validar los datos del formulario
            $validated = $request->validate([
                'nombre' => [
                    'required', 
                    'string', 
                    'max:255',
                    Rule::unique('rols', 'nombre')->ignore($idrol, 'idrol')
                ]
            ]);

            // Buscar el rol
            $rol = Rol::findOrFail($idrol);
            
            // Actualizar el rol
            $rol->nombre = $validated['nombre'];
            $rol->save();

            return redirect()->route('roles.index')
                ->with('success', 'Rol actualizado exitosamente');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si hay errores de validación, redirigir al formulario de edición
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // Para otros errores
            return redirect()->back()
                ->with('error', 'Error al actualizar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idrol)
    {
        $rol = Rol::findOrFail($idrol);
        $rol->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Rol eliminado exitosamente');
    }
}
