@extends('layouts.app')

@section('title', 'Citas Médicas')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Citas Médicas</h1>
        <input type="text" id="search-citas-doc" class="form-control form-control-sm" placeholder="Buscar..." style="max-width: 240px;" />
    </div>

    {{-- Filtro de Estado con estilo de Bootstrap --}}
    <div class="mb-3">
        <form action="{{ route('citas_doctores.index') }}" method="GET" class="d-flex align-items-center">
            <div class="form-group mb-0">
                <label for="estado" class="mr-2">Filtrar por Estado:</label>
                <select name="estado" id="estado" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="Pendiente" {{ old('estado', $selectedEstado ?? '') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Realizado" {{ old('estado', $selectedEstado ?? '') == 'Realizado' ? 'selected' : '' }}>Realizado</option>
                </select>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-citas-doc">
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
                                <span class="badge {{ $cita->estado === 'Pendiente' ? 'bg-warning' : ($cita->estado === 'Realizado' ? 'bg-success' : 'bg-secondary') }}">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('search-citas-doc');
  const table = document.getElementById('table-citas-doc');
  if (!input || !table) return;
  const rows = Array.from(table.querySelectorAll('tbody tr'));
  function normalize(s){ return (s || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''); }
  input.addEventListener('input', function () {
    const q = normalize(this.value);
    rows.forEach(tr => {
      const text = normalize(tr.innerText);
      tr.style.display = text.includes(q) ? '' : 'none';
    });
  });
});
</script>
@endpush