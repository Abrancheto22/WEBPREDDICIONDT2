@extends('layouts.app')

@section('title', 'Información del Paciente')

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
                            <p class="mb-0">{{ optional($paciente->usuario)->name ?? 'No asignado' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <h5 class="card-header">Historial Médico</h5>
                <div class="card-body">
                    <!-- Pestañas para Citas, Triajes y Predicciones -->
                    <ul class="nav nav-tabs" id="historialMedicoTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="citas-tab" data-bs-toggle="tab" data-bs-target="#citas" type="button" role="tab" aria-controls="citas" aria-selected="true">Citas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="triajes-tab" data-bs-toggle="tab" data-bs-target="#triajes" type="button" role="tab" aria-controls="triajes" aria-selected="false">Triajes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="predicciones-tab" data-bs-toggle="tab" data-bs-target="#predicciones" type="button" role="tab" aria-controls="predicciones" aria-selected="false">Predicciones</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="historialMedicoTabContent">
                        <!-- Contenido de Citas -->
                        <div class="tab-pane fade show active" id="citas" role="tabpanel" aria-labelledby="citas-tab">
                            <div class="table-responsive text-nowrap mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha y Hora</th>
                                            <th>Doctor</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($paciente->citas as $cita)
                                            <tr>
                                                <td>{{ $cita->idcita }}</td>
                                                <td>{{ $cita->fecha_cita }} {{ $cita->hora_cita }}</td>
                                                <td>
                                                    @if ($cita->doctor && $cita->doctor->usuario)
                                                        {{ $cita->doctor->usuario->name }}
                                                    @else
                                                        No asignado
                                                    @endif
                                                </td>
                                                <td>{{ $cita->estado }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No hay citas registradas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Contenido de Triajes -->
                        <div class="tab-pane fade" id="triajes" role="tabpanel" aria-labelledby="triajes-tab">
                            <div class="table-responsive text-nowrap mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha Cita</th>
                                            <th>Edad</th>
                                            <th>Talla</th>
                                            <th>Peso</th>
                                            <th>BMI</th>
                                            <th>Grosor Piel</th>
                                            <th>Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $triajeRows = 0; @endphp
                                        @foreach ($paciente->citas as $cita)
                                            @if ($cita->triaje)
                                                @php $triajeRows++; @endphp
                                                <tr>
                                                    <td>{{ $cita->triaje->idtriaje }}</td>
                                                    <td>{{ $cita->fecha_cita }} {{ $cita->hora_cita }}</td>
                                                    <td>{{ $cita->triaje->edad }}</td>
                                                    <td>{{ $cita->triaje->talla }}</td>
                                                    <td>{{ $cita->triaje->peso }}</td>
                                                    <td>{{ $cita->triaje->BMI }}</td>
                                                    <td>{{ $cita->triaje->grosor_piel }}</td>
                                                    <td>{{ $cita->triaje->observaciones }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if ($triajeRows === 0)
                                            <tr>
                                                <td colspan="8" class="text-center">No hay triajes registrados.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Contenido de Predicciones -->
                        <div class="tab-pane fade" id="predicciones" role="tabpanel" aria-labelledby="predicciones-tab">
                            <div class="table-responsive text-nowrap mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Fecha</th>
                                            <th>Resultado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $predRows = 0; @endphp
                                        @foreach ($paciente->citas as $cita)
                                            @if ($cita->prediccion)
                                                @php $predRows++; @endphp
                                                <tr>
                                                    <td>{{ $cita->prediccion->idprediccion }}</td>
                                                    <td>{{ $cita->fecha_cita }}</td>
                                                    <td>{{ number_format($cita->prediccion->resultado, 2) }}</td>
                                                    <td>
                                                        <a href="{{ route('predicciones.show', $cita->prediccion->idprediccion) }}" class="btn btn-info btn-sm">Ver Detalles</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        @if ($predRows === 0)
                                            <tr>
                                                <td colspan="4" class="text-center">No hay predicciones registradas.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
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
