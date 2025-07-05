@extends('layouts.app')

@section('title', 'Editar Predicción')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Editar Predicción</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('predicciones.update', $prediccion->idprediccion) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="glucosa" class="form-label">Glucosa (mg/dl)</label>
                                        <input type="number" class="form-control" id="glucosa" name="glucosa" 
                                               value="{{ old('glucosa', $prediccion->glucosa) }}" required min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="presion_sanguinea" class="form-label">Presión Sanguínea (mmHg)</label>
                                        <input type="number" class="form-control" id="presion_sanguinea" name="presion_sanguinea" 
                                               value="{{ old('presion_sanguinea', $prediccion->presion_sanguinea) }}" required min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="grosor_piel" class="form-label">Grosor Piel (mm)</label>
                                        <input type="number" class="form-control" id="grosor_piel" name="grosor_piel" 
                                               value="{{ old('grosor_piel', $prediccion->grosor_piel) }}" required min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="embarazos" class="form-label">Embarazos</label>
                                        <input type="number" class="form-control" id="embarazos" name="embarazos" 
                                               value="{{ old('embarazos', $prediccion->embarazos) }}" required min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="BMI" class="form-label">BMI (kg/m²)</label>
                                        <input type="number" step="0.01" class="form-control" id="BMI" name="BMI" 
                                               value="{{ old('BMI', $prediccion->BMI) }}" required min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pedigree" class="form-label">Pedigree</label>
                                        <input type="number" step="0.01" class="form-control" id="pedigree" name="pedigree" 
                                               value="{{ number_format(old('pedigree', $prediccion->pedigree), 2) }}" required min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edad" class="form-label">Edad</label>
                                        <input type="number" class="form-control" id="edad" name="edad" 
                                               value="{{ old('edad', $prediccion->edad) }}" required min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="insulina" class="form-label">Insulina (μU/ml)</label>
                                        <input type="number" class="form-control" id="insulina" name="insulina" 
                                               value="{{ old('insulina', $prediccion->insulina) }}" required min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3">Resultado y Observación</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="resultado" class="form-label">Resultado</label>
                                        <select class="form-select" id="resultado" name="resultado" required>
                                            <option value="0" {{ old('resultado', $prediccion->resultado) == 0 ? 'selected' : '' }}>Negativo</option>
                                            <option value="1" {{ old('resultado', $prediccion->resultado) == 1 ? 'selected' : '' }}>Positivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="observacion" class="form-label">Observación</label>
                                        <textarea class="form-control" id="observacion" name="observacion" rows="3">{{ old('observacion', $prediccion->observacion) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="{{ route('predicciones.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection