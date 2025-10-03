@extends('layouts.app')

@section('title', 'Detalles de la Predicción')

@section('content')
<style>
.ai-analysis-content {
    line-height: 1.7;
    font-size: 14px;
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.ai-analysis-content strong {
    color: #0d6efd;
    font-weight: 600;
}

/* Estilos para títulos y secciones */
.ai-analysis-content h1, 
.ai-analysis-content h2, 
.ai-analysis-content h3, 
.ai-analysis-content h4, 
.ai-analysis-content h5, 
.ai-analysis-content h6 {
    color: #2c5aa0;
    margin: 25px 0 15px 0;
    font-weight: bold;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.ai-analysis-content h3 {
    font-size: 20px;
    border-bottom: 3px solid #2c5aa0;
    padding-bottom: 8px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 12px 15px 8px 15px;
    border-radius: 8px 8px 0 0;
    margin-bottom: 20px;
}

.ai-analysis-content h4 {
    font-size: 17px;
    color: #495057;
    border-left: 4px solid #007bff;
    padding-left: 12px;
    margin: 20px 0 12px 0;
}

.ai-analysis-content h5 {
    font-size: 15px;
    color: #6c757d;
    margin: 15px 0 10px 0;
}

/* Estilos para listas */
.ai-analysis-content ul, 
.ai-analysis-content ol {
    margin: 15px 0;
    padding-left: 25px;
    background: #f8f9fa;
    border-radius: 6px;
    padding: 15px 25px;
}

.ai-analysis-content li {
    margin: 8px 0;
    line-height: 1.6;
    color: #495057;
}

/* Estilos para párrafos */
.ai-analysis-content p {
    margin: 15px 0;
    text-align: justify;
    color: #495057;
    line-height: 1.7;
}

/* Estilos mejorados para tablas en el análisis de IA */
.ai-analysis-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.ai-analysis-content table th {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #ffffff;
    font-weight: 600;
    padding: 15px 12px;
    text-align: left;
    border: none;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ai-analysis-content table td {
    padding: 12px;
    border-bottom: 1px solid #e9ecef;
    font-size: 13px;
    color: #495057;
    vertical-align: top;
    line-height: 1.5;
}

.ai-analysis-content table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.ai-analysis-content table tr:hover {
    background-color: #e3f2fd;
    transition: background-color 0.3s ease;
}

.ai-analysis-content table tr:last-child td {
    border-bottom: none;
}

/* Estilos para bloques de información destacada */
.ai-analysis-content .info-block {
    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
    border-left: 4px solid #2196f3;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.ai-analysis-content .warning-block {
    background: linear-gradient(135deg, #fff3e0, #ffcc80);
    border-left: 4px solid #ff9800;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.ai-analysis-content .success-block {
    background: linear-gradient(135deg, #e8f5e8, #c8e6c9);
    border-left: 4px solid #4caf50;
    padding: 20px;
    margin: 20px 0;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.ai-analysis-content .info-block h4,
.ai-analysis-content .warning-block h4,
.ai-analysis-content .success-block h4 {
    margin-top: 0;
    margin-bottom: 12px;
    font-size: 15px;
    font-weight: 600;
}

/* Estilos adicionales para mejorar la presentación */
.ai-analysis-content {
    padding: 20px;
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Espaciado mejorado entre secciones */
.ai-analysis-content > *:first-child {
    margin-top: 0;
}

.ai-analysis-content > *:last-child {
    margin-bottom: 0;
}

/* Estilos para texto en negrita dentro del contenido */
.ai-analysis-content strong,
.ai-analysis-content b {
    color: #2c5aa0;
    font-weight: 600;
}

/* Mejoras para la legibilidad */
.ai-analysis-content {
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Espaciado entre párrafos consecutivos */
.ai-analysis-content p + p {
    margin-top: 18px;
}
</style>
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
                        <h5 class="mb-3">Resultado de la Predicción</h5>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <strong>Probabilidad de Diabetes:</strong> {{ number_format($prediccion->resultado * 100, 2) }}%
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Diagnóstico Final:</strong>
                                <span class="badge {{ $prediccion->resultado >= 0.5 ? 'bg-danger' : 'bg-success' }}">
                                    {{ $prediccion->resultado >= 0.5 ? 'Positivo' : 'Negativo' }}
                                </span>
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Observaciones:</strong> {{ $prediccion->observacion }}
                            </div>
                        </div>
                    </div>

                    @if($prediccion->analisis_ia)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bx bx-brain text-primary me-2"></i>Análisis de Inteligencia Artificial
                        </h5>
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="bx bx-stethoscope me-1"></i>
                                    Evaluación Clínica para Toma de Decisiones Médicas
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="ai-analysis-content">
                                    {!! $prediccion->analisis_ia !!}
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <small class="text-muted">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Este análisis es generado por IA y debe ser interpretado por el médico tratante como apoyo en la toma de decisiones clínicas.
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    

                    <div class="mt-4">
                        <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i>
                            Volver
                        </a>
                        <a href="{{ route('predicciones.pdf', $prediccion->idprediccion) }}" class="btn btn-danger" target="_blank">
                            <i class="bx bxs-file-pdf me-1"></i>
                            Generar Reporte PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
