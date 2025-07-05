@extends('layouts.app')

@section('title', 'Realizar Análisis')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Realizar Análisis</h3>
        </div>
        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('predicciones.store') }}" method="POST">
                @csrf

                <!-- Sección de Información de Cita -->
                <div class="row mb-4">
                    <div class="col-12">
                        <!-- Cita -->
                        <div class="mb-3">
                            <label for="idcita" class="form-label">Cita</label>
                            <select name="idcita" id="idcita" class="form-select @error('idcita') is-invalid @enderror" 
                                    onchange="mostrarInfoCita(this.value)">
                                @if(isset($cita))
                                    <option value="{{ $cita->idcita }}" selected data-triaje='{{ json_encode($cita->triaje) }}'>
                                        {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }} - 
                                        {{ $cita->fecha_cita }} {{ date('H:i', strtotime($cita->hora_cita)) }}
                                    </option>
                                @else
                                    <option value="">Selecciona una cita</option>
                                    @foreach($citas as $cita)
                                        @if($cita->triaje)
                                            <option value="{{ $cita->idcita }}" data-triaje='{{ json_encode($cita->triaje) }}'>
                                                {{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }} - 
                                                {{ $cita->fecha_cita }} {{ date('H:i', strtotime($cita->hora_cita)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('idcita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Información de la Cita Seleccionada -->
                        <div id="infoCita" class="mt-3" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Paciente:</strong> <span id="pacienteNombre">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>DNI:</strong> <span id="pacienteDNI">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Sexo:</strong> <span id="pacienteSexo">-</span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-4">
                                            <strong>Teléfono:</strong> <span id="pacienteTelefono">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Fecha:</strong> <span id="citaFecha">-</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Hora:</strong> <span id="citaHora">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Datos de Predicción -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Datos de la Predicción</h5>

                <!-- Fila 1: Glucosa y Presión Sanguínea -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="glucosa" class="form-label">Glucosa</label>
                            <input type="number" name="glucosa" id="glucosa" 
                                   class="form-control @error('glucosa') is-invalid @enderror" 
                                   value="{{ old('glucosa') }}" required>
                            @error('glucosa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="presion_sanguinea" class="form-label">Presión Sanguínea</label>
                            <input type="number" name="presion_sanguinea" id="presion_sanguinea" 
                                   class="form-control @error('presion_sanguinea') is-invalid @enderror" 
                                   value="{{ old('presion_sanguinea') }}" required>
                            @error('presion_sanguinea')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Grosor Piel y Embarazos -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="grosor_piel" class="form-label">Grosor de Piel (mm)</label>
                            <input type="number" name="grosor_piel" id="grosor_piel" 
                                   class="form-control @error('grosor_piel') is-invalid @enderror" 
                                   value="{{ old('grosor_piel') }}" required readonly>
                            @error('grosor_piel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="embarazos" class="form-label">Embarazos</label>
                            <input type="number" name="embarazos" id="embarazos" 
                                   class="form-control @error('embarazos') is-invalid @enderror" 
                                   value="{{ old('embarazos') }}" required>
                            @error('embarazos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 3: BMI y Pedigree -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="BMI" class="form-label">BMI (kg/m2)</label>
                            <input type="number" step="0.01" name="BMI" id="BMI" 
                                   class="form-control @error('BMI') is-invalid @enderror" 
                                   value="{{ old('BMI') }}" required readonly>
                            @error('BMI')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="pedigree" class="form-label">Pedigree</label>
                            <input type="number" step="0.001" name="pedigree" id="pedigree" 
                                   class="form-control @error('pedigree') is-invalid @enderror" 
                                   value="{{ old('pedigree') }}" required>
                            @error('pedigree')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 4: Edad y Observación -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edad" class="form-label">Edad (años)</label>
                            <input type="number" name="edad" id="edad" 
                                   class="form-control @error('edad') is-invalid @enderror" 
                                   value="{{ old('edad') }}" required readonly>
                            @error('edad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="insulina" class="form-label">Insulina (mu U/ml)</label>
                            <input type="number" name="insulina" id="insulina" 
                                   class="form-control @error('insulina') is-invalid @enderror" 
                                   value="{{ old('insulina') }}" required>
                            @error('insulina')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="resultado" class="form-label">Resultado</label>
                            <input type="number" name="resultado" id="resultado" 
                                   class="form-control @error('resultado') is-invalid @enderror" 
                                   value="1" required readonly>
                            @error('resultado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="observacion" class="form-label">Observación</label>
                            <textarea name="observacion" id="observacion" 
                                      class="form-control @error('observacion') is-invalid @enderror"
                                      rows="3">{{ old('observacion') }}</textarea>
                            @error('observacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Predicción</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function mostrarInfoCita(idCita) {
        if (idCita) {
            const selectCita = document.getElementById('idcita');
            const option = selectCita.options[selectCita.selectedIndex];
            const citaText = option.text;
            
            const [paciente, fechaHora] = citaText.split(' - ');
            const [fecha, hora] = fechaHora.split(' ');
            
            // Formatear la hora a HH:mm
            const horaFormateada = hora.split(':').slice(0, 2).join(':');
            
            // Obtener la cita seleccionada para mostrar más información
            const triajeInfo = option.getAttribute('data-triaje');
            const triajeData = JSON.parse(triajeInfo || '{}');
            
            document.getElementById('infoCita').style.display = 'block';
            document.getElementById('pacienteNombre').textContent = paciente;
            document.getElementById('citaFecha').textContent = fecha;
            document.getElementById('citaHora').textContent = horaFormateada;
            
            // Cargar el BMI del triaje si existe
            if (triajeData.BMI) {
                document.getElementById('BMI').value = triajeData.BMI;
            }
            
            // Cargar el grosor de piel del triaje si existe
            if (triajeData.grosor_piel) {
                document.getElementById('grosor_piel').value = triajeData.grosor_piel;
            }
            
            // Cargar la edad del triaje si existe
            if (triajeData.edad) {
                document.getElementById('edad').value = triajeData.edad;
            }
            
        } else {
            document.getElementById('infoCita').style.display = 'none';
            // Limpiar los campos cuando no hay cita seleccionada
            document.getElementById('BMI').value = '';
            document.getElementById('grosor_piel').value = '';
            document.getElementById('edad').value = '';
        }
    }

    // Si hay una cita pre-seleccionada, mostrar su información
    document.addEventListener('DOMContentLoaded', function() {
        const selectCita = document.getElementById('idcita');
        if (selectCita.value) {
            mostrarInfoCita(selectCita.value);
        }
    });

    // Función para mostrar la información del triaje
    function mostrarInfoTriaje(triaje) {
        if (triaje) {
            document.getElementById('triajeGrosorPiel').textContent = triaje.grosor_piel || '-';
            document.getElementById('triajeEdad').textContent = triaje.edad || '-';
            document.getElementById('triajeBMI').textContent = triaje.BMI || '-';
        }
    }

    // Si hay un triaje pre-seleccionado, mostrar su información
    @if(isset($triaje))
    document.addEventListener('DOMContentLoaded', function() {
        mostrarInfoTriaje({
            grosor_piel: '{{ $triaje->grosor_piel }}',
            edad: '{{ $triaje->edad }}',
            BMI: '{{ $triaje->BMI }}'
        });
    });
    @endif

    // Función para mostrar la información del paciente
    function mostrarInfoPaciente(paciente) {
        if (paciente) {
            document.getElementById('pacienteSexo').textContent = paciente.sexo || '-';
            document.getElementById('pacienteTelefono').textContent = paciente.telefono || '-';
            document.getElementById('pacienteDNI').textContent = paciente.DNI || '-';
        }
    }

    // Si hay un paciente pre-seleccionado, mostrar su información
    @if(isset($paciente))
    document.addEventListener('DOMContentLoaded', function() {
        mostrarInfoPaciente({
            sexo: '{{ $paciente->sexo }}',
            telefono: '{{ $paciente->telefono }}',
            DNI: '{{ $paciente->DNI }}',
        });
    });
    @endif
</script>
@endsection
