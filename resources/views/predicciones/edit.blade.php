@extends('layouts.app')

@section('title', 'Editar Predicción')

@push('styles')
<style>
    #timer {
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 18px;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        font-weight: bold;
        z-index: 1000;
        border: 2px solid rgba(255,255,255,0.2);
    }
    
    .modal-timer {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        margin: 15px 0;
        color: #0d6efd;
    }

    /* Estilos profesionales para el encabezado */
    .main-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 25px 30px;
        margin-bottom: 0;
        position: relative;
        overflow: hidden;
    }

    .main-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .main-header h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .main-header .header-icon {
        font-size: 2.2rem;
        margin-right: 15px;
        opacity: 0.9;
    }

    .main-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .main-card .card-body {
        padding: 30px;
        background: #fafbfc;
    }

    /* Estilos para secciones */
    .section-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .section-title {
        color: #495057;
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 2px solid #e9ecef;
    }

    .section-title i {
        font-size: 1.5rem;
        margin-right: 12px;
        color: #667eea;
    }

    /* Estilos mejorados para alertas */
    .alert {
        border-radius: 10px;
        border: none;
        padding: 15px 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }

    /* Estilos mejorados para campos de entrada */
    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        background: white;
        transform: translateY(-1px);
    }

    .form-control:hover:not(:focus) {
        border-color: #ced4da;
        background: white;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        color: #667eea;
        margin-right: 6px;
        font-size: 1.1rem;
    }

    /* Estilos para botones mejorados */
    .btn {
        border-radius: 8px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
    }

    /* Estilos para archivos adjuntos */
    .card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .card:hover {
        border-color: #667eea;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    /* Estilos para textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    /* Estilos para campos readonly */
    .form-control[readonly] {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #6c757d;
    }

    /* Animaciones suaves */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-card {
        animation: fadeInUp 0.6s ease-out;
    }

    .section-card:nth-child(1) { animation-delay: 0.1s; }
    .section-card:nth-child(2) { animation-delay: 0.2s; }
    .section-card:nth-child(3) { animation-delay: 0.3s; }

    /* Estilos para el spinner de carga */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }

    /* Mejoras para dispositivos móviles */
    @media (max-width: 768px) {
        .main-header {
            padding: 20px 15px;
        }
        
        .main-header h3 {
            font-size: 1.5rem;
        }
        
        .section-card {
            padding: 20px 15px;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
        
        .btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }

    /* Estilos para campos de validación */
    .is-invalid {
        border-color: #dc3545;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 5px;
    }

    /* Estilos para el texto de ayuda */
    .form-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 5px;
    }

    .form-text i {
        color: #667eea;
    }

</style>
@endpush

