<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;
use App\Models\Doctor;

class IndexController
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboard()
    {
        return view('index');
    }

    public function settings()
    {
        return view('access.settings');
    }

    public function profile()
    {
        $user = Auth::user();
        $doctor = $user->doctor; // Obtener el doctor asociado si existe
        return view('access.profile', compact('user', 'doctor'));
    }

    public function users()
    {
        $users = User::with('rol')->get();
        return view('users.index', compact('users'));
    }

    public function createUser()
    {
        $roles = Rol::all();
        return view('users.create', compact('roles'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'idrol' => 'required|integer|exists:rols,idrol'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'idrol' => $validated['idrol']
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Rol::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'idrol' => 'required|integer|exists:rols,idrol'
        ]);

        // Solo actualiza la contraseña si se proporciona
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
}
