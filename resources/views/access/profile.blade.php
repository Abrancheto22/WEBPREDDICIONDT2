@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img
                            src="{{ asset($user->imagen ?? 'assets/img/avatars/1.png') }}"
                            alt="user-avatar"
                            class="d-block w-px-150 h-px-150 rounded"
                            id="uploadedAvatar" />
                        <div class="button-wrapper">
                            <h4 class="mb-2">{{ $user->name }}</h4>
                            <p class="mb-0 text-muted">{{ $user->rol->nombre }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($doctor)
            <div class="card mb-4">
                <h5 class="card-header">Información del Doctor</h5>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">DNI</label>
                            <p class="mb-0">{{ $doctor->DNI }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número</label>
                            <p class="mb-0">{{ $doctor->numero }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Especialidad</label>
                            <p class="mb-0">{{ $doctor->especialidad }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usuario Asociado</label>
                            <p class="mb-0">{{ $doctor->usuario->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>No tienes un perfil de doctor asociado.</strong>
                        <p class="mb-0">Puedes crear un perfil de doctor desde el menú de doctores.</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus me-1"></i> Crear Perfil de Doctor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection