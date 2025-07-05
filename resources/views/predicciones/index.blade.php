@extends('layouts.app')

@section('title', 'Predicciones')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Predicciones</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cita</th>
                            <th>Glucosa</th>
                            <th>Presión</th>
                            <th>Grosor Piel</th>
                            <th>Embarazos</th>
                            <th>BMI</th>
                            <th>Pedigree</th>
                            <th>Edad</th>
                            <th>Resultado</th>
                            <th>Observación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predicciones as $prediccion)
                        <tr>
                            <td>{{ $prediccion->idprediccion }}</td>
                            <td>{{ $prediccion->cita->idcita }}</td>
                            <td>{{ $prediccion->glucosa }}</td>
                            <td>{{ $prediccion->presion_sanguinea }}</td>
                            <td>{{ $prediccion->grosor_piel }}</td>
                            <td>{{ $prediccion->embarazos }}</td>
                            <td>{{ $prediccion->BMI }}</td>
                            <td>{{ number_format($prediccion->pedigree, 2) }}</td>
                            <td>{{ $prediccion->edad }}</td>
                            <td>{{number_format($prediccion->resultado, 2) }}</td>
                            <td>{{ $prediccion->observacion }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('predicciones.show', $prediccion->idprediccion) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    <a href="{{ route('predicciones.edit', $prediccion->idprediccion) }}" class="btn btn-sm btn-warning">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('predicciones.destroy', $prediccion->idprediccion) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta predicción?')">
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
@endsection
