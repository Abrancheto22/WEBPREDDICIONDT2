@extends('layouts.app')

@section('title', 'Editar Paciente')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Paciente</h5>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('pacientes.update', $paciente->idpaciente) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Mensajes de éxito y error -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- Profile Photo Section -->
                        <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                            <img
                                src="{{ asset($paciente->imagen) }}"
                                alt="paciente-avatar"
                                class="d-block w-px-100 h-px-100 rounded"
                                id="uploadedAvatar" />
                            <div class="mb-3">
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                                <small class="text-muted">Dejar vacío para mantener la imagen actual</small>
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="row g-6 mb-4">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    id="nombre"
                                    name="nombre"
                                    value="{{ old('nombre', $paciente->nombre) }}"
                                    required />
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input
                                    type="text"
                                    class="form-control @error('apellido') is-invalid @enderror"
                                    id="apellido"
                                    name="apellido"
                                    value="{{ old('apellido', $paciente->apellido) }}"
                                    required />
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-6 mb-4">
                            <div class="col-md-6">
                                <label for="DNI" class="form-label">DNI</label>
                                <input
                                    type="text"
                                    class="form-control @error('DNI') is-invalid @enderror"
                                    id="DNI"
                                    name="DNI"
                                    value="{{ old('DNI', $paciente->DNI) }}"
                                    required />
                                @error('DNI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select @error('sexo') is-invalid @enderror" id="sexo" name="sexo" required>
                                    <option value="">Seleccione un sexo...</option>
                                    <option value="M" {{ old('sexo', $paciente->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $paciente->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-6 mb-4">
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input
                                    type="date"
                                    class="form-control @error('fecha_nacimiento') is-invalid @enderror"
                                    id="fecha_nacimiento"
                                    name="fecha_nacimiento"
                                    value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento) }}"
                                    required />
                                @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input
                                    type="text"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    id="telefono"
                                    name="telefono"
                                    value="{{ old('telefono', $paciente->telefono) }}" />
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-6 mb-4">
                            <div class="col-md-6">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input
                                    type="text"
                                    class="form-control @error('direccion') is-invalid @enderror"
                                    id="direccion"
                                    name="direccion"
                                    value="{{ old('direccion', $paciente->direccion) }}"
                                    required />
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="iduser" class="form-label">Usuario</label>
                                <select class="form-select @error('iduser') is-invalid @enderror" id="iduser" name="iduser" required>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('iduser', $paciente->iduser) == $usuario->id ? 'selected' : '' }}>
                                            {{ $usuario->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('iduser')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Actualizar Paciente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function displayFileName(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('uploadedAvatar').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
