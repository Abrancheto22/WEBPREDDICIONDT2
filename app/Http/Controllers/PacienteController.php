<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = (int) ($user->idrol ?? 0);

        // Si es paciente, redirigir al panel personal
        if ($role === 4) {
            return redirect()->route('pacientes.panel');
        }

        $query = Paciente::with('usuario');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'ilike', "%{$search}%")
                  ->orWhere('apellido', 'ilike', "%{$search}%")
                  ->orWhere('DNI', 'ilike', "%{$search}%");
            });
        }

        // Optimización: Paginación y eliminación de verificación de disco por registro
        $pacientes = $query->orderBy('apellido')->paginate(10);
        
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
        } else {
            // Asignar imagen por defecto si no se sube ninguna
            $paciente->imagen = 'plantilla/assets/img/avatars/default.webp';
        }

        $paciente->save();

        return redirect()->route('pacientes.index')->with('success', 'Paciente creado exitosamente');
    }

    public function show($idpaciente)
    {
        $paciente = Paciente::with(['usuario', 'citas.triaje', 'citas.prediccion', 'citas.doctor.usuario'])->findOrFail($idpaciente);

        // Asegurarse de que el paciente autenticado solo pueda ver su propio historial, a menos que sea admin
        if (Auth::user()->rol->idrol === 4 && Auth::user()->paciente->idpaciente != $idpaciente) {
            abort(403, 'Acceso no autorizado.');
        }

        $predicciones = $paciente->citas->map(function ($cita) {
            return $cita->prediccion;
        })->filter();

        return view('pacientes.show', compact('paciente', 'predicciones'));
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
        } elseif (empty($paciente->imagen)) {
            // Asignar imagen por defecto si no hay existente y no se sube nueva
            $paciente->imagen = 'plantilla/assets/img/avatars/default.webp';
        }

        $paciente->save();

        $authUser = Auth::user();
        $role = (int) ($authUser->idrol ?? 0);
        if ($role === 4) {
            return redirect()->route('profile')->with('success', 'Perfil actualizado exitosamente');
        }

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

    public function panel(Request $request)
    {
        $user = Auth::user();

        $role = (int) ($user->idrol ?? 0);
        $isPaciente = $role === 4;
        $isAdminOrEnfermera = in_array($role, [1, 3], true);

        $pacientesList = collect();
        $paciente = null;

        if ($isPaciente) {
            // Obtener el paciente asociado al usuario autenticado usando la relación
            $paciente = Paciente::where('iduser', $user->id)
                ->with(['citas.triaje', 'citas.prediccion', 'citas.doctor.usuario'])
                ->first();

            if (!$paciente) {
                return view('pacientes.panel', [
                    'pacientes' => $pacientesList,
                    'selectedPaciente' => null,
                    'citas' => collect(),
                    'isAdminOrEnfermera' => false,
                ]);
            }
        } elseif ($isAdminOrEnfermera) {
            // Cargar lista para selector
            $pacientesList = Paciente::orderBy('apellido')->orderBy('nombre')->get(['idpaciente','nombre','apellido','DNI']);

            $selectedId = $request->input('idpaciente');
            if ($selectedId) {
                $paciente = Paciente::with(['citas.triaje', 'citas.prediccion', 'citas.doctor.usuario'])
                    ->find($selectedId);
            }
        }

        // Optimización: Limitar el historial de citas para evitar sobrecarga
        $citas = $paciente ? $paciente->citas()
            ->with(['triaje', 'prediccion', 'doctor.usuario'])
            ->orderByDesc('fecha_cita')
            ->take(50) // Limitar a las últimas 50 citas
            ->get() : collect();

        return view('pacientes.panel', [
            'pacientes' => $pacientesList,
            'selectedPaciente' => $paciente,
            'citas' => $citas,
            'isAdminOrEnfermera' => $isAdminOrEnfermera,
        ]);
    }
}
