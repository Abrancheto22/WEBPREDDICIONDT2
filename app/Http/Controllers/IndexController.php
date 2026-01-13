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
use App\Models\Cita;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function dashboard(Request $request)
    {
        // Año y mes seleccionados desde los filtros del dashboard
        $selectedYear = (int)($request->input('year', date('Y')));
        $selectedMonth = $request->filled('month') ? (int)$request->input('month') : null; // 1-12

        // Obtener registros filtrados por año/mes (si aplica) para KPIs
        $kpiQuery = \App\Models\Prediccion::query()
            ->whereNotNull('timer_parada');

        if (config('database.default') === 'pgsql') {
            $kpiQuery->whereRaw("EXTRACT(YEAR FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedYear]);
            if ($selectedMonth) {
                $kpiQuery->whereRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedMonth]);
            }
        } else {
            $kpiQuery->whereYear('timer_parada', $selectedYear);
            if ($selectedMonth) {
                $kpiQuery->whereMonth('timer_parada', $selectedMonth);
            }
        }

        $predicciones = $kpiQuery->get(['timer']);

        // Para depuración: Pasar los valores crudos a la vista
        $valoresTimer = [];
        $suma = 0;

        foreach ($predicciones as $prediccion) {
            $valor = $prediccion->timer;
            $valoresTimer[] = $valor;

            // Intentar convertir a número
            $valorNumerico = 0;
            if (is_numeric($valor)) {
                $valorNumerico = (float)$valor;
            } elseif (is_string($valor)) {
                // Reemplazar comas por puntos y quitar espacios
                $valor = str_replace(',', '.', trim($valor));
                if (is_numeric($valor)) {
                    $valorNumerico = (float)$valor;
                }
            }

            $suma += $valorNumerico;
        }

        $totalPredicciones = count($predicciones);
        $tiempoPromedio = $totalPredicciones > 0 ? $suma / $totalPredicciones : 0;

        $query = \App\Models\Prediccion::query()
            ->whereNotNull('timer_parada')
            ->where('resultado', '>=', 0.5);

        if (config('database.default') === 'pgsql') {
            $query->whereRaw("EXTRACT(YEAR FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedYear]);
            if ($selectedMonth) {
                $query->whereRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedMonth]);
            }
            $trendQuery = $query
                ->selectRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) as m, COUNT(*) as c")
                ->groupByRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP))")
                ->orderByRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP))")
                ->get();
        } else {
            $query->whereYear('timer_parada', $selectedYear);
            if ($selectedMonth) {
                $query->whereMonth('timer_parada', $selectedMonth);
            }
            $trendQuery = $query
                ->selectRaw('MONTH(timer_parada) as m, COUNT(*) as c')
                ->groupByRaw('MONTH(timer_parada)')
                ->orderByRaw('MONTH(timer_parada)')
                ->get();
        }

        $queryNeg = \App\Models\Prediccion::query()
            ->whereNotNull('timer_parada')
            ->where('resultado', '<', 0.5);

        if (config('database.default') === 'pgsql') {
            $queryNeg->whereRaw("EXTRACT(YEAR FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedYear]);
            if ($selectedMonth) {
                $queryNeg->whereRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedMonth]);
            }
            $trendQueryNeg = $queryNeg
                ->selectRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) as m, COUNT(*) as c")
                ->groupByRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP))")
                ->orderByRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP))")
                ->get();
        } else {
            $queryNeg->whereYear('timer_parada', $selectedYear);
            if ($selectedMonth) {
                $queryNeg->whereMonth('timer_parada', $selectedMonth);
            }
            $trendQueryNeg = $queryNeg
                ->selectRaw('MONTH(timer_parada) as m, COUNT(*) as c')
                ->groupByRaw('MONTH(timer_parada)')
                ->orderByRaw('MONTH(timer_parada)')
                ->get();
        }

        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $trendLabels = [];
        $trendCounts = [];
        $trendCountsNeg = [];

        if ($selectedMonth) {
            // Mostrar días del mes seleccionado (1..n)
            $daysInMonth = Carbon::create($selectedYear, $selectedMonth, 1)->daysInMonth;
            // Mapear por día (1-31)
            $countMapByDay = [];
            foreach ($trendQuery as $row) {
                $day = (int) Carbon::parse($row->d)->format('j');
                $countMapByDay[$day] = (int) $row->c;
            }
            $countMapByDayNeg = [];
            foreach ($trendQueryNeg as $row) {
                $day = (int) Carbon::parse($row->d)->format('j');
                $countMapByDayNeg[$day] = (int) $row->c;
            }
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $trendLabels[] = str_pad((string)$day, 2, '0', STR_PAD_LEFT);
                $trendCounts[] = $countMapByDay[$day] ?? 0;
                $trendCountsNeg[] = $countMapByDayNeg[$day] ?? 0;
            }
        } else {
            // Mostrar todos los meses del año
            $countMap = $trendQuery->pluck('c', 'm');
            $countMapNeg = $trendQueryNeg->pluck('c', 'm');
            for ($m = 1; $m <= 12; $m++) {
                $trendLabels[] = $monthNames[$m];
                $trendCounts[] = (int)($countMap[$m] ?? 0);
                $trendCountsNeg[] = (int)($countMapNeg[$m] ?? 0);
            }
        }
        // Doctores y KPIs por doctor seleccionado
        $doctores = Doctor::select('iddoctor','nombre','apellido','sueldo')->get();
        $selectedDoctorId = $request->input('doctor_id');
        $doctorPredCount = null;
        $doctorCOPG = null;

        if ($selectedDoctorId) {
            $doctor = $doctores->firstWhere('iddoctor', (int)$selectedDoctorId);
            if ($doctor) {
                $citas = Cita::where('iddoctor', $doctor->iddoctor)
                    ->whereHas('prediccion', function ($q) use ($selectedYear, $selectedMonth) {
                        $q->whereNotNull('timer_parada');
                        if (config('database.default') === 'pgsql') {
                            $q->whereRaw("EXTRACT(YEAR FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedYear]);
                            if ($selectedMonth) {
                                $q->whereRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedMonth]);
                            }
                        } else {
                            $q->whereYear('timer_parada', $selectedYear);
                            if ($selectedMonth) {
                                $q->whereMonth('timer_parada', $selectedMonth);
                            }
                        }
                    })
                    ->with(['prediccion' => function ($q) use ($selectedYear, $selectedMonth) {
                        $q->whereNotNull('timer_parada');
                        if (config('database.default') === 'pgsql') {
                            $q->whereRaw("EXTRACT(YEAR FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedYear]);
                            if ($selectedMonth) {
                                $q->whereRaw("EXTRACT(MONTH FROM CAST(timer_parada AS TIMESTAMP)) = ?", [$selectedMonth]);
                            }
                        } else {
                            $q->whereYear('timer_parada', $selectedYear);
                            if ($selectedMonth) {
                                $q->whereMonth('timer_parada', $selectedMonth);
                            }
                        }
                    }])
                    ->get();

                $predCount = 0;
                $totalTime = 0.0;
                foreach ($citas as $c) {
                    if ($c->prediccion) {
                        $predCount++;
                        $t = $c->prediccion->timer;
                        if (is_numeric($t)) {
                            $totalTime += (float) $t;
                        } elseif (is_string($t)) {
                            $num = preg_replace('/[^0-9.]/', '', str_replace(',', '.', $t));
                            $totalTime += (float) $num;
                        }
                    }
                }

                $salary = (float) ($doctor->sueldo ?? 0);
                $hourly = $salary / 192.0;
                $totalHours = $totalTime / 3600.0;
                $totalCost = $hourly * $totalHours;
                $copg = $predCount > 0 ? ($totalCost / $predCount) : 0.0;

                $doctorPredCount = $predCount;
                $doctorCOPG = $copg;
            }
        }

        return view('index', [
            'totalTiempoPrediccion' => $suma,
            'totalPredicciones' => $totalPredicciones,
            'tiempoPromedio' => $tiempoPromedio,
            'valoresTimer' => $valoresTimer,
            'trendLabels' => $trendLabels,
            'trendCounts' => $trendCounts,
            'trendCountsNeg' => $trendCountsNeg,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'trendGranularity' => $selectedMonth ? 'daily' : 'monthly',
            'doctores' => $doctores,
            'selectedDoctorId' => $selectedDoctorId,
            'doctorPredCount' => $doctorPredCount,
            'doctorCOPG' => $doctorCOPG,
        ]);
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
