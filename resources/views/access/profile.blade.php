@extends('layouts.app')

@section('title', 'Perfil')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        @if($profileView === 'admin')
                            <img
                                src="{{ asset('/plantilla/assets/img/avatars/1.png') }}"
                                alt="admin-avatar"
                                class="d-block w-px-150 h-px-150 rounded"
                                id="uploadedAvatar" />
                        @else
                            <img
                                src="{{ $profileData ? asset($profileData->imagen ?? 'assets/img/avatars/1.png') : asset('assets/img/avatars/1.png') }}"
                                alt="{{ $profileData ? ($profileView === 'doctor' ? 'doctor-avatar' : ($profileView === 'enfermera' ? 'enfermera-avatar' : 'paciente-avatar')) : 'user-avatar' }}"
                                class="d-block w-px-150 h-px-150 rounded"
                                id="uploadedAvatar" />
                        @endif
                        <div class="button-wrapper">
                            <h4 class="mb-2">{{ $user->name }}</h4>
                            <p class="mb-0 text-muted">{{ $user->rol->nombre }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($profileView === 'admin')
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Bienvenido Administrador</h5>
                    <p class="card-text">Como administrador, tienes acceso a todas las funcionalidades del sistema.</p>
                </div>
            </div>
            @else
            <div class="card mb-4">
                <div class="card-body">
                    @if($profileExists)
                        <div class="mb-4">
                            @if($profileView === 'doctor')
                            <h5 class="card-header">Información del Doctor</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DNI</label>
                                    <p class="mb-0">{{ $profileData->DNI }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número</label>
                                    <p class="mb-0">{{ $profileData->numero }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Especialidad</label>
                                    <p class="mb-0">{{ $profileData->especialidad }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre</label>
                                    <p class="mb-0">{{ $profileData->nombre }} {{ $profileData->apellido }}</p>
                                </div>
                            </div>
                        @elseif($profileView === 'enfermera')
                            <h5 class="card-header">Información de la Enfermera</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DNI</label>
                                    <p class="mb-0">{{ $profileData->DNI }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Número</label>
                                    <p class="mb-0">{{ $profileData->numero }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre</label>
                                    <p class="mb-0">{{ $profileData->nombre }} {{ $profileData->apellido }}</p>
                                </div>
                            </div>
                        @elseif($profileView === 'paciente')
                            <h5 class="card-header">Información del Paciente</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">DNI</label>
                                    <p class="mb-0">{{ $profileData->DNI }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sexo</label>
                                    <p class="mb-0">{{ $profileData->sexo }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <p class="mb-0">{{ $profileData->fecha_nacimiento }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <p class="mb-0">{{ $profileData->telefono }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nombre</label>
                                    <p class="mb-0">{{ $profileData->nombre }} {{ $profileData->apellido }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Dirección</label>
                                    <p class="mb-0">{{ $profileData->direccion }}</p>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>No tienes un perfil asociado.</strong>
                            <p class="mb-0">Puedes crear un perfil para tu rol en el sistema.</p>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mt-4">
                        @if(!$profileExists)
                            <a href="{{ route($profileRoute) }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Crear Perfil
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection