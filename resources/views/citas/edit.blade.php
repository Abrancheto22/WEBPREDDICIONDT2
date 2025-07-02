@extends('layouts.app')

@section('title', 'Editar Cita')

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Editar Cita</h5>
                        <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Volver
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form action="{{ route('citas.update', $cita->idcita) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Mensajes de éxito y error -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label for="paciente" class="form-label">Paciente</label>
                                    <select
                                        class="form-select @error('paciente') is-invalid @enderror"
                                        id="paciente"
                                        name="paciente"
                                        required>
                                        <option value="">Seleccione un paciente...</option>
                                        @foreach($pacientes as $paciente)
                                            <option value="{{ $paciente->idpaciente }}" {{ old('paciente', $cita->idpaciente) == $paciente->idpaciente ? 'selected' : '' }}>
                                                {{ $paciente->nombre }} {{ $paciente->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('paciente')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="doctor" class="form-label">Doctor</label>
                                    <select
                                        class="form-select @error('doctor') is-invalid @enderror"
                                        id="doctor"
                                        name="doctor"
                                        required>
                                        <option value="">Seleccione un doctor...</option>
                                        @foreach($doctores as $doctor)
                                            <option value="{{ $doctor->iddoctor }}" {{ old('doctor', $cita->iddoctor) == $doctor->iddoctor ? 'selected' : '' }}>
                                                {{ $doctor->nombre }} {{ $doctor->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="enfermera" class="form-label">Enfermera</label>
                                    <select
                                        class="form-select @error('enfermera') is-invalid @enderror"
                                        id="enfermera"
                                        name="enfermera"
                                        required>
                                        <option value="">Seleccione una enfermera...</option>
                                        @foreach($enfermeras as $enfermera)
                                            <option value="{{ $enfermera->idenfermera }}" {{ old('enfermera', $cita->idenfermera) == $enfermera->idenfermera ? 'selected' : '' }}>
                                                {{ $enfermera->nombre }} {{ $enfermera->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('enfermera')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_cita" class="form-label">Fecha de Cita</label>
                                    <input
                                        type="date"
                                        class="form-control @error('fecha_cita') is-invalid @enderror"
                                        id="fecha_cita"
                                        name="fecha_cita"
                                        value="{{ old('fecha_cita', $cita->fecha_cita) }}"
                                        required />
                                    @error('fecha_cita')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="hora_cita" class="form-label">Hora de Cita</label>
                                    <input
                                        type="time"
                                        class="form-control @error('hora_cita') is-invalid @enderror"
                                        id="hora_cita"
                                        name="hora_cita"
                                        value="{{ old('hora_cita', $cita->hora_cita) }}"
                                        required />
                                    @error('hora_cita')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="motivo" class="form-label">Motivo de la Cita</label>
                                    <textarea
                                        class="form-control @error('motivo') is-invalid @enderror"
                                        id="motivo"
                                        name="motivo"
                                        rows="3"
                                        required>{{ old('motivo', $cita->motivo) }}</textarea>
                                    @error('motivo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select
                                        class="form-select @error('estado') is-invalid @enderror"
                                        id="estado"
                                        name="estado"
                                        required>
                                        <option value="Pendiente" {{ old('estado', $cita->estado) === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="Cancelada" {{ old('estado', $cita->estado) === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        <option value="Completada" {{ old('estado', $cita->estado) === 'Completada' ? 'selected' : '' }}>Completada</option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Actualizar Cita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@endsection