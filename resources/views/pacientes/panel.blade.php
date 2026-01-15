@extends('layouts.app')

@section('title', 'Panel de Paciente')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    @if($isAdminOrEnfermera)
    <div class="col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <form method="GET" action="{{ route('pacientes.panel') }}" class="row g-3 align-items-end">
            <div class="col-sm-8 col-md-9">
              <label for="idpaciente" class="form-label">Seleccionar paciente</label>
              <select id="idpaciente" name="idpaciente" class="form-select">
                <option value="">-- Elegir --</option>
                @foreach($pacientes as $p)
                  <option value="{{ $p->idpaciente }}" {{ (optional($selectedPaciente)->idpaciente ?? '') == $p->idpaciente ? 'selected' : '' }}>
                    {{ $p->apellido }}, {{ $p->nombre }} (DNI: {{ $p->dni ?? $p->DNI }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-sm-4 col-md-3 d-grid">
              <button type="submit" class="btn btn-primary">Cargar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif

    <div class="col-12">
      <div class="card h-100">
        <div class="card-body">
          @if(!$selectedPaciente)
            <h5 class="card-title mb-3">Panel de Paciente</h5>
            <p class="mb-0">Seleccione un paciente para ver su información o ingrese con una cuenta de paciente.</p>
          @else
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h5 class="mb-0">{{ $selectedPaciente->apellido }}, {{ $selectedPaciente->nombre }} - Historial</h5>
            </div>

            <div class="row g-4">
              <div class="col-12">
                <h6 class="mb-2">Citas médicas</h6>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Médico</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($citas as $c)
                        <tr>
                          <td>{{ $c->fecha_cita }}</td>
                          <td>{{ $c->hora_cita }}</td>
                          <td>{{ optional($c->doctor->usuario)->name }}</td>
                          <td>{{ $c->motivo }}</td>
                          <td>{{ $c->estado }}</td>
                        </tr>
                      @empty
                        <tr><td colspan="5" class="text-center">Sin registros</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-12">
                <h6 class="mb-2">Triaje por cita</h6>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Edad</th>
                        <th>Talla</th>
                        <th>Peso</th>
                        <th>BMI</th>
                        <th>Grosor Piel</th>
                        <th>Observaciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $printedRows = 0; @endphp
                      @foreach($citas as $c)
                        @if($c->triaje)
                          @php $printedRows++; @endphp
                          <tr>
                            <td>{{ $c->fecha_cita }}</td>
                            <td>{{ $c->triaje->edad ?? '-' }}</td>
                            <td>{{ $c->triaje->talla ?? '-' }}</td>
                            <td>{{ $c->triaje->peso ?? '-' }}</td>
                            <td>{{ $c->triaje->BMI ?? '-' }}</td>
                            <td>{{ $c->triaje->grosor_piel ?? '-' }}</td>
                            <td>{{ $c->triaje->observaciones ?? '-' }}</td>
                          </tr>
                        @endif
                      @endforeach
                      @if($printedRows === 0)
                        <tr><td colspan="7" class="text-center">Sin registros</td></tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-12">
                <h6 class="mb-2">Predicciones por cita</h6>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Fecha</th>
                        <th>Resultado</th>
                        <th>Observación</th>
                        <th>Tiempo (s)</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $printedRows = 0; @endphp
                      @foreach($citas as $c)
                        @if($c->prediccion)
                          @php $printedRows++; @endphp
                          <tr>
                            <td>{{ $c->fecha_cita }}</td>
                            <td>{{ number_format($c->prediccion->resultado, 2) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($c->prediccion->observacion, 60) }}</td>
                            <td>{{ $c->prediccion->timer }}</td>
                            <td>
                              <a href="{{ route('predicciones.show', $c->prediccion->idprediccion) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                      @if($printedRows === 0)
                        <tr><td colspan="5" class="text-center">Sin registros</td></tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
