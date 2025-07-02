@extends('layouts.app')

@section('title', 'Enfermeras')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Enfermeras</h5>
                    <a href="{{ route('enfermeras.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nueva Enfermera
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
                                <th>Imagen</th>
                                <th>Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enfermeras as $enfermera)
                            <tr>
                                <td>{{ $enfermera->idenfermera }}</td>
                                <td>{{ $enfermera->DNI }}</td>
                                <td>{{ $enfermera->nombre }} {{ $enfermera->apellido }}</td>
                                <td>{{ $enfermera->numero }}</td>
                                <td>
                                    @if ($enfermera->imagen)
                                        <img src="{{ asset($enfermera->imagen) }}" alt="Imagen de la enfermera" class="img-fluid" style="max-width: 100px;">
                                    @else
                                        <span class="text-muted">No hay imagen</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($enfermera->usuario)
                                        {{ $enfermera->usuario->name }}
                                    @else
                                        <span class="text-muted">No asignado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('enfermeras.show', $enfermera->idenfermera) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('enfermeras.edit', $enfermera->idenfermera) }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('enfermeras.destroy', $enfermera->idenfermera) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta enfermera?')">
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
            </div>
        </div>
    </div>
</div>
@endsection