@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Pacientes</h5>
                    <div class="d-flex gap-2 align-items-center">
                        <input type="text" id="search-pacientes" class="form-control form-control-sm" placeholder="Buscar..." />
                        <a href="{{ route('pacientes.panel') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-grid-alt"></i> Panel de Paciente
                        </a>
                        <a href="{{ route('pacientes.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Nuevo Paciente
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="table-pacientes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Sexo</th>
                                <th>Fecha Nacimiento</th>
                                <th>Imagen</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pacientes as $paciente)
                            <tr>
                                <td>{{ $paciente->idpaciente }}</td>
                                <td>{{ $paciente->apellido }}, {{ $paciente->nombre }}</td>
                                <td>{{ $paciente->telefono }}</td>
                                <td>{{ $paciente->sexo }}</td>
                                <td>{{ $paciente->fecha_nacimiento }}</td>
                                <td>{{ $paciente->dni ?? $paciente->DNI }}</td>
                                <td>{{ optional($paciente->usuario)->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('pacientes.show', $paciente->idpaciente) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('pacientes.panel', ['idpaciente' => $paciente->idpaciente]) }}" class="btn btn-sm btn-secondary" title="Abrir Panel">
                                            <i class="bx bx-dock-top"></i>
                                        </a>
                                        <a href="{{ route('pacientes.edit', $paciente->idpaciente) }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('pacientes.destroy', $paciente->idpaciente) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este paciente?')">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $pacientes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
