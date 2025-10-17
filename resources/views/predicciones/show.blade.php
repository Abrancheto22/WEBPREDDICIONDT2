@extends('layouts.app')

@section('title', 'Detalles de la Predicción')

@section('content')
<style>
/* Contenedor principal del análisis de IA */
.ai-analysis-content {
    line-height: 1.6;
    font-size: 14px;
    color: #333;
    font-family: Arial, sans-serif;
}

/* Contenido interno */
.ai-analysis-content > * {
    margin-bottom: 15px;
}

.ai-analysis-content strong {
    color: #495057;
    font-weight: 600;
}

/* Títulos simples */
.ai-analysis-content h1, 
.ai-analysis-content h2, 
.ai-analysis-content h3, 
.ai-analysis-content h4, 
.ai-analysis-content h5, 
.ai-analysis-content h6 {
    color: #212529;
    margin: 10px 0;
    font-weight: 600;
}

.ai-analysis-content h3 {
    font-size: 18px;
}

.ai-analysis-content h4 {
    font-size: 16px;
    color: #495057;
}

.ai-analysis-content h5 {
    font-size: 14px;
    color: #6c757d;
}

/* Listas simples */
.ai-analysis-content ul, 
.ai-analysis-content ol {
    margin: 5px 0;
    padding: 0 20px;
}

.ai-analysis-content li {
    margin: 5px 0;
    line-height: 1.5;
    color: #495057;
}

/* Párrafos simples */
.ai-analysis-content p {
    margin: 5px 0;
    text-align: justify;
    color: #495057;
    line-height: 1.6;
}

/* Tablas simples */
.ai-analysis-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
    background: #ffffff;
    border: 1px solid #dee2e6;
}

.ai-analysis-content table th {
    background: #f8f9fa;
    color: #495057;
    font-weight: 600;
    padding: 8px 5px;
    text-align: center;
    border: 1px solid #dee2e6;
    font-size: 13px;
}

.ai-analysis-content table td {
    padding: 5px;
    border: 1px solid #dee2e6;
    font-size: 13px;
    color: #495057;
    text-align: center;
    background: #ffffff;
}

.ai-analysis-content table tr:nth-child(even) td {
    background: #f8f9fa;
}

/* Bloques de información simples */
.ai-analysis-content .info-block {
    background: #e7f3ff;
    color: #0c5460;
    padding: 10px;
    margin: 10px 0;
    border-radius: 3px;
}

.ai-analysis-content .warning-block {
    background: #fff3cd;
    color: #856404;
    padding: 10px;
    margin: 10px 0;
    border-radius: 3px;
}

.ai-analysis-content .success-block {
    background: #d1edff;
    color: #155724;
    padding: 10px;
    margin: 10px 0;
    border-radius: 3px;
}

.ai-analysis-content .info-block h4,
.ai-analysis-content .warning-block h4,
.ai-analysis-content .success-block h4 {
    margin-top: 0;
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 600;
}



/* Responsive design */
@media (max-width: 768px) {
    .ai-analysis-content {
        padding: 15px;
    }
    
    .ai-analysis-content h3 {
        font-size: 18px;
        padding: 10px 15px;
    }
    
    .ai-analysis-content table th,
    .ai-analysis-content table td {
        padding: 8px 6px;
        font-size: 12px;
    }
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

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
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
                    
                    @if($prediccion->attachment_paths && count($prediccion->attachment_paths) > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bx bx-paperclip text-info me-2"></i>Documentos Adjuntos
                        </h5>
                        <div class="card border-info">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-info">
                                    <i class="bx bx-file me-1"></i>
                                    Archivos Adjuntos a la Predicción
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($prediccion->attachment_paths as $index => $path)
                                        @php
                                            $fileName = $prediccion->attachment_names[$index] ?? basename($path);
                                            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $iconClass = match($fileExtension) {
                                                'pdf' => 'bx-file-pdf text-danger',
                                                'docx', 'doc' => 'bx-file-doc text-primary',
                                                'jpg', 'jpeg', 'png', 'gif' => 'bx-image text-success',
                                                default => 'bx-file text-secondary'
                                            };
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card h-100 border-light">
                                                <div class="card-body text-center">
                                                    <i class="bx {{ $iconClass }} display-4 mb-2"></i>
                                                    <h6 class="card-title text-truncate" title="{{ $fileName }}">
                                                        {{ $fileName }}
                                                    </h6>
                                                    <p class="card-text">
                                                        <small class="text-muted">{{ strtoupper($fileExtension) }}</small>
                                                    </p>
                                                    <a href="{{ route('predicciones.downloadAttachment', ['id' => $prediccion->idprediccion, 'index' => $index]) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="bx bx-download me-1"></i>Descargar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <small class="text-muted">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Total de archivos adjuntos: {{ count($prediccion->attachment_paths) }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5 class="mb-3">Enviar Reporte por Correo Electrónico</h5>
                        <form action="{{ route('predicciones.sendEmail', $prediccion->idprediccion) }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Correo electrónico del destinatario" required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bx bx-envelope me-1"></i> Enviar por Correo
                                </button>
                            </div>
                        </form>
                    </div>

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
