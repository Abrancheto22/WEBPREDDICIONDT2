@extends('layouts.app')

@section('title', 'Realizar Análisis')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Realizar Análisis de Diabetes (ML)</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
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

            {{-- FORMULARIO PRINCIPAL PARA LA PREDICCIÓN --}}
            <form id="predictionForm" action="{{ route('predicciones.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="idcita" class="form-label">Cita</label>
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

                        <div id="infoCita" class="mt-3 card bg-light p-3" style="display: none;">
                            <h5 class="card-title">Detalles de la Cita y Paciente</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Paciente:</strong> <span id="infoPacienteNombre">-</span></p>
                                    <p><strong>DNI:</strong> <span id="infoPacienteDNI">-</span></p>
                                    <p><strong>Sexo:</strong> <span id="infoPacienteSexo">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Teléfono:</strong> <span id="infoPacienteTelefono">-</span></p>
                                    <p><strong>Fecha Cita:</strong> <span id="infoCitaFecha">-</span></p>
                                    <p><strong>Hora Cita:</strong> <span id="infoCitaHora">-</span></p>
                                </div>
                            </div>
                            <div id="alertaSinTriaje" class="alert alert-warning mt-3" style="display: none;">
                                La cita seleccionada no tiene un triaje asociado. Por favor, ingrese los datos manualmente.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Datos para la Predicción (Pima Indians)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="embarazos" class="form-label">Número de Embarazos</label>
                                <input type="number" name="embarazos" id="embarazos" 
                                       class="form-control @error('embarazos') is-invalid @enderror" 
                                       value="{{ old('embarazos', $cita->triaje->embarazos ?? '') }}" required min="0">
                                @error('embarazos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="glucosa" class="form-label">Glucosa</label>
                                <input type="number" step="0.01" name="glucosa" id="glucosa" 
                                       class="form-control @error('glucosa') is-invalid @enderror" 
                                       value="{{ old('glucosa', $cita->triaje->glucosa ?? '') }}" required min="0">
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
                                       value="{{ old('presion_sanguinea', $cita->triaje->presion_sanguinea ?? '') }}" required min="0">
                                @error('presion_sanguinea')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="grosor_piel" class="form-label">Grosor de Piel (mm)</label>
                                <input type="number" step="0.01" name="grosor_piel" id="grosor_piel" 
                                       class="form-control @error('grosor_piel') is-invalid @enderror" 
                                       value="{{ old('grosor_piel', $cita->triaje->grosor_piel ?? '') }}" required min="0">
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
                                       value="{{ old('insulina', $cita->triaje->insulina ?? '') }}" required min="0">
                                @error('insulina')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="BMI" class="form-label">BMI (kg/m2)</label>
                                <input type="number" step="0.01" name="BMI" id="BMI" 
                                       class="form-control @error('BMI') is-invalid @enderror" 
                                       value="{{ old('BMI', $cita->triaje->BMI ?? '') }}" required min="0">
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
                                       value="{{ old('pedigree', $cita->triaje->pedigree ?? '') }}" required min="0">
                                @error('pedigree')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edad" class="form-label">Edad (años)</label>
                                <input type="number" name="edad" id="edad" 
                                       class="form-control @error('edad') is-invalid @enderror" 
                                       value="{{ old('edad', $cita->triaje->edad ?? '') }}" required min="0">
                                @error('edad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación (Opcional)</label>
                            <textarea name="observacion" id="observacion" 
                                      class="form-control @error('observacion') is-invalid @enderror"
                                      rows="3">{{ old('observacion') }}</textarea>
                            @error('observacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" id="predictBtn" class="btn btn-primary">
                                <span id="predictBtnText">Predecir</span>
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
                <h4 class="mb-3">Resultado de la Predicción:</h4>
                <div id="predictionResultContent">
                    {{-- Aquí se inyectará el resultado via JS --}}
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
    const allCitasData = @json($citas);

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

        predictionForm.addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevenir el envío normal del formulario

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
@endsection