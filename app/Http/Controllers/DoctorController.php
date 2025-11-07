<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Prediccion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Exports\DoctorCostosExport;
use Maatwebsite\Excel\Facades\Excel;

class DoctorController extends Controller
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
            'sueldo' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:doctor,iduser',
        ]);

        $doctor = new Doctor();
        $doctor->DNI = $request->DNI;
        $doctor->nombre = $request->nombre;
        $doctor->apellido = $request->apellido;
        $doctor->numero = $request->numero;
        $doctor->especialidad = $request->especialidad;
        $doctor->sueldo = $request->sueldo;
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
            'sueldo' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'iduser' => 'required|exists:users,id|unique:doctor,iduser,' . $iddoctor . ',iddoctor',
        ]);

        $doctor->DNI = $request->DNI;
        $doctor->nombre = $request->nombre;
        $doctor->apellido = $request->apellido;
        $doctor->numero = $request->numero;
        $doctor->especialidad = $request->especialidad;
        $doctor->sueldo = $request->sueldo;
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

        // Si el usuario autenticado es doctor y está editando su propio perfil, redirigir al dashboard
        $authUser = Auth::user();
        $role = (int) ($authUser->idrol ?? 0);
        if ($role === 2) {
            return redirect()->route('profile')->with('success', 'Perfil de doctor actualizado exitosamente');
        }

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

    public function costos()
    {
        $doctores = Doctor::select('iddoctor','nombre','apellido','sueldo')
            ->with('usuario')
            ->get();

        $stats = [];
        foreach ($doctores as $doc) {
            $citas = \App\Models\Cita::where('iddoctor', $doc->iddoctor)
                ->with('prediccion')
                ->get();

            $predCount = 0;
            $totalTime = 0.0; // en segundos

            foreach ($citas as $c) {
                if ($c->prediccion) {
                    $predCount++;
                    $t = $c->prediccion->timer;
                    if (is_numeric($t)) {
                        $totalTime += (float) $t;
                    } else if (is_string($t)) {
                        $num = preg_replace('/[^0-9.]/', '', $t);
                        $totalTime += (float) $num;
                    }
                }
            }

            $stats[] = [
                'doctor' => $doc,
                'pred_count' => $predCount,
                'total_time' => $totalTime,
            ];
        }

        // Calcular matriz de confusión global (umbral 0.35)
        $TP = 0; $TN = 0; $FP = 0; $FN = 0;
        $threshold = 0.35;
        $predicciones = Prediccion::select('resultado', 'validar_prediccion')->get();
        foreach ($predicciones as $p) {
            if ($p->validar_prediccion === null) { continue; }
            $predictedPositive = ((float)$p->resultado) > $threshold;
            $actualPositive = (int)$p->validar_prediccion === 1;
            if ($predictedPositive && $actualPositive) { $TP++; }
            elseif (!$predictedPositive && !$actualPositive) { $TN++; }
            elseif ($predictedPositive && !$actualPositive) { $FP++; }
            else { $FN++; }
        }

        return view('doctores.costos', [ 
            'stats' => $stats,
            'confusion' => [
                'TP' => $TP,
                'TN' => $TN,
                'FP' => $FP,
                'FN' => $FN,
                'threshold' => $threshold,
            ],
        ]);
    }

    public function exportCostos()
    {
        return Excel::download(new DoctorCostosExport, 'costos_doctores.xlsx');
    }

    public function exportConfusion(Request $request)
    {
        $threshold = (float) ($request->get('threshold', 0.35));
        return Excel::download(new \App\Exports\ConfusionMatrixExport($threshold), 'matriz_confusion.xlsx');
    }
}
