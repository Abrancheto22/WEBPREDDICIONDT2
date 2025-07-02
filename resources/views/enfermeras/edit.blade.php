@extends('layouts.app')

@section('title', 'Editar Enfermera')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Enfermera</h5>
                    <a href="{{ route('enfermeras.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('enfermeras.update', $enfermera->idenfermera) }}" method="POST" enctype="multipart/form-data">
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
                                src="{{ asset($enfermera->imagen) }}"
                                alt="enfermera-avatar"
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
                                    value="{{ old('nombre', $enfermera->nombre) }}"
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
                                    value="{{ old('apellido', $enfermera->apellido) }}"
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
                                    value="{{ old('DNI', $enfermera->DNI) }}"
                                    required />
                                @error('DNI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="numero" class="form-label">Número de Teléfono</label>
                                <input
                                    type="text"
                                    class="form-control @error('numero') is-invalid @enderror"
                                    id="numero"
                                    name="numero"
                                    value="{{ old('numero', $enfermera->numero) }}"
                                    required />
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-6 mb-4">
                            <div class="col-md-6">
                                <label for="iduser" class="form-label">Usuario</label>
                                <select class="form-select @error('iduser') is-invalid @enderror" id="iduser" name="iduser" required>
                                    <option value="">Seleccione un usuario...</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ old('iduser', $enfermera->iduser) == $usuario->id ? 'selected' : '' }}>
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
                                <i class="bx bx-save"></i> Actualizar Enfermera
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection