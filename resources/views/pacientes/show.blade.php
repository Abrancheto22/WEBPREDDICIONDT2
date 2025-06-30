@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                            src="{{ asset($paciente->imagen ?? 'assets/img/avatars/1.png') }}"
                            alt="paciente-avatar"
                            class="d-block w-px-150 h-px-150 rounded"
                            id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <h4 class="mb-2">{{ $paciente->nombre }} {{ $paciente->apellido }}</h4>
                            <p class="mb-0 text-muted">{{ $paciente->sexo === 'M' ? 'Masculino' : 'Femenino' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <h5 class="card-header">Información del Paciente</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DNI</label>
                            <p class="mb-0">{{ $paciente->DNI }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <p class="mb-0">{{ $paciente->fecha_nacimiento }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirección</label>
                            <p class="mb-0">{{ $paciente->direccion }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono</label>
                            <p class="mb-0">{{ $paciente->telefono }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usuario Asociado</label>
                            <p class="mb-0">{{ $paciente->usuario->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ADMINISTRADOR --}}
            @if(Auth::user()->rol->idrol === 1)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary me-2">
                            <i class="bx bx-arrow-back me-1"></i> Volver
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
