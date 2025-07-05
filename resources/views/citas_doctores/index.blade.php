@extends('layouts.app')

@section('title', 'Citas Médicas')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Citas Médicas</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Cita</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th>Triaje</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($citas as $cita)
                        <tr>
                            <td>{{ $cita->idcita }}</td>
                            <td>{{ $cita->paciente_nombre }} {{ $cita->paciente_apellido }}</td>
                            <td>{{ $cita->doctor_nombre }} {{ $cita->doctor_apellido }}</td>
                            <td>{{ date('d/m/Y', strtotime($cita->fecha_cita)) }}</td>
                            <td>{{ date('H:i', strtotime($cita->hora_cita)) }}</td>
                            <td>{{ $cita->motivo }}</td>
                            <td>
                                <span class="badge {{ $cita->estado === 'pendiente' ? 'bg-warning' : ($cita->estado === 'atendida' ? 'bg-success' : 'bg-secondary') }}">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </td>
                            <td>
                                @if($cita->tiene_triaje)
                                    <span class="badge bg-primary">Con triaje: {{ $cita->idtriaje }}</span>
                                @else
                                    <span class="badge bg-danger">Sin triaje</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('predicciones.create', ['idcita' => $cita->idcita]) }}" class="btn btn-sm btn-info">Consulta</a>
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
@endsection