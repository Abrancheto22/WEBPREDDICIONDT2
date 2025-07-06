<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Rol;
use App\Models\Doctor;
use App\Models\Enfermera;
use App\Models\Paciente;

class IndexController extends Controller
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
        $user = Auth::user();
        
        switch ($user->idrol) {
            case 2: // Doctor
                $doctor = Doctor::where('iduser', $user->id)->first();
                if ($doctor) {
                    return redirect()->route('doctores.edit', ['id' => $doctor->iddoctor]);
                }
                break;
            case 3: // Enfermera
                $enfermera = Enfermera::where('iduser', $user->id)->first();
                if ($enfermera) {
                    return redirect()->route('enfermeras.edit', ['idenfermera' => $enfermera->idenfermera]);
                }
                break;
            case 4: // Paciente
                $paciente = Paciente::where('iduser', $user->id)->first();
                if ($paciente) {
                    return redirect()->route('pacientes.edit', ['id' => $paciente->idpaciente]);
                }
                break;
            default: // Admin u otros roles
                return redirect()->route('users.edit', ['id' => $user->id]);
        }

        // Si no tiene perfil, redirigir a la vista de settings
        return view('access.settings');
    }

    public function profile()
    {
        $user = Auth::user();
        
        // Determinar el tipo de perfil según el rol
        $profileData = null;
        $profileView = null;
        $profileRoute = null;
        $profileExists = false;

        switch ($user->idrol) {
            case 2: // Doctor
                $profileData = Doctor::where('iduser', $user->id)->first();
                $profileView = 'doctor';
                $profileExists = !empty($profileData);
                $profileRoute = $profileExists ? 'doctores.edit' : 'doctores.create';
                break;
            case 3: // Enfermera
                $profileData = Enfermera::where('iduser', $user->id)->first();
                $profileView = 'enfermera';
                $profileExists = !empty($profileData);
                $profileRoute = $profileExists ? 'enfermeras.edit' : 'enfermeras.create';
                break;
            case 4: // Paciente
                $profileData = Paciente::where('iduser', $user->id)->first();
                $profileView = 'paciente';
                $profileExists = !empty($profileData);
                $profileRoute = $profileExists ? 'pacientes.edit' : 'pacientes.create';
                break;
            default: // Admin u otros roles
                $profileView = 'admin';
                break;
        }

        return view('access.profile', compact('user', 'profileData', 'profileView', 'profileRoute', 'profileExists'));
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
