@extends('layouts.app')

@section('title', 'Detalles del Triaje')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Detalles del Triaje') }}</h5>
                        <div>
                            <a href="{{ route('triajes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i> {{ __('Volver') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Información de la Cita -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ __('Información de la Cita') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Paciente') }}:</strong></p>
                                            <p>{{ $triaje->cita->paciente->nombre }} {{ $triaje->cita->paciente->apellido }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Fecha de Cita') }}:</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($triaje->cita->fecha_cita)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($triaje->cita->hora_cita)->format('H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Doctor') }}:</strong></p>
                                            <p>{{ $triaje->cita->doctor->nombre }} {{ $triaje->cita->doctor->apellido }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Estado') }}:</strong></p>
                                            <p>{{ $triaje->cita->estado }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Enfermera') }}:</strong></p>
                                            <p>{{ $triaje->cita->enfermera->nombre }} {{ $triaje->cita->enfermera->apellido }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Parámetros Físicos -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ __('Parámetros Físicos') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Edad') }}:</strong></p>
                                            <p>{{ $triaje->edad }} años</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Talla') }}:</strong></p>
                                            <p>{{ $triaje->talla }} cm</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Peso') }}:</strong></p>
                                            <p>{{ $triaje->peso }} kg</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('BMI') }}:</strong></p>
                                            <p>{{ $triaje->BMI }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>{{ __('Grosor Piel') }}:</strong></p>
                                            <p>{{ $triaje->grosor_piel }} mm</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">{{ __('Observaciones') }}</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $triaje->observaciones }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
