@extends('layouts.app')

@section('title', 'Predicciones')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Predicciones</h1>
        <a href="{{ route('predicciones.exportar') }}" class="btn btn-success">
            <i class='bx bx-file'></i> Exportar a Excel
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Cita</th>
                            <th>Resultado</th>
                            <th>Validar predicción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predicciones as $prediccion)
                        <tr>
                            <td>{{ $prediccion->idprediccion }}</td>
                            <td>{{ $prediccion->cita->paciente->nombre . ' ' . $prediccion->cita->paciente->apellido }}</td>
                            <td>{{ $prediccion->cita->idcita }}</td>

                            <td>{{number_format($prediccion->resultado, 2) }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Validar predicción">
                                    <input 
                                        type="radio" 
                                        class="btn-check validar-radio" 
                                        name="validar[{{ $prediccion->idprediccion }}]" 
                                        id="validar-si-{{ $prediccion->idprediccion }}" 
                                        value="1"
                                        data-id="{{ $prediccion->idprediccion }}"
                                        autocomplete="off"
                                        {{ (isset($prediccion->validar_prediccion) && (int)$prediccion->validar_prediccion === 1) ? 'checked' : '' }}
                                    >
                                    <label class="btn btn-sm btn-outline-success" for="validar-si-{{ $prediccion->idprediccion }}">Si</label>
                                    <input 
                                        type="radio" 
                                        class="btn-check validar-radio" 
                                        name="validar[{{ $prediccion->idprediccion }}]" 
                                        id="validar-no-{{ $prediccion->idprediccion }}" 
                                        value="0"
                                        data-id="{{ $prediccion->idprediccion }}"
                                        autocomplete="off"
                                        {{ (isset($prediccion->validar_prediccion) && (int)$prediccion->validar_prediccion === 0) ? 'checked' : '' }}
                                    >
                                    <label class="btn btn-sm btn-outline-danger" for="validar-no-{{ $prediccion->idprediccion }}">No</label>
                                </div>
                            </td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

  function postValidacion(id, valor) {
    const url = `{{ url('/predicciones') }}/${id}/validar`;
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      },
      credentials: 'same-origin',
      body: JSON.stringify({ validar_prediccion: valor })
    }).then(async (res) => {
      if (!res.ok) {
        const data = await res.json().catch(() => ({}));
        console.error('Error al guardar validación', data);
      }
    }).catch(err => console.error('Error de red', err));
  }

  document.querySelectorAll('.validar-radio').forEach((input) => {
    input.addEventListener('change', (e) => {
      const id = e.target.getAttribute('data-id');
      const valor = e.target.value;
      if (id && valor) {
        postValidacion(id, valor);
      }
    });
  });
});
</script>
@endpush
