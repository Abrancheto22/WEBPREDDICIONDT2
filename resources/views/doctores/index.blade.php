@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Doctores</h5>
                    <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nuevo Doctor
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DNI</th>
                                <th>Nombre</th>
                                <th>Número</th>
                                <th>Especialidad</th>
                                <th>Imagen</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctores as $doctor)
                            <tr>
                                <td>{{ $doctor->iddoctor }}</td>
                                <td>{{ $doctor->DNI }}</td>
                                <td>{{ $doctor->nombre }} {{ $doctor->apellido }}</td>
                                <td>{{ $doctor->numero }}</td>
                                <td>{{ $doctor->especialidad }}</td>
                                <td>
                                    @if ($doctor->imagen)
                                        <img src="{{ asset($doctor->imagen) }}" alt="Imagen del doctor" class="img-fluid" style="max-width: 100px;">
                                    @else
                                        <span class="text-muted">No hay imagen</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($doctor->usuario)
                                        {{ $doctor->usuario->name }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('doctores.edit', $doctor->iddoctor) }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('doctores.destroy', $doctor->iddoctor) }}" method="POST" class="d-inline">
                                            @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('title', 'Doctores')