@section('content')
<div id="timer" class="d-none">⏱️ <span id="time-display">00:00:00</span></div>
<div class="container mt-4">
    <div class="main-card card">
        <div class="main-header card-header">
            <h3 class="mb-0">
                <i class="bx bx-edit-alt header-icon"></i>
                Editar Predicción de Diabetes
                <small class="ms-3" style="font-size: 0.9rem; opacity: 0.8;">(ID: {{ $prediccion->idprediccion }})</small>
            </h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <div id="server-validation-errors">
                {{-- Mostrar errores de validación del backend --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- FORMULARIO PRINCIPAL --}}
            <form id="predictionForm" action="{{ route('predicciones.process_edited_prediction', $prediccion->idprediccion) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">

                {{-- SECCIÓN: INFORMACIÓN DE LA CITA --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="bx bx-calendar-check"></i>
                        Información de la Cita Médica
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="idcita" class="form-label">
                                    <i class="bx bx-id-card me-1"></i>ID Cita
                                </label>
                                <input type="text" class="form-control" id="idcita" name="idcita"
                                       value="{{ $prediccion->cita->idcita }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paciente" class="form-label">
                                    <i class="bx bx-user me-1"></i>Paciente
                                </label>
                                <input type="text" class="form-control" id="paciente"
                                       value="{{ $prediccion->cita->paciente->nombre }} {{ $prediccion->cita->paciente->apellido }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: DATOS CLÍNICOS DEL PACIENTE --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="bx bx-health"></i>
                        Datos Clínicos del Paciente
                    </h5>
                    
                    {{-- Fila 1: Embarazos, Glucosa, Presión --}}
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="embarazos" class="form-label">
                                <i class="bx bx-plus-medical me-1"></i>Embarazos
                            </label>
                            <input type="number" name="embarazos" id="embarazos"
                                   class="form-control @error('embarazos') is-invalid @enderror"
                                   value="{{ old('embarazos', $prediccion->embarazos) }}" required min="0">
                            @error('embarazos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="glucosa" class="form-label">
                                <i class="bx bx-test-tube me-1"></i>Glucosa (mg/dL)
                            </label>
                            <input type="number" step="0.01" name="glucosa" id="glucosa"
                                   class="form-control @error('glucosa') is-invalid @enderror"
                                   value="{{ old('glucosa', $prediccion->glucosa) }}" required min="0">
                            @error('glucosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="presion_sanguinea" class="form-label">
                                <i class="bx bx-heart me-1"></i>Presión Sanguínea (mmHg)
                            </label>
                            <input type="number" step="0.01" name="presion_sanguinea" id="presion_sanguinea"
                                   class="form-control @error('presion_sanguinea') is-invalid @enderror"
                                   value="{{ old('presion_sanguinea', $prediccion->presion_sanguinea) }}" required min="0">
                            @error('presion_sanguinea')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Fila 2: Grosor de Piel, Insulina, BMI --}}
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="grosor_piel" class="form-label">
                                <i class="bx bx-ruler me-1"></i>Grosor de Piel (mm)
                            </label>
                            <input type="number" step="0.01" name="grosor_piel" id="grosor_piel"
                                   class="form-control @error('grosor_piel') is-invalid @enderror"
                                   value="{{ old('grosor_piel', $prediccion->grosor_piel) }}" required min="0">
                            @error('grosor_piel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="insulina" class="form-label">
                                <i class="bx bx-injection me-1"></i>Insulina (mu U/ml)
                            </label>
                            <input type="number" step="0.01" name="insulina" id="insulina"
                                   class="form-control @error('insulina') is-invalid @enderror"
                                   value="{{ old('insulina', $prediccion->insulina) }}" required min="0">
                            @error('insulina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="BMI" class="form-label">
                                <i class="bx bx-body me-1"></i>BMI (kg/m²)
                            </label>
                            <input type="number" step="0.01" name="BMI" id="BMI"
                                   class="form-control @error('BMI') is-invalid @enderror"
                                   value="{{ old('BMI', $prediccion->BMI) }}" required min="0">
                            @error('BMI')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Fila 3: Pedigree, Edad --}}
                    <div class="row">
                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="pedigree" class="form-label">
                                <i class="bx bx-dna me-1"></i>Función Pedigree de Diabetes
                            </label>
                            <input type="number" step="0.001" name="pedigree" id="pedigree"
                                   class="form-control @error('pedigree') is-invalid @enderror"
                                   value="{{ number_format(old('pedigree', $prediccion->pedigree), 3, '.', '') }}" required min="0">
                            @error('pedigree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 mb-3">
                            <label for="edad" class="form-label">
                                <i class="bx bx-time me-1"></i>Edad (años)
                            </label>
                            <input type="number" name="edad" id="edad"
                                   class="form-control @error('edad') is-invalid @enderror"
                                   value="{{ old('edad', $prediccion->edad) }}" required min="0">
                            @error('edad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: DOCUMENTOS Y OBSERVACIONES --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="bx bx-file-plus"></i>
                        Documentos y Observaciones
                    </h5>
                    
                    {{-- Observación --}}
                    <div class="mb-4">
                        <label for="observacion" class="form-label">
                            <i class="bx bx-note me-1"></i>Observación Médica (Opcional)
                        </label>
                        <textarea name="observacion" id="observacion"
                                  class="form-control @error('observacion') is-invalid @enderror"
                                  rows="3" placeholder="Ingrese observaciones adicionales sobre el paciente...">{{ old('observacion', $prediccion->observacion) }}</textarea>
                        @error('observacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Archivos Adjuntos --}}
                    <div class="mb-3">
                        <h6 class="mb-3">
                            <i class="bx bx-paperclip me-1"></i>Archivos Adjuntos
                        </h6>
                        
                        {{-- Mostrar archivos existentes --}}
                        @if($prediccion->attachment_paths && count($prediccion->attachment_paths) > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Archivos Actuales:</small>
                                <div class="row" id="existing-attachments">
                                    @foreach($prediccion->attachment_paths as $index => $path)
                                        @php
                                            $fileName = $prediccion->attachment_names[$index] ?? 'Archivo ' . ($index + 1);
                                            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                            $iconClass = match($extension) {
                                                'pdf' => 'bx-file-pdf text-danger',
                                                'doc', 'docx' => 'bx-file-doc text-primary',
                                                'xls', 'xlsx' => 'bx-file text-success',
                                                'jpg', 'jpeg', 'png', 'gif' => 'bx-image text-info',
                                                default => 'bx-file text-secondary'
                                            };
                                        @endphp
                                        <div class="col-md-6 col-lg-4 mb-2" data-attachment-index="{{ $index }}">
                                            <div class="card h-100">
                                                <div class="card-body p-2 d-flex align-items-center">
                                                    <i class="bx {{ $iconClass }} me-2" style="font-size: 1.5rem;"></i>
                                                    <div class="flex-grow-1 me-2">
                                                        <small class="text-muted d-block">{{ $fileName }}</small>
                                                        <small class="text-muted">.{{ $extension }}</small>
                                                    </div>
                                                    <div class="btn-group-vertical btn-group-sm">
                                                        <a href="{{ route('predicciones.downloadAttachment', ['id' => $prediccion->idprediccion, 'index' => $index]) }}" 
                                                           class="btn btn-outline-primary btn-sm" title="Descargar">
                                                            <i class="bx bx-download"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                                onclick="removeExistingAttachment({{ $index }})" title="Eliminar">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Subir nuevos archivos --}}
                        <div class="mb-3">
                            <label for="attachments" class="form-label">
                                <i class="bx bx-cloud-upload me-1"></i>Agregar Nuevos Archivos
                            </label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" 
                                   multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif">
                            <div class="form-text">
                                <i class="bx bx-info-circle me-1"></i>
                                Formatos: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG, GIF. Máximo 5MB por archivo.
                            </div>
                        </div>

                        {{-- Vista previa de nuevos archivos --}}
                        <div id="new-attachments-preview" class="row" style="display: none;">
                            <div class="col-12">
                                <small class="text-muted d-block mb-2">Nuevos Archivos a Subir:</small>
                                <div id="new-attachments-list" class="row"></div>
                            </div>
                        </div>

                        {{-- Campos ocultos para manejar archivos eliminados --}}
                        <input type="hidden" id="removed-attachments" name="removed_attachments" value="">
                    </div>
                </div>

                {{-- Campos ocultos para los resultados de la predicción --}}
                <input type="hidden" name="probability_diabetes" id="save_probability_diabetes">
                <input type="hidden" name="prediction_label" id="save_prediction_label">
                <input type="hidden" name="diagnosis" id="save_diagnosis">
                <input type="hidden" name="timer" id="save_timer">
                {{-- NUEVO CAMPO OCULTO --}}
                <input type="hidden" name="timer_duration_ms" id="save_timer_ms">

<div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('predicciones.index') }}" class="btn btn-secondary" id="cancelBtn">Cancelar</a>
                            <button type="submit" id="mainBtn" class="btn btn-primary">
                                <span id="mainBtnText">Re-Predecir</span>
                                <span id="mainSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="mainLoadingText" class="d-none">Cargando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            {{-- SECCIÓN PARA MOSTRAR EL RESULTADO DE LA PREDICCIÓN (inicialmente oculta) --}}
            <div id="predictionResultSection" class="mt-4" style="display: none;">
                <h4 class="mb-3">Resultado de la Predicción Actualizada:</h4>
                <div id="predictionResultContent">
                    {{-- Aquí se inyectará el resultado via JS --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Elementos DOM ---
        const form = document.getElementById('predictionForm');
        const mainBtn = document.getElementById('mainBtn');
        const mainBtnText = document.getElementById('mainBtnText');
        const mainSpinner = document.getElementById('mainSpinner');
        const mainLoadingText = document.getElementById('mainLoadingText');
        const formMethod = document.getElementById('formMethod');
        const cancelBtn = document.getElementById('cancelBtn');
        
        const predictionResultSection = document.getElementById('predictionResultSection');
        const predictionResultContent = document.getElementById('predictionResultContent');
        
        const serverValidationErrors = document.getElementById('server-validation-errors');

        // --- Lógica del Temporizador ---
        const startTime = new Date();
        let timerInterval;
        const timerElement = document.getElementById('timer');
        const timeDisplay = document.getElementById('time-display');
        
        timerElement.classList.remove('d-none');
        
        function updateTimer() {
            const now = new Date();
            const elapsed = now - startTime;
            const totalSeconds = Math.floor(elapsed / 1000);
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            const milliseconds = Math.floor((elapsed % 1000) / 10);
            
            timeDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0') + ':' +
                String(milliseconds).padStart(2, '0');
        }
        
        timerInterval = setInterval(updateTimer, 10);
        updateTimer();

        // --- Lógica de envío del formulario ---
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Ocultar errores previos
            serverValidationErrors.innerHTML = '';
            
            // Mostrar spinner y deshabilitar botón
            mainBtn.disabled = true;
            mainBtnText.classList.add('d-none');
            mainSpinner.classList.remove('d-none');
            mainLoadingText.classList.remove('d-none');
            predictionResultSection.style.display = 'none';

            // Recoger datos del formulario
            const formData = new FormData(form);
            
            // --- NUEVO CÓDIGO para capturar el tiempo en milisegundos ---
            const now = new Date();
            const elapsed_ms = now - startTime;
            formData.append('timer_duration_ms', elapsed_ms);
            document.getElementById('save_timer_ms').value = elapsed_ms;
            // -------------------------------------------------------------
            
            try {
                // Determinar la acción y el método HTTP
                let endpoint = form.action;
                let method = 'POST';

                if (formMethod.value === 'PUT') {
                    method = 'PUT';
                    endpoint = `{{ route('predicciones.update_confirmed_prediction', $prediccion->idprediccion) }}`;
                }

                let response;
                if (formMethod.value === 'POST') {
                    response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                } else {
                    // Para PUT, necesitamos enviar como JSON
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        if (key !== '_method') {
                            data[key] = value;
                        }
                    }
                    
                    response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({...data, _method: 'PUT'})
                    });
                }
                
                // Comprueba si la respuesta no es de tipo JSON
                const contentType = response.headers.get("content-type");
                if (contentType && !contentType.includes("application/json")) {
                    const errorText = await response.text();
                    console.error('El servidor devolvió contenido no-JSON:', errorText);
                    throw new Error(`Respuesta inesperada del servidor (se esperaba JSON). Estado: ${response.status}. Por favor, revise la consola para ver la respuesta completa.`);
                }
                
                const result = await response.json();

                if (response.status === 422) {
                    let errorsHtml = '<div class="alert alert-danger mb-0"><ul>';
                    for (let key in result.errors) {
                        result.errors[key].forEach(error => {
                            errorsHtml += `<li>${error}</li>`;
                        });
                    }
                    errorsHtml += '</ul></div>';
                    serverValidationErrors.innerHTML = errorsHtml;
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.error || 'Error en la operación.');
                }

                if (formMethod.value === 'POST') {
                    // Lógica para mostrar el resultado de la predicción
                    clearInterval(timerInterval);
                    const elapsedTime = timeDisplay.textContent;
                    const timerModal = new bootstrap.Modal(document.getElementById('timerModal'));
                    document.getElementById('elapsed-time').textContent = elapsedTime;
                    timerModal.show();
                    
                    const prediction = result.predictionResult.prediction;
                    const diagnosis = result.predictionResult.diagnosis;
                    const probNoDiabetes = (result.predictionResult.probability_no_diabetes * 100).toFixed(2);
                    const probDiabetes = (result.predictionResult.probability_diabetes * 100).toFixed(2);

                    let alertClass = prediction === 1 ? 'alert-danger' : 'alert-success';

                    predictionResultContent.innerHTML = `
                        <div class="alert ${alertClass}" role="alert">
                            <p class="h5">Diagnóstico: <strong>${diagnosis}</strong></p>
                            <p>Probabilidad de NO Diabetes: <strong>${probNoDiabetes}%</strong></p>
                            <p>Probabilidad de SÍ Diabetes: <strong>${probDiabetes}%</strong></p>
                        </div>
                    `;
                    predictionResultSection.style.display = 'block';

                    // Llenar campos ocultos para el guardado
                    document.getElementById('save_probability_diabetes').value = result.predictionResult.probability_diabetes;
                    document.getElementById('save_prediction_label').value = prediction;
                    document.getElementById('save_diagnosis').value = diagnosis;
                    document.getElementById('save_timer').value = elapsedTime;
                    
                    // Cambiar el formulario para el guardado
                    mainBtnText.textContent = 'Guardar Cambios';
                    mainBtn.classList.remove('btn-primary');
                    mainBtn.classList.add('btn-success');
                    formMethod.value = 'PUT';
                    cancelBtn.textContent = 'Volver a Editar';
                    cancelBtn.href = '#'; // Evitar la navegación
                    cancelBtn.onclick = () => window.location.reload(); // Recargar la página para editar de nuevo
                
                } else if (formMethod.value === 'PUT') {
                    // Lógica para el guardado exitoso
                    // Redirigir o mostrar un mensaje de éxito
                    window.location.href = result.redirect || "{{ route('predicciones.index') }}";
                }

            } catch (error) {
                console.error('Error:', error);
                predictionResultContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Ocurrió un error al realizar la operación: ${error.message}. Por favor, revise la consola para más detalles.
                    </div>
                `;
                predictionResultSection.style.display = 'block';
            } finally {
                // Ocultar spinner y habilitar botón
                mainBtn.disabled = false;
                mainBtnText.classList.remove('d-none');
                mainSpinner.classList.add('d-none');
                mainLoadingText.classList.add('d-none');
            }
        });
    });

    // --- JavaScript para gestión de archivos adjuntos ---
    let removedAttachments = [];

    // Función para eliminar archivos existentes
    function removeExistingAttachment(index) {
        if (confirm('¿Está seguro de que desea eliminar este archivo?')) {
            // Agregar el índice a la lista de archivos eliminados
            removedAttachments.push(index);
            document.getElementById('removed-attachments').value = JSON.stringify(removedAttachments);
            
            // Ocultar visualmente el archivo
            const attachmentElement = document.querySelector(`[data-attachment-index="${index}"]`);
            if (attachmentElement) {
                attachmentElement.style.display = 'none';
            }
        }
    }

    // Función para mostrar vista previa de nuevos archivos
    document.getElementById('attachments').addEventListener('change', function(e) {
        const files = e.target.files;
        const previewContainer = document.getElementById('new-attachments-preview');
        const listContainer = document.getElementById('new-attachments-list');
        
        if (files.length > 0) {
            listContainer.innerHTML = '';
            
            Array.from(files).forEach((file, index) => {
                const extension = file.name.split('.').pop().toLowerCase();
                const iconClass = getFileIconClass(extension);
                
                const fileElement = document.createElement('div');
                fileElement.className = 'col-md-6 col-lg-4 mb-2';
                fileElement.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body p-2 d-flex align-items-center">
                            <i class="bx ${iconClass} me-2" style="font-size: 1.5rem;"></i>
                            <div class="flex-grow-1 me-2">
                                <small class="text-muted d-block">${file.name}</small>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    onclick="removeNewAttachment(${index})" title="Eliminar">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                listContainer.appendChild(fileElement);
            });
            
            previewContainer.style.display = 'block';
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Función para obtener la clase de icono según la extensión
    function getFileIconClass(extension) {
        switch(extension) {
            case 'pdf': return 'bx-file-pdf text-danger';
            case 'doc':
            case 'docx': return 'bx-file-doc text-primary';
            case 'xls':
            case 'xlsx': return 'bx-file text-success';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif': return 'bx-image text-info';
            default: return 'bx-file text-secondary';
        }
    }

    // Función para eliminar archivos nuevos de la vista previa
    function removeNewAttachment(index) {
        const fileInput = document.getElementById('attachments');
        const dt = new DataTransfer();
        
        Array.from(fileInput.files).forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        
        // Disparar el evento change para actualizar la vista previa
        fileInput.dispatchEvent(new Event('change'));
    }
</script>


<div class="modal fade" id="timerModal" tabindex="-1" aria-labelledby="timerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timerModalLabel">Tiempo de Análisis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body text-center">
                <p>¡Análisis completado en:</p>
                <div class="modal-timer" id="elapsed-time">00:00:00</div>
                <p>Gracias por utilizar nuestro sistema de predicción.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
