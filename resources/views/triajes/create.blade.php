@extends('layouts.app')

@php
use App\Models\Triaje;
@endphp

@section('title', 'Crear Triaje')

@section('content')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pesoInput = document.getElementById('peso');
    const tallaInput = document.getElementById('talla');
    const BMIInput = document.getElementById('BMI');

    function calcularBMI() {
        const peso = parseFloat(pesoInput.value) || 0;
        const talla = parseFloat(tallaInput.value) / 100 || 0; // Convertir de cm a metros
        
        if (talla > 0) {
            const bmi = peso / (talla * talla);
            BMIInput.value = bmi.toFixed(1);
        } else {
            BMIInput.value = '';
        }
    }

    pesoInput.addEventListener('input', calcularBMI);
    tallaInput.addEventListener('input', calcularBMI);
});
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Crear Triaje') }}</h5>
                        <a href="{{ route('triajes.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('triajes.store') }}">
                        @csrf

                        <!-- Sección de Identificación -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">{{ __('Identificación') }}</h6>
                            <div class="form-group row">
                                <label for="cita_id" class="col-md-3 col-form-label text-md-right">{{ __('Cita') }}</label>
                                <div class="col-md-9">
                                    <select id="idcita" class="form-control @error('idcita') is-invalid @enderror" name="idcita" required>
                                        <option value="">Seleccione una cita</option>
                                        @foreach($citas as $cita)
                                            <option value="{{ $cita->idcita }}">
                                                {{ $cita->paciente->nombre }} - 
                                                {{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($cita->hora_cita)->format('H:i') }}
                                                @if(Triaje::where('idcita', $cita->idcita)->exists())
                                                    <span class="text-muted">(Ya tiene triaje)</span>
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cita_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Parámetros Físicos -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">{{ __('Parámetros Físicos') }}</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="edad" class="form-label">{{ __('Edad') }}</label>
                                        <input id="edad" type="number" class="form-control @error('edad') is-invalid @enderror" name="edad" value="{{ old('edad') }}" required>
                                        @error('edad')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="talla" class="form-label">{{ __('Talla (cm)') }}</label>
                                        <input id="talla" type="number" step="0.1" class="form-control @error('talla') is-invalid @enderror" name="talla" value="{{ old('talla') }}" required>
                                        @error('talla')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="peso" class="form-label">{{ __('Peso (kg)') }}</label>
                                        <input id="peso" type="number" step="0.1" class="form-control @error('peso') is-invalid @enderror" name="peso" value="{{ old('peso') }}" required>
                                        @error('peso')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="grosor_piel" class="form-label">{{ __('Grosor Piel (mm)') }}</label>
                                        <input id="grosor_piel" type="number" step="0.1" class="form-control @error('grosor_piel') is-invalid @enderror" name="grosor_piel" value="{{ old('grosor_piel') }}" required>
                                        @error('grosor_piel')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="BMI">{{ __('BMI') }}</label>
                                        <input id="BMI" type="number" step="0.1" class="form-control" name="BMI" value="{{ old('BMI') }}" readonly>
                                        @error('BMI')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Observaciones -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">{{ __('Observaciones') }}</h6>
                            <div class="form-group">
                                <textarea id="observaciones" class="form-control @error('observaciones') is-invalid @enderror" name="observaciones" rows="4" required>{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-4">
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> {{ __('Guardar Triaje') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
