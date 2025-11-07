@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Lista de Doctores</h5>
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" id="search-doctores" class="form-control form-control-sm" placeholder="Buscar..." />
                        <a href="{{ route('doctores.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Nuevo Doctor
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="table-doctores">
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
                                    <div class="btn-group">
                                        <a href="{{ route('doctores.show', $doctor->iddoctor) }}" class="btn btn-sm btn-info">
                                            <i class="bx bx-show"></i>
                                        </a>
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

@section('title', 'Doctores')
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('search-doctores');
  const table = document.getElementById('table-doctores');
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