@extends('layouts.app')

@section('title', 'Editar Predicción')

@section('content')
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

            {{-- FORMULARIO PRINCIPAL PARA LA RE-PREDICCIÓN --}}
            <form id="editPredictionForm" action="{{ route('predicciones.process_edited_prediction', $prediccion->idprediccion) }}" method="POST">
                @csrf
                {{-- No necesitamos @method('PUT') aquí, porque este formulario es para AJAX POST --}}

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

                <!-- Botones de acción del formulario de RE-PREDICCIÓN -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" id="predictBtn" class="btn btn-primary">
                                <span id="predictBtnText">Re-Predecir</span>
                                <span id="predictSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="predictLoadingText" class="d-none">Cargando...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <hr class="my-4">

            {{-- SECCIÓN PARA MOSTRAR EL RESULTADO DE LA PREDICCIÓN (inicialmente oculta) --}}
            <div id="predictionResultSection" style="display: none;">
                <h4 class="mb-3">Resultado de la Predicción Actualizada:</h4>
                <div id="predictionResultContent">
                    {{-- Aquí se inyectará el resultado via JS --}}
                </div>

                {{-- FORMULARIO PARA GUARDAR LA PREDICCIÓN EDITADA (inicialmente oculto) --}}
                <form id="saveEditedPredictionForm" action="{{ route('predicciones.update_confirmed_prediction', $prediccion->idprediccion) }}" method="POST" style="display: none;">
                    @csrf
                    @method('PUT') {{-- Importante para el método PUT --}}

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
                    
                    {{-- Hidden inputs para los resultados de la predicción --}}
                    <input type="hidden" name="probability_diabetes" id="save_probability_diabetes">
                    <input type="hidden" name="prediction_label" id="save_prediction_label">
                    <input type="hidden" name="diagnosis" id="save_diagnosis">

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Lógica AJAX para la re-predicción en edición ---
        const editPredictionForm = document.getElementById('editPredictionForm');
        const predictBtn = document.getElementById('predictBtn');
        const predictBtnText = document.getElementById('predictBtnText');
        const predictSpinner = document.getElementById('predictSpinner');
        const predictLoadingText = document.getElementById('predictLoadingText');
        const predictionResultSection = document.getElementById('predictionResultSection');
        const predictionResultContent = document.getElementById('predictionResultContent');
        const saveEditedPredictionForm = document.getElementById('saveEditedPredictionForm');

        editPredictionForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

            // Mostrar spinner y deshabilitar botón
            predictBtn.disabled = true;
            predictBtnText.classList.add('d-none');
            predictSpinner.classList.remove('d-none');
            predictLoadingText.classList.remove('d-none');
            predictionResultSection.style.display = 'none'; // Ocultar resultados anteriores
            saveEditedPredictionForm.style.display = 'none'; // Ocultar botón de guardar

            const formData = new FormData(editPredictionForm);
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            try {
                const response = await fetch(editPredictionForm.action, {
                    method: 'POST', // Siempre POST para AJAX
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
                    throw new Error(result.error || 'Error en la re-predicción.');
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
                saveEditedPredictionForm.style.display = 'block'; // Mostrar el botón de guardar

                // Llenar los campos ocultos del formulario de guardar
                // Los campos de entrada se toman directamente del formulario actual
                document.getElementById('save_idcita').value = document.getElementById('idcita').value;
                document.getElementById('save_embarazos').value = document.getElementById('embarazos').value;
                document.getElementById('save_glucosa').value = document.getElementById('glucosa').value;
                document.getElementById('save_presion_sanguinea').value = document.getElementById('presion_sanguinea').value;
                document.getElementById('save_grosor_piel').value = document.getElementById('grosor_piel').value;
                document.getElementById('save_insulina').value = document.getElementById('insulina').value;
                document.getElementById('save_BMI').value = document.getElementById('BMI').value;
                document.getElementById('save_pedigree').value = document.getElementById('pedigree').value;
                document.getElementById('save_edad').value = document.getElementById('edad').value;
                document.getElementById('save_observacion').value = document.getElementById('observacion').value;
                
                // Los resultados de la ML se toman de la respuesta
                document.getElementById('save_probability_diabetes').value = result.predictionResult.probability_diabetes;
                document.getElementById('save_prediction_label').value = result.predictionResult.prediction;
                document.getElementById('save_diagnosis').value = result.predictionResult.diagnosis;

            } catch (error) {
                console.error('Error:', error);
                predictionResultContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        Ocurrió un error al realizar la re-predicción: ${error.message}. Por favor, revise los datos o inténtelo más tarde.
                    </div>
                `;
                predictionResultSection.style.display = 'block';
                saveEditedPredictionForm.style.display = 'none'; // No mostrar el botón de guardar si hay error
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
@endsection