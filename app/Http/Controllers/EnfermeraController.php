<?php

namespace App\Http\Controllers;

use App\Models\Enfermera;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EnfermeraController extends Controller
{
    public function index()
    {
        abort_unless((int) Auth::user()->idrol === 1, 403);
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
        $user = Auth::user();
        if ((int) $user->idrol === 3) {
            abort_if($user->enfermera()->exists(), 403, 'La cuenta ya tiene un perfil de enfermería.');
            $usuarios = User::whereKey($user->id)->get();
        } else {
            abort_unless((int) $user->idrol === 1, 403);
            $usuarios = User::where('idrol', 3)->get();
        }
        
        if (request()->routeIs('enfermeras.create')) {
            return view('enfermeras.create', compact('usuarios'));
        }
        
        return abort(404);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless(in_array((int) $user->idrol, [1, 3], true), 403);
        if ((int) $user->idrol === 3) {
            abort_if($user->enfermera()->exists(), 403, 'La cuenta ya tiene un perfil de enfermería.');
        }
        $rules = [
            'DNI' => 'required|string|max:20|unique:efermera',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        if ((int) $user->idrol === 1) {
            $rules['iduser'] = 'required|exists:users,id,idrol,3|unique:efermera,iduser';
        }
        $request->validate($rules);

        $enfermera = new Enfermera();
        $enfermera->DNI = $request->DNI;
        $enfermera->nombre = $request->nombre;
        $enfermera->apellido = $request->apellido;
        $enfermera->numero = $request->numero;
        $enfermera->iduser = (int) $user->idrol === 1 ? $request->iduser : $user->id;

        if ($request->hasFile('imagen')) {
            $enfermera->imagen = $request->file('imagen')->store('images/enfermeras', 'public');
        }

        $enfermera->save();

        return (int) $user->idrol === 3
            ? redirect()->route('profile')->with('success', 'Perfil de enfermería creado exitosamente')
            : redirect()->route('enfermeras.index')->with('success', 'Enfermera creada exitosamente');
    }

    public function edit($idenfermera)
    {
        $enfermera = Enfermera::findOrFail($idenfermera);
        $this->authorizeEnfermeraAccess($enfermera);
        
        // Filtrar usuarios que tienen el rol enfermera
        // Incluimos el usuario actual de la enfermera
        $usuarios = (int) Auth::user()->idrol === 1
            ? User::where('idrol', 3)->orWhere('id', $enfermera->iduser)->get()
            : User::whereKey(Auth::id())->get();
        
        return view('enfermeras.edit', compact('enfermera', 'usuarios'));
    }

    public function update(Request $request, $idenfermera)
    {
        $enfermera = Enfermera::findOrFail($idenfermera);
        $this->authorizeEnfermeraAccess($enfermera);
        $user = Auth::user();

        $rules = [
            'DNI' => 'required|string|max:20|unique:efermera,DNI,' . $idenfermera . ',idenfermera',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'numero' => 'required|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
        if ((int) $user->idrol === 1) {
            $rules['iduser'] = 'required|exists:users,id,idrol,3|unique:efermera,iduser,' . $idenfermera . ',idenfermera';
        }
        $request->validate($rules);

        $enfermera->DNI = $request->DNI;
        $enfermera->nombre = $request->nombre;
        $enfermera->apellido = $request->apellido;
        $enfermera->numero = $request->numero;
        if ((int) $user->idrol === 1) {
            $enfermera->iduser = $request->iduser;
        }

        if ($request->hasFile('imagen')) {
            if ($enfermera->imagen && Storage::disk('public')->exists($enfermera->imagen)) {
                Storage::disk('public')->delete($enfermera->imagen);
            }
            $enfermera->imagen = $request->file('imagen')->store('images/enfermeras', 'public');
        }

        $enfermera->save();

        // Si el usuario autenticado es enfermera y está editando su propio perfil, redirigir al perfil
        $authUser = Auth::user();
        $role = (int) ($authUser->idrol ?? 0);
        if ($role === 3) {
            return redirect()->route('profile')->with('success', 'Perfil de enfermera actualizado exitosamente');
        }

        return redirect()->route('enfermeras.index')->with('success', 'Enfermera actualizada exitosamente');
    }

    public function destroy($idenfermera)
    {
        abort_unless((int) Auth::user()->idrol === 1, 403);
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
        $this->authorizeEnfermeraAccess($enfermera);
        return view('enfermeras.show', compact('enfermera'));
    }

    private function authorizeEnfermeraAccess(Enfermera $enfermera): void
    {
        $user = Auth::user();
        abort_unless($user, 401);
        if ((int) $user->idrol === 1) {
            return;
        }
        abort_unless(
            (int) $user->idrol === 3 && (int) $enfermera->iduser === (int) $user->id,
            403,
            'No tiene permisos sobre este perfil de enfermería.'
        );
    }
}
