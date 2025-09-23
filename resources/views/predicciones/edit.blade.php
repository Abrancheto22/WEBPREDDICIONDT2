@extends('layouts.app')

@section('title', 'Editar Predicción')

@push('styles')
<style>
    #timer {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        font-weight: bold;
        z-index: 1000;
    }
    .modal-timer {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        margin: 15px 0;
        color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div id="timer" class="d-none">Tiempo: <span id="time-display">00:00:00</span></div>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Editar Predicción (ID: {{ $prediccion->idprediccion }})</h3>
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

            <div id="server-validation-errors">
                {{-- Mostrar errores de validación del backend --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- FORMULARIO PRINCIPAL --}}
            <form id="predictionForm" action="{{ route('predicciones.process_edited_prediction', $prediccion->idprediccion) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST" id="formMethod">

                <div class="mb-4">
                    <h5 class="mb-3">Información de la Cita</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="idcita" class="form-label">ID Cita</label>
                                <input type="text" class="form-control" id="idcita" name="idcita"
                                       value="{{ $prediccion->cita->idcita }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="paciente" class="form-label">Paciente</label>
                                <input type="text" class="form-control" id="paciente"
                                       value="{{ $prediccion->cita->paciente->nombre }} {{ $prediccion->cita->paciente->apellido }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h5 class="mb-3">Parámetros de la Predicción</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="embarazos" class="form-label">Número de Embarazos</label>
                            <input type="number" name="embarazos" id="embarazos"
                                   class="form-control @error('embarazos') is-invalid @enderror"
                                   value="{{ old('embarazos', $prediccion->embarazos) }}" required min="0">
                            @error('embarazos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="glucosa" class="form-label">Glucosa</label>
                            <input type="number" step="0.01" name="glucosa" id="glucosa"
                                   class="form-control @error('glucosa') is-invalid @enderror"
                                   value="{{ old('glucosa', $prediccion->glucosa) }}" required min="0">
                            @error('glucosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="presion_sanguinea" class="form-label">Presión Sanguínea</label>
                            <input type="number" step="0.01" name="presion_sanguinea" id="presion_sanguinea"
                                   class="form-control @error('presion_sanguinea') is-invalid @enderror"
                                   value="{{ old('presion_sanguinea', $prediccion->presion_sanguinea) }}" required min="0">
                            @error('presion_sanguinea')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grosor_piel" class="form-label">Grosor de Piel (mm)</label>
                            <input type="number" step="0.01" name="grosor_piel" id="grosor_piel"
                                   class="form-control @error('grosor_piel') is-invalid @enderror"
                                   value="{{ old('grosor_piel', $prediccion->grosor_piel) }}" required min="0">
                            @error('grosor_piel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="insulina" class="form-label">Insulina (mu U/ml)</label>
                            <input type="number" step="0.01" name="insulina" id="insulina"
                                   class="form-control @error('insulina') is-invalid @enderror"
                                   value="{{ old('insulina', $prediccion->insulina) }}" required min="0">
                            @error('insulina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="BMI" class="form-label">BMI (kg/m2)</label>
                            <input type="number" step="0.01" name="BMI" id="BMI"
                                   class="form-control @error('BMI') is-invalid @enderror"
                                   value="{{ old('BMI', $prediccion->BMI) }}" required min="0">
                            @error('BMI')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pedigree" class="form-label">Función Pedigree de Diabetes</label>
                            <input type="number" step="0.001" name="pedigree" id="pedigree"
                                   class="form-control @error('pedigree') is-invalid @enderror"
                                   value="{{ number_format(old('pedigree', $prediccion->pedigree), 3, '.', '') }}" required min="0">
                            @error('pedigree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edad" class="form-label">Edad (años)</label>
                            <input type="number" name="edad" id="edad"
                                   class="form-control @error('edad') is-invalid @enderror"
                                   value="{{ old('edad', $prediccion->edad) }}" required min="0">
                            @error('edad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación (Opcional)</label>
                        <textarea name="observacion" id="observacion"
                                  class="form-control @error('observacion') is-invalid @enderror"
                                  rows="3">{{ old('observacion', $prediccion->observacion) }}</textarea>
                        @error('observacion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            const data = {};
            for (let [key, value] of formData.entries()) {
                // Evitar el campo del método _method, que será dinámico
                if (key !== '_method') {
                    data[key] = value;
                }
            }
            
            // --- NUEVO CÓDIGO para capturar el tiempo en milisegundos ---
            const now = new Date();
            const elapsed_ms = now - startTime;
            data['timer_duration_ms'] = elapsed_ms;
            // -------------------------------------------------------------
            
            try {
                // Determinar la acción y el método HTTP
                let endpoint = form.action;
                let method = 'POST';

                if (formMethod.value === 'PUT') {
                    method = 'PUT';
                    endpoint = `{{ route('predicciones.update_confirmed_prediction', $prediccion->idprediccion) }}`;
                }

                const response = await fetch(endpoint, {
                    method: 'POST', // Siempre POST para AJAX, el método real va en el body
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({...data, _method: method})
                });
                
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
