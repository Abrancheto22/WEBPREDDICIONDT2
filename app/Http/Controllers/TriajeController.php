<?php

namespace App\Http\Controllers;

use App\Models\Triaje;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TriajeController
{
    public function index()
    {
        $triajes = Triaje::with(['cita.paciente'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('triajes.index', compact('triajes'));
    }

    public function create()
    {
        $citas = Cita::where('estado', 'pendiente')
            ->with(['paciente', 'doctor', 'enfermera'])
            ->get();
        return view('triajes.create', compact('citas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idcita' => [
                'required',
                'exists:cita,idcita',
                function ($attribute, $value, $fail) {
                    if (Triaje::where('idcita', $value)->exists()) {
                        $fail('Esta cita ya tiene un triaje registrado.');
                    }
                }
            ],
            'edad' => 'required|integer|min:0',
            'talla' => 'required|numeric|min:0',
            'peso' => 'required|numeric|min:0',
            'BMI' => 'required|numeric|min:0',
            'grosor_piel' => 'required|numeric|min:0',
            'observaciones' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $triaje = Triaje::create($request->all());

        return redirect()->route('triajes.index')
            ->with('success', 'Triaje registrado exitosamente');
    }

    public function show($id)
    {
        $triaje = Triaje::with('cita')->findOrFail($id);
        return view('triajes.show', compact('triaje'));
    }

    public function edit($id)
    {
        $triaje = Triaje::with('cita')->findOrFail($id);
        $citas = Cita::where('estado', 'pendiente')->get();
        return view('triajes.edit', compact('triaje', 'citas'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'idcita' => [
                'required',
                'exists:cita,idcita',
                function ($attribute, $value, $fail) {
                    if (Triaje::where('idcita', $value)->exists()) {
                        $fail('Esta cita ya tiene un triaje registrado.');
                    }
                }
            ],
            'edad' => 'required|integer|min:0',
            'talla' => 'required|numeric|min:0',
            'peso' => 'required|numeric|min:0',
            'BMI' => 'required|numeric|min:0',
            'grosor_piel' => 'required|numeric|min:0',
            'observaciones' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $triaje = Triaje::findOrFail($id);
        $triaje->update($request->all());

        return redirect()->route('triajes.index')
            ->with('success', 'Triaje actualizado exitosamente');
    }

    public function destroy($id)
    {
        $triaje = Triaje::findOrFail($id);
        $triaje->delete();

        return redirect()->route('triajes.index')
            ->with('success', 'Triaje eliminado exitosamente');
    }
}
