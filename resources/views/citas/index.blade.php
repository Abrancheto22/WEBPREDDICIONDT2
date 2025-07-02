@extends('layouts.app')

@section('title', 'Citas Médicas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Citas</h5>
                    <a href="{{ route('citas.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nueva Cita
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                            <tr>
                                <td>{{ $cita->idcita }}</td>
                                <td>{{ $cita->fecha_cita }}</td>
                                <td>{{ \Carbon\Carbon::parse($cita->hora_cita)->format('H:i') }}</td>
                                <td>{{ $cita->paciente_nombre }} {{ $cita->paciente_apellido }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($cita->motivo, 30) }}</td>
                                <td>{{ $cita->estado }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('citas.show', $cita->idcita) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('citas.edit', $cita->idcita) }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('citas.destroy', $cita->idcita) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta cita?')">
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