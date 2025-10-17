@extends('layouts.app')

@section('title', 'Realizar Análisis')

@push('styles')
<style>
    /* Timer styles */
    #timer {
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        border-radius: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        font-weight: 600;
        z-index: 1000;
        font-size: 0.9rem;
        border: 2px solid rgba(255,255,255,0.2);
    }
    
    .modal-timer {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        margin: 15px 0;
        color: #0d6efd;
    }

    /* Professional header styles */
    .professional-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 15px 15px 0 0;
        padding: 25px;
        position: relative;
        overflow: hidden;
    }

    .professional-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .professional-header h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .professional-header .header-icon {
        background: rgba(255,255,255,0.2);
        padding: 12px;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .professional-header .subtitle {
        margin-top: 8px;
        opacity: 0.9;
        font-size: 1rem;
        font-weight: 400;
        position: relative;
        z-index: 1;
    }

    /* Card improvements */
    .main-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .section-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .section-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .section-title {
        color: #495057;
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-icon {
        color: #667eea;
        font-size: 1.2rem;
    }

    .form-label {
        color: #495057;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-select {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-gradient-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-gradient-info {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .btn-gradient-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
        color: white;
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
    }

    .result-section {
        margin-top: 2rem;
        animation: fadeInUp 0.5s ease;
    }

    .result-card {
        border-left: 4px solid #667eea;
    }

    .result-content {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        min-height: 100px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert {
        border: none;
        border-radius: 10px;
        padding: 1rem 1.5rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        color: white;
    }

    .alert-warning {
        background: linear-gradient(135deg, #feca57 0%, #ff9ff3 100%);
        color: white;
    }

    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div id="timer" class="d-none">Tiempo: <span id="time-display">00:00:00</span></div>
<div class="container mt-4">
    <div class="card main-card">
        <div class="card-header professional-header">
            <h3 class="mb-0">
                <span class="header-icon">
                    <i class="fas fa-brain"></i>
                </span>
                Análisis Inteligente de Diabetes
            </h3>
            <div class="subtitle">Sistema de predicción con Machine Learning e Inteligencia Artificial</div>
        </div>
        <div class="card-body p-4">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Mostrar errores de validación del backend --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- FORMULARIO PRINCIPAL PARA LA PREDICCIÓN --}}
            <form id="predictionForm" action="{{ route('predicciones.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- SECCIÓN DE SELECCIÓN DE CITA --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="fas fa-calendar-check section-icon"></i>
                        Selección de Cita Médica
                    </h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="idcita" class="form-label fw-semibold">
                                    <i class="fas fa-user-md me-2 text-primary"></i>
                                    Cita Médica
                                </label>
                                <select name="idcita" id="idcita" class="form-select @error('idcita') is-invalid @enderror" 
                                        onchange="cargarDatosTriaje(this.value)" required>
                                    @if(isset($cita) && $cita)
                                        <option value="{{ $cita->idcita }}" selected>
                                            {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }} - 
                                            {{ $cita->fecha_cita }} {{ date('H:i', strtotime($cita->hora_cita)) }}
                                        </option>
                                    @else
                                        <option value="">Selecciona una cita</option>
                                        @foreach($citas as $c)
                                            @if($c->triaje)
                                                <option value="{{ $c->idcita }}" 
                                                        {{ old('idcita') == $c->idcita ? 'selected' : '' }}>
                                                    {{ $c->paciente->nombre }} {{ $c->paciente->apellido }} - 
                                                    {{ $c->fecha_cita }} {{ date('H:i', strtotime($c->hora_cita)) }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('idcita')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="infoCita" class="mt-3 card bg-light border-0 shadow-sm" style="display: none;">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Información del Paciente y Cita
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong><i class="fas fa-user me-2 text-muted"></i>Paciente:</strong> <span id="infoPacienteNombre">-</span></p>
                                            <p class="mb-2"><strong><i class="fas fa-id-card me-2 text-muted"></i>DNI:</strong> <span id="infoPacienteDNI">-</span></p>
                                            <p class="mb-2"><strong><i class="fas fa-venus-mars me-2 text-muted"></i>Sexo:</strong> <span id="infoPacienteSexo">-</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong><i class="fas fa-phone me-2 text-muted"></i>Teléfono:</strong> <span id="infoPacienteTelefono">-</span></p>
                                            <p class="mb-2"><strong><i class="fas fa-calendar me-2 text-muted"></i>Fecha Cita:</strong> <span id="infoCitaFecha">-</span></p>
                                            <p class="mb-2"><strong><i class="fas fa-clock me-2 text-muted"></i>Hora Cita:</strong> <span id="infoCitaHora">-</span></p>
                                        </div>
                                    </div>
                                    <div id="alertaSinTriaje" class="alert alert-warning mt-3" style="display: none;">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        La cita seleccionada no tiene un triaje asociado. Por favor, ingrese los datos manualmente.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN DE DATOS CLÍNICOS --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="fas fa-heartbeat section-icon"></i>
                        Datos Clínicos del Paciente
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="embarazos" class="form-label fw-semibold">
                                    <i class="fas fa-baby me-2 text-info"></i>
                                    Número de Embarazos
                                </label>
                                <input type="number" name="embarazos" id="embarazos" 
                                        class="form-control @error('embarazos') is-invalid @enderror" 
                                        value="{{ old('embarazos', $cita->triaje->embarazos ?? '') }}" required min="0" max="20">
                                @error('embarazos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="glucosa" class="form-label fw-semibold">
                                    <i class="fas fa-tint me-2 text-danger"></i>
                                    Glucosa (mg/dL)
                                </label>
                                <input type="number" step="0.01" name="glucosa" id="glucosa" 
                                        class="form-control @error('glucosa') is-invalid @enderror" 
                                        value="{{ old('glucosa', $cita->triaje->glucosa ?? '') }}" required min="0" max="300">
                                @error('glucosa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="presion_sanguinea" class="form-label fw-semibold">
                                    <i class="fas fa-heart me-2 text-danger"></i>
                                    Presión Sanguínea (mmHg)
                                </label>
                                <input type="number" step="0.01" name="presion_sanguinea" id="presion_sanguinea" 
                                        class="form-control @error('presion_sanguinea') is-invalid @enderror" 
                                        value="{{ old('presion_sanguinea', $cita->triaje->presion_sanguinea ?? '') }}" required min="0" max="200">
                                @error('presion_sanguinea')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="grosor_piel" class="form-label fw-semibold">
                                    <i class="fas fa-ruler me-2 text-warning"></i>
                                    Grosor de Piel (mm)
                                </label>
                                <input type="number" step="0.01" name="grosor_piel" id="grosor_piel" 
                                        class="form-control @error('grosor_piel') is-invalid @enderror" 
                                        value="{{ old('grosor_piel', $cita->triaje->grosor_piel ?? '') }}" required min="0" max="100">
                                @error('grosor_piel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="insulina" class="form-label fw-semibold">
                                    <i class="fas fa-syringe me-2 text-success"></i>
                                    Insulina (μU/mL)
                                </label>
                                <input type="number" step="0.01" name="insulina" id="insulina" 
                                        class="form-control @error('insulina') is-invalid @enderror" 
                                        value="{{ old('insulina', $cita->triaje->insulina ?? '') }}" required min="0" max="1000">
                                @error('insulina')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="BMI" class="form-label fw-semibold">
                                    <i class="fas fa-weight me-2 text-primary"></i>
                                    Índice de Masa Corporal (BMI)
                                </label>
                                <input type="number" step="0.01" name="BMI" id="BMI" 
                                        class="form-control @error('BMI') is-invalid @enderror" 
                                        value="{{ old('BMI', $cita->triaje->BMI ?? '') }}" required min="0" max="100">
                                @error('BMI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="pedigree" class="form-label fw-semibold">
                                    <i class="fas fa-dna me-2 text-info"></i>
                                    Función Pedigree de Diabetes
                                </label>
                                <input type="number" step="0.001" name="pedigree" id="pedigree" 
                                        class="form-control @error('pedigree') is-invalid @enderror" 
                                        value="{{ old('pedigree', $cita->triaje->pedigree ?? '') }}" required min="0" max="5">
                                @error('pedigree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edad" class="form-label fw-semibold">
                                    <i class="fas fa-birthday-cake me-2 text-secondary"></i>
                                    Edad (años)
                                </label>
                                <input type="number" name="edad" id="edad" 
                                        class="form-control @error('edad') is-invalid @enderror" 
                                        value="{{ old('edad', $cita->triaje->edad ?? '') }}" required min="0" max="120">
                                @error('edad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN DE DOCUMENTOS Y OBSERVACIONES --}}
                <div class="section-card">
                    <h5 class="section-title">
                        <i class="fas fa-file-medical section-icon"></i>
                        Documentos y Observaciones
                    </h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="documentos_adjuntos" class="form-label fw-semibold">
                                    <i class="fas fa-paperclip me-2 text-primary"></i>
                                    Documentos Adjuntos
                                </label>
                                <input type="file" name="documentos_adjuntos[]" id="documentos_adjuntos" 
                                        class="form-control @error('documentos_adjuntos') is-invalid @enderror" 
                                        multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Puedes subir múltiples archivos (PDF, DOC, DOCX, JPG, PNG). Máximo 10MB por archivo.
                                </div>
                                @error('documentos_adjuntos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="observacion" class="form-label fw-semibold">
                                    <i class="fas fa-notes-medical me-2 text-secondary"></i>
                                    Observaciones Médicas
                                </label>
                                <textarea name="observacion" id="observacion" 
                                          class="form-control @error('observacion') is-invalid @enderror" 
                                          rows="4" placeholder="Ingrese observaciones adicionales, síntomas relevantes o notas médicas...">{{ old('observacion') }}</textarea>
                                @error('observacion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN DE ACCIONES --}}
                <div class="section-card">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('predicciones.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Cancelar
                                </a>
                                <div class="d-flex gap-3">
                                    <button type="button" id="analyzeAiBtn" class="btn btn-gradient-info btn-lg px-4">
                                        <i class="fas fa-brain me-2"></i>
                                        <span id="analyzeAiBtnText">Analizar con IA</span>
                                        <span id="analyzeAiSpinner" class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                        <span id="analyzeAiLoadingText" class="d-none">Analizando...</span>
                                    </button>
                                    <button type="submit" id="predictBtn" class="btn btn-gradient-primary btn-lg px-4">
                                        <i class="fas fa-chart-line me-2"></i>
                                        <span id="predictBtnText">Realizar Predicción</span>
                                        <span id="predictSpinner" class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                                        <span id="predictLoadingText" class="d-none">Procesando...</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            {{-- SECCIÓN PARA MOSTRAR EL RESULTADO DEL ANÁLISIS DE IA --}}
            <div id="aiAnalysisSection" class="result-section" style="display: none;">
                <div class="section-card result-card">
                    <h5 class="section-title">
                        <i class="fas fa-robot section-icon text-info"></i>
                        Análisis con Inteligencia Artificial
                    </h5>
                    <div id="aiAnalysisContent" class="result-content">
                        {{-- Aquí se inyectará el resultado del análisis de IA via JS --}}
                    </div>
                </div>
            </div>

            {{-- SECCIÓN PARA MOSTRAR EL RESULTADO DE LA PREDICCIÓN --}}
            <div id="predictionResultSection" class="result-section" style="display: none;">
                <div class="section-card result-card">
                    <h5 class="section-title">
                        <i class="fas fa-chart-bar section-icon text-success"></i>
                        Resultado de la Predicción
                    </h5>
                    <div id="predictionResultContent" class="result-content">
                        {{-- Aquí se inyectará el resultado via JS --}}
                    </div>
                </div>
            </div>

                {{-- FORMULARIO PARA GUARDAR LA PREDICCIÓN (inicialmente oculto) --}}
                <form id="savePredictionForm" action="{{ route('predicciones.save_confirmed_prediction') }}" method="POST" style="display: none;">
                    @csrf
                    {{-- Hidden inputs para los datos originales --}}
                    <input type="hidden" name="idcita" id="save_idcita">
                    <input type="hidden" name="embarazos" id="save_embarazos">
                    <input type="hidden" name="glucosa" id="save_glucosa">
                    <input type="hidden" name="presion_sanguinea" id="save_presion_sanguinea">
                    <input type="hidden" name="grosor_piel" id="save_grosor_piel">
                    <input type="hidden" name="insulina" id="save_insulina">
                    <input type="hidden" name="BMI" id="save_BMI">
                    <input type="hidden" name="pedigree" id="save_pedigree">
                    <input type="hidden" name="edad" id="save_edad">
                    <input type="hidden" name="observacion" id="save_observacion">
                    <input type="hidden" name="timer" id="save_timer">
                    <input type="hidden" name="timer_duration_ms" id="save_timer_duration_ms">
                    
                    {{-- Hidden inputs para los resultados de la predicción --}}
                    <input type="hidden" name="probability_diabetes" id="save_probability_diabetes">
                    <input type="hidden" name="prediction_label" id="save_prediction_label">
                    <input type="hidden" name="diagnosis" id="save_diagnosis">

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">Guardar Predicción</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Variable global para almacenar todas las citas con sus relaciones (triaje, paciente)
    const allCitasData = <?php echo json_encode($citas ?? []); ?>;

    function cargarDatosTriaje(idCita) {
        const infoCitaDiv = document.getElementById('infoCita');
        const alertaSinTriaje = document.getElementById('alertaSinTriaje');

        if (!idCita) {
            infoCitaDiv.style.display = 'none';
            alertaSinTriaje.style.display = 'none';
            // Limpiar todos los campos de predicción
            document.getElementById('embarazos').value = '';
            document.getElementById('glucosa').value = '';
            document.getElementById('presion_sanguinea').value = '';
            document.getElementById('grosor_piel').value = '';
            document.getElementById('insulina').value = '';
            document.getElementById('BMI').value = '';
            document.getElementById('pedigree').value = '';
            document.getElementById('edad').value = '';
            document.getElementById('observacion').value = ''; // También limpiar observación
            return;
        }

        const citaSeleccionada = allCitasData.find(cita => cita.idcita == idCita);

        if (citaSeleccionada) {
            infoCitaDiv.style.display = 'block';

            document.getElementById('infoPacienteNombre').textContent = `${citaSeleccionada.paciente.nombre || ''} ${citaSeleccionada.paciente.apellido || ''}`;
            document.getElementById('infoPacienteDNI').textContent = citaSeleccionada.paciente.DNI || '-';
            document.getElementById('infoPacienteSexo').textContent = citaSeleccionada.paciente.sexo || '-';
            document.getElementById('infoPacienteTelefono').textContent = citaSeleccionada.paciente.telefono || '-';
            document.getElementById('infoCitaFecha').textContent = citaSeleccionada.fecha_cita || '-';
            document.getElementById('infoCitaHora').textContent = citaSeleccionada.hora_cita ? citaSeleccionada.hora_cita.substring(0, 5) : '-'; 

            if (citaSeleccionada.triaje) {
                alertaSinTriaje.style.display = 'none';
                document.getElementById('embarazos').value = citaSeleccionada.triaje.embarazos || '';
                document.getElementById('glucosa').value = citaSeleccionada.triaje.glucosa || '';
                document.getElementById('presion_sanguinea').value = citaSeleccionada.triaje.presion_sanguinea || '';
                document.getElementById('grosor_piel').value = citaSeleccionada.triaje.grosor_piel || '';
                document.getElementById('insulina').value = citaSeleccionada.triaje.insulina || '';
                document.getElementById('BMI').value = citaSeleccionada.triaje.BMI || '';
                document.getElementById('pedigree').value = citaSeleccionada.triaje.pedigree || '';
                document.getElementById('edad').value = citaSeleccionada.triaje.edad || '';
            } else {
                alertaSinTriaje.style.display = 'block';
                // Limpiar campos si no hay triaje
                document.getElementById('embarazos').value = '';
                document.getElementById('glucosa').value = '';
                document.getElementById('presion_sanguinea').value = '';
                document.getElementById('grosor_piel').value = '';
                document.getElementById('insulina').value = '';
                document.getElementById('BMI').value = '';
                document.getElementById('pedigree').value = '';
                document.getElementById('edad').value = '';
            }
        } else {
            infoCitaDiv.style.display = 'none';
            alertaSinTriaje.style.display = 'none';
            // Limpiar todos los campos de predicción si la cita no se encuentra
            document.getElementById('embarazos').value = '';
            document.getElementById('glucosa').value = '';
            document.getElementById('presion_sanguinea').value = '';
            document.getElementById('grosor_piel').value = '';
            document.getElementById('insulina').value = '';
            document.getElementById('BMI').value = '';
            document.getElementById('pedigree').value = '';
            document.getElementById('edad').value = '';
            document.getElementById('observacion').value = '';
        }
        // Ocultar sección de resultado y botón de guardar al cambiar la cita
        document.getElementById('predictionResultSection').style.display = 'none';
        document.getElementById('savePredictionForm').style.display = 'none';
    }

    // Inicializar al cargar la página si ya hay una cita seleccionada (ej. por URL)
    document.addEventListener('DOMContentLoaded', function() {
        const selectCita = document.getElementById('idcita');
        if (selectCita.value) {
            cargarDatosTriaje(selectCita.value);
        }

        // --- Lógica AJAX para la predicción ---
        const predictionForm = document.getElementById('predictionForm');
        const predictBtn = document.getElementById('predictBtn');
        const predictBtnText = document.getElementById('predictBtnText');
        const predictSpinner = document.getElementById('predictSpinner');
        const predictLoadingText = document.getElementById('predictLoadingText');
        const predictionResultSection = document.getElementById('predictionResultSection');
        const predictionResultContent = document.getElementById('predictionResultContent');
        const savePredictionForm = document.getElementById('savePredictionForm');

        // Inicializar el temporizador cuando se carga la página
        let startTime = new Date();
        let timerInterval;
        const timerElement = document.getElementById('timer');
        const timeDisplay = document.getElementById('time-display');
        
        // Mostrar el temporizador
        timerElement.classList.remove('d-none');
        
        // Actualizar el temporizador cada 10 milisegundos para mayor precisión
        function updateTimer() {
            const now = new Date();
            const elapsed = now - startTime;
            const minutes = Math.floor(elapsed / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);
            const milliseconds = Math.floor((elapsed % 1000) / 10);
            
            timeDisplay.textContent = 
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0') + ':' +
                String(milliseconds).padStart(2, '0');
        }
        
        // Iniciar el temporizador con actualización cada 10ms
        timerInterval = setInterval(updateTimer, 10);
        updateTimer(); // Llamar inmediatamente para evitar retraso inicial

        // --- Lógica para el análisis con IA ---
        const analyzeAiBtn = document.getElementById('analyzeAiBtn');
        const analyzeAiBtnText = document.getElementById('analyzeAiBtnText');
        const analyzeAiSpinner = document.getElementById('analyzeAiSpinner');
        const analyzeAiLoadingText = document.getElementById('analyzeAiLoadingText');
        const aiAnalysisSection = document.getElementById('aiAnalysisSection');
        const aiAnalysisContent = document.getElementById('aiAnalysisContent');

        analyzeAiBtn.addEventListener('click', async function() {
            // Validar que se haya seleccionado una cita
            const idcita = document.getElementById('idcita').value;
            if (!idcita) {
                alert('Por favor, selecciona una cita antes de realizar el análisis con IA.');
                return;
            }

            // Mostrar spinner y deshabilitar botón
            analyzeAiBtn.disabled = true;
            analyzeAiBtnText.classList.add('d-none');
            analyzeAiSpinner.classList.remove('d-none');
            analyzeAiLoadingText.classList.remove('d-none');
            
            aiAnalysisSection.style.display = 'none'; // Ocultar análisis anterior

            // Recopilar datos del formulario incluyendo archivos adjuntos
            const formData = new FormData(predictionForm);

            try {
                const response = await fetch('{{ route("predicciones.analyze_gemini") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                        // No incluir Content-Type para que el navegador lo configure automáticamente para multipart/form-data
                    },
                    body: formData // Enviar FormData directamente para incluir archivos
                });

                const result = await response.json();

                // Manejar errores de validación
                if (response.status === 422) {
                    let errorsHtml = '<div class="alert alert-danger mb-0"><ul>';
                    for (let key in result.errors) {
                        result.errors[key].forEach(error => {
                            errorsHtml += `<li>${error}</li>`;
                        });
                    }
                    errorsHtml += '</ul></div>';
                    document.querySelector('.card-body').insertAdjacentHTML('afterbegin', errorsHtml);
                    return;
                }

                if (!response.ok) {
                    throw new Error(result.error || 'Error en el análisis con IA.');
                }
                
                // Mostrar el resultado del análisis de IA
                const analysis = result.analysis;
                const patientData = result.patient_data;
                
                // Formatear el análisis para mejor presentación
                const formattedAnalysis = analysis.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                                                  .replace(/\n/g, '<br>')
                                                  .replace(/(\d+\.\s)/g, '<br><strong>$1</strong>');

                let genderNote = '';
                if (patientData.sexo === 'Masculino' && patientData.embarazos_originales > 0) {
                    genderNote = `<div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Nota:</strong> Se detectó que el paciente es masculino, por lo que el número de embarazos se ajustó automáticamente de ${patientData.embarazos_originales} a ${patientData.embarazos_ajustados} para el análisis.
                    </div>`;
                }

                aiAnalysisContent.innerHTML = `
                    <div class="mb-3">
                        <h5 class="text-primary">
                            <i class="fas fa-user-md"></i> 
                            Análisis Médico para: ${patientData.nombre}
                        </h5>
                        <small class="text-muted">Sexo: ${patientData.sexo}</small>
                    </div>
                    ${genderNote}
                    <div class="analysis-content">
                        ${formattedAnalysis}
                    </div>
                    <div class="mt-3 text-muted">
                        <small><i class="fas fa-robot"></i> Análisis generado por Inteligencia Artificial Gemini</small>
                    </div>
                `;
                
                aiAnalysisSection.style.display = 'block';

            } catch (error) {
                console.error('Error:', error);
                aiAnalysisContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        Ocurrió un error al realizar el análisis con IA: ${error.message}. Por favor, revise los datos o inténtelo más tarde.
                    </div>
                `;
                aiAnalysisSection.style.display = 'block';
            } finally {
                // Ocultar spinner y habilitar botón
                analyzeAiBtn.disabled = false;
                analyzeAiBtnText.classList.remove('d-none');
                analyzeAiSpinner.classList.add('d-none');
                analyzeAiLoadingText.classList.add('d-none');
            }
        });

        predictionForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario
            
            // Detener el temporizador y obtener el tiempo transcurrido
            clearInterval(timerInterval);
            const elapsedTime = new Date() - startTime;
            const elapsedTimeString = timeDisplay.textContent;

            // Mostrar spinner y deshabilitar botón
            predictBtn.disabled = true;
            predictBtnText.classList.add('d-none');
            predictSpinner.classList.remove('d-none');
            predictLoadingText.classList.remove('d-none');
            
            predictionResultSection.style.display = 'none'; // Ocultar resultados anteriores
            savePredictionForm.style.display = 'none'; // Ocultar botón de guardar

            const formData = new FormData(predictionForm);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            try {
                const response = await fetch(predictionForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                // Manejar errores de validación de Laravel (si el backend devuelve 422)
                if (response.status === 422) {
                    let errorsHtml = '<div class="alert alert-danger mb-0"><ul>';
                    for (let key in result.errors) {
                        result.errors[key].forEach(error => {
                            errorsHtml += `<li>${error}</li>`;
                        });
                    }
                    errorsHtml += '</ul></div>';
                    // Inyectar errores al inicio del card body
                    document.querySelector('.card-body').insertAdjacentHTML('afterbegin', errorsHtml);
                    return; // Detener la ejecución
                }

                if (!response.ok) {
                    throw new Error(result.error || 'Error en la predicción.');
                }
                
                // Mostrar el resultado de la predicción
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
                savePredictionForm.style.display = 'block'; // Mostrar el botón de guardar

                // Llenar los campos ocultos del formulario de guardar
                document.getElementById('save_idcita').value = data.idcita;
                document.getElementById('save_embarazos').value = data.embarazos;
                document.getElementById('save_glucosa').value = data.glucosa;
                document.getElementById('save_presion_sanguinea').value = data.presion_sanguinea;
                document.getElementById('save_grosor_piel').value = data.grosor_piel;
                document.getElementById('save_insulina').value = data.insulina;
                document.getElementById('save_BMI').value = data.BMI;
                document.getElementById('save_pedigree').value = data.pedigree;
                document.getElementById('save_edad').value = data.edad;
                document.getElementById('save_observacion').value = data.observacion;
                
                // Asignar el tiempo de duración y el tiempo formateado
                document.getElementById('save_timer').value = elapsedTimeString;
                document.getElementById('save_timer_duration_ms').value = elapsedTime;
                
                document.getElementById('save_probability_diabetes').value = result.predictionResult.probability_diabetes;
                document.getElementById('save_prediction_label').value = result.predictionResult.prediction;
                document.getElementById('save_diagnosis').value = result.predictionResult.diagnosis;

            } catch (error) {
                console.error('Error:', error);
                predictionResultContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Ocurrió un error al realizar la predicción: ${error.message}. Por favor, revise los datos o inténtelo más tarde.
                    </div>
                `;
                predictionResultSection.style.display = 'block';
                savePredictionForm.style.display = 'none'; // No mostrar el botón de guardar si hay error
            } finally {
                // Ocultar spinner y habilitar botón
                predictBtn.disabled = false;
                predictBtnText.classList.remove('d-none');
                predictSpinner.classList.add('d-none');
                predictLoadingText.classList.add('d-none');
            }
        });
    });
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