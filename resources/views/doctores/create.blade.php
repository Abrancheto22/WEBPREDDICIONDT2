@extends('layouts.app')

@section('title', 'Crear Doctor')
@section('content')
<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-6">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Crear Doctor</h5>
                        <a href="{{ route('doctores.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back"></i> Volver
                        </a>
                    </div>
                    <div class="card-body pt-4">
                        <form action="{{ route('doctores.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
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

                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label for="DNI" class="form-label">DNI</label>
                                    <input
                                        type="text"
                                        class="form-control @error('DNI') is-invalid @enderror"
                                        id="DNI"
                                        name="DNI"
                                        value="{{ old('DNI') }}"
                                        required
                                        autofocus />
                                    @error('DNI')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input
                                        type="text"
                                        class="form-control @error('nombre') is-invalid @enderror"
                                        id="nombre"
                                        name="nombre"
                                        value="{{ old('nombre') }}"
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
                                        value="{{ old('apellido') }}"
                                        required />
                                    @error('apellido')
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
                                        value="{{ old('numero') }}"
                                        required />
                                    @error('numero')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="especialidad" class="form-label">Especialidad</label>
                                    <input
                                        type="text"
                                        class="form-control @error('especialidad') is-invalid @enderror"
                                        id="especialidad"
                                        name="especialidad"
                                        value="{{ old('especialidad') }}"
                                        required />
                                    @error('especialidad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="iduser" class="form-label">Usuario</label>
                                    <select
                                        class="form-select @error('iduser') is-invalid @enderror"
                                        id="iduser"
                                        name="iduser"
                                        required>
                                        <option value="">Seleccione un usuario...</option>
                                        @foreach($usuarios as $usuario)
                                            <option value="{{ $usuario->id }}" {{ old('iduser') == $usuario->id ? 'selected' : '' }}>
                                                {{ $usuario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('iduser')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="imagen" class="form-label">Imagen</label>
                                    <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen">
                                    @error('imagen')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Crear Doctor
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
</div>
@endsection

