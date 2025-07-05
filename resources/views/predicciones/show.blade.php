@extends('layouts.app')

@section('title', 'Detalles de la Predicción')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Detalles de la Predicción</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5 class="mb-3">Información de la Cita</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>ID Cita:</strong> {{ $prediccion->cita->idcita }}
                            </div>
                            <div class="col-md-6">
                                <strong>Fecha:</strong> {{ date('d/m/Y', strtotime($prediccion->cita->fecha_cita)) }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Hora:</strong> {{ date('H:i', strtotime($prediccion->cita->hora_cita)) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Estado:</strong> 
                                <span class="badge {{ $prediccion->cita->estado === 'pendiente' ? 'bg-warning' : ($prediccion->cita->estado === 'atendida' ? 'bg-success' : 'bg-secondary') }}">
                                    {{ ucfirst($prediccion->cita->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Datos del Paciente</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Nombre:</strong> {{ $prediccion->cita->paciente->nombre }} {{ $prediccion->cita->paciente->apellido }}
                            </div>
                            <div class="col-md-6">
                                <strong>Edad:</strong> {{ $prediccion->edad }} años
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Sexo:</strong> {{ $prediccion->cita->paciente->sexo }}
                            </div>
                            <div class="col-md-6">
                                <strong>DNI:</strong> {{ $prediccion->cita->paciente->DNI }}
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Parámetros de la Predicción</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Glucosa:</strong> {{ $prediccion->glucosa }} mg/dl
                            </div>
                            <div class="col-md-6">
                                <strong>Presión Sanguínea:</strong> {{ $prediccion->presion_sanguinea }} mmHg
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Grosor Piel:</strong> {{ $prediccion->grosor_piel }} mm
                            </div>
                            <div class="col-md-6">
                                <strong>Embarazos:</strong> {{ $prediccion->embarazos }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>BMI:</strong> {{ number_format($prediccion->BMI, 2) }} kg/m²
                            </div>
                            <div class="col-md-6">
                                <strong>Pedigree:</strong> {{ number_format($prediccion->pedigree, 2) }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <strong>Insulina:</strong> {{ $prediccion->insulina }} μU/ml
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Resultado</h5>
                        <div class="row">
                            <div class="col-12">
                                <strong>Resultado:</strong> 
                                <span class="badge {{ $prediccion->resultado === 1 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $prediccion->resultado === 1 ? 'Positivo' : 'Negativo' }}
                                </span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Observación:</strong> {{ $prediccion->observacion ?? 'Sin observación' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
