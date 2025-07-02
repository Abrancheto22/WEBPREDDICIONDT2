@extends('layouts.app')

@section('title', 'Triajes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Lista de Triajes</h5>
                        <a href="{{ route('triajes.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Nuevo Triaje
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success fade-out-alert" id="successAlert">
                            {{ session('success') }}
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                setTimeout(function() {
                                    document.getElementById('successAlert').style.display = 'none';
                                }, 5000); // 5000ms = 5 seconds
                            });
                        </script>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Paciente</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Edad</th>
                                    <th>BMI</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($triajes as $triaje)
                                <tr>
                                    <td>{{ $triaje->idtriaje }}</td>
                                    <td>{{ $triaje->cita->paciente->nombre }} {{ $triaje->cita->paciente->apellido }}</td>
                                    <td>{{ \Carbon\Carbon::parse($triaje->cita->fecha_cita)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($triaje->cita->hora_cita)->format('H:i') }}</td>
                                    <td>{{ $triaje->edad }}</td>
                                    <td>{{ $triaje->BMI }}</td>
                                    <td>{{ $triaje->observaciones }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('triajes.show', $triaje->idtriaje) }}" class="btn btn-sm btn-info">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('triajes.edit', $triaje->idtriaje) }}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <form action="{{ route('triajes.destroy', $triaje->idtriaje) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este triaje?')">
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

                    <div class="d-flex justify-content-center mt-3">
                        {{ $triajes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
