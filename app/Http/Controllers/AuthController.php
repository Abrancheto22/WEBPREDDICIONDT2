<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function showLoginForm()
    {
        return view('access.auth-login-basic');
    }

    public function showRegisterForm()
    {
        return view('access.auth-register-basic');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'idrol' => 4, // Asignamos automáticamente el rol de paciente
        ]);

        // Crear el perfil de paciente automáticamente
        \App\Models\Paciente::create([
            'nombre' => $validatedData['name'],
            'apellido' => 'Por actualizar', // Valor temporal
            'DNI' => 'TEMP' . time(), // Valor temporal único
            'sexo' => 'M', // Valor por defecto
            'fecha_nacimiento' => now(), // Valor por defecto
            'direccion' => 'Por actualizar',
            'iduser' => $user->id,
            'imagen' => 'plantilla/assets/img/avatars/default.webp'
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso. Por favor, inicie sesión.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Primero buscamos el usuario por email
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Verificamos si la contraseña coincide
            if (Hash::check($credentials['password'], $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                // Redirección basada en rol
                if ($user->idrol === 4) { // Paciente
                    // Si el paciente tiene datos temporales, redirigir a editar perfil
                    if ($user->paciente && $user->paciente->DNI && str_starts_with($user->paciente->DNI, 'TEMP')) {
                        return redirect()->route('pacientes.edit', $user->paciente->idpaciente)
                            ->with('info', 'Por favor, complete sus datos personales para continuar.');
                    }
                    return redirect()->route('pacientes.panel');
                }

                return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
