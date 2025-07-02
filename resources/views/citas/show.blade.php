@extends('layouts.app')

@section('title', 'Detalles de la Cita')

@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <!-- Card principal -->
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detalles de la Cita</h5>
                        <div class="d-flex gap-2">
                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Volver
                            </a>
                        </div>
                    </div>
                    
                    <!-- Información básica -->
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Información General -->
                            <div class="col-md-8">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Información General</h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-calendar me-3 text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-label mb-1">Fecha y Hora</label>
                                                        <div class="p-3 rounded">
                                                            <p class="mb-0">{{ $cita->fecha_cita }} / {{ \Carbon\Carbon::parse($cita->hora_cita)->format('H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-flag me-3 text-{{ $cita->estado === 'Pendiente' ? 'warning' : ($cita->estado === 'Completada' ? 'success' : 'danger') }}"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-label mb-1">Estado</label>
                                                        <div class="p-3 rounded">
                                                            <span class="text-{{ $cita->estado === 'Pendiente' ? 'warning' : ($cita->estado === 'Completada' ? 'success' : 'danger') }}">
                                                                {{ $cita->estado }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-id-card me-3 text-info"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <label class="form-label mb-1">ID de la Cita</label>
                                                        <div class="p-3 rounded">
                                                            <span class="text">N° {{ $cita->idcita }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Motivo de la Cita -->
                                <div class="col-12 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="bx bx-notepad me-3 text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">Motivo de la Cita</h6>
                                                </div>
                                            </div>
                                            <div class="p-3 rounded">
                                                <p class="mb-0">{{ $cita->motivo }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Participantes -->
                            <div class="col-md-4">
                                <div class="row g-4">
                                    <!-- Paciente -->
                                    <div class="col-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-user me-3 text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1">Paciente</h6>
                                                        <p class="mb-0 fw-bold">{{ $cita->paciente ? $cita->paciente->nombre . ' ' . $cita->paciente->apellido : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-id-card me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">DNI: {{ $cita->paciente ? $cita->paciente->DNI : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-male me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Sexo: {{ $cita->paciente ? ($cita->paciente->sexo === 'M' ? 'Masculino' : 'Femenino') : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-calendar me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Fecha Nac: {{ $cita->paciente ? $cita->paciente->fecha_nacimiento : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-phone me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Teléfono: {{ $cita->paciente ? $cita->paciente->telefono : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Doctor -->
                                    <div class="col-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-user me-3 text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1">Doctor</h6>
                                                        <p class="mb-0 fw-bold">{{ $cita->doctor ? $cita->doctor->nombre . ' ' . $cita->doctor->apellido : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-notepad me-3 text"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Especialidad: {{ $cita->doctor ? $cita->doctor->especialidad : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-phone me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Teléfono: {{ $cita->doctor ? $cita->doctor->numero : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enfermera -->
                                    <div class="col-12">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="bx bx-user me-3 text-info"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title mb-1">Enfermera</h6>
                                                        <p class="mb-0 fw-bold">{{ $cita->enfermera ? $cita->enfermera->nombre . ' ' . $cita->enfermera->apellido : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <i class="bx bx-phone me-2 text-muted"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <span class="text-muted">Teléfono: {{ $cita->enfermera ? $cita->enfermera->numero : 'N/A' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@endsection
