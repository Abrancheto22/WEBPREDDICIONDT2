@extends('layouts.app')

@section('title', 'Editar Rol')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Editar Rol</h5>
                    <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back"></i> Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.update', $rol->idrol) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Rol</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $rol->nombre }}" required>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i> Actualizar Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
