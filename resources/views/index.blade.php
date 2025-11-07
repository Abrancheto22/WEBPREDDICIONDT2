@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  @if(auth()->user() && auth()->user()->idrol == 1)
  <div class="row">
    <!-- Filtro global a ancho completo -->
    <div class="col-12 mb-3">
      <div class="card h-100">
        <div class="card-body py-3">
          <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
              <label for="year" class="form-label mb-2">Año</label>
              @php $currentYear = now()->year; @endphp
              <select id="year" name="year" class="form-select">
                @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                  <option value="{{ $y }}" {{ (isset($selectedYear) && (int)$selectedYear === $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
            <div class="col-12 col-md-4">
              <label for="month" class="form-label mb-2">Mes</label>
              @php $monthNames = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; @endphp
              <select id="month" name="month" class="form-select">
                <option value="">Todos</option>
                @for($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ (isset($selectedMonth) && (int)$selectedMonth === $m) ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                @endfor
              </select>
            </div>
            <div class="col-12 col-md-4 d-grid">
              <label class="form-label d-none d-md-block mb-2">&nbsp;</label>
              <button type="submit" class="btn btn-primary">Aplicar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Columna izquierda -->
    <div class="col-12 col-lg-6">

    <!-- Resumen -->
    <div class="col-12 mb-3">
      <div class="card h-100">
        <div class="card-header py-2 d-flex align-items-center justify-content-between">
          <h6 class="mb-0">Resumen</h6>
        </div>
        <div class="card-body py-3">
          <div class="row g-2">
            <div class="col-12 col-md-4">
              <div class="border rounded p-3 h-100 d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">Total de Predicciones</div>
                  <div class="display-6 fs-3 fw-semibold mb-0">{{ number_format($totalPredicciones) }}</div>
                  <div class="text-muted small">Predicciones realizadas en total</div>
                </div>
                <span class="badge bg-label-success"><i class="bx bx-bar-chart-alt-2"></i></span>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="border rounded p-3 h-100 d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">Tiempo Total</div>
                  @php
                      $minutos = floor($totalTiempoPrediccion / 60);
                      $segundos = floor($totalTiempoPrediccion % 60);
                      $milisegundos = round(fmod($totalTiempoPrediccion, 1) * 100);
                  @endphp
                  <div class="fs-5 fw-semibold mb-0">
                      @if($minutos > 0) {{ $minutos }}<small class="text-muted">m </small> @endif
                      {{ $segundos }}<small class="text-muted">s </small>
                      {{ str_pad($milisegundos, 2, '0', STR_PAD_LEFT) }}<small class="text-muted">ms</small>
                  </div>
                  <div class="text-muted small">Tiempo total de todas las predicciones</div>
                </div>
                <span class="badge bg-label-primary"><i class="bx bx-time-five"></i></span>
              </div>
            </div>
            <div class="col-12 col-md-4">
              <div class="border rounded p-3 h-100 d-flex align-items-center justify-content-between">
                <div>
                  <div class="text-muted small">Tiempo Promedio</div>
                  @php
                      $promedioMinutos = floor($tiempoPromedio / 60);
                      $promedioSegundos = floor($tiempoPromedio % 60);
                      $promedioMilisegundos = round(fmod($tiempoPromedio, 1) * 100);
                  @endphp
                  <div class="fs-5 fw-semibold mb-0">
                      @if($promedioMinutos > 0) {{ $promedioMinutos }}<small class="text-muted">m </small> @endif
                      {{ $promedioSegundos }}<small class="text-muted">s </small>
                      {{ str_pad($promedioMilisegundos, 2, '0', STR_PAD_LEFT) }}<small class="text-muted">ms</small>
                  </div>
                  <div class="text-muted small">Tiempo promedio por predicción</div>
                </div>
                <span class="badge bg-label-info"><i class="bx bx-timer"></i></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div><!-- /Columna izquierda -->

    <!-- Columna derecha -->
    <div class="col-12 col-lg-6">
      <!-- Doctor -->
      <div class="col-12 mb-3">
        <div class="card h-100">
          <div class="card-header py-2 d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Doctor</h6>
          </div>
          <div class="card-body py-3">
            <form method="GET" action="{{ route('dashboard') }}" class="row g-2 align-items-end mb-2">
              <input type="hidden" name="year" value="{{ $selectedYear }}">
              <input type="hidden" name="month" value="{{ $selectedMonth }}">
              <div class="col-8">
                <label for="doctor_id" class="form-label mb-1">Seleccionar</label>
                <select id="doctor_id" name="doctor_id" class="form-select form-select-sm">
                  <option value="">Seleccione un doctor</option>
                  @foreach(($doctores ?? []) as $doc)
                    <option value="{{ $doc->iddoctor }}" {{ (isset($selectedDoctorId) && (int)$selectedDoctorId === (int)$doc->iddoctor) ? 'selected' : '' }}>
                      {{ $doc->nombre }} {{ $doc->apellido }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-4 d-grid">
                <button type="submit" class="btn btn-secondary btn-sm">Cargar</button>
              </div>
            </form>

            @if(!empty($selectedDoctorId))
            <div class="row g-2">
              <div class="col-6">
                <div class="border rounded p-2 h-100 d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">Predicciones</div>
                    <div class="fw-semibold">{{ number_format($doctorPredCount ?? 0) }}</div>
                  </div>
                  <span class="badge bg-label-warning"><i class="bx bx-user-check"></i></span>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-2 h-100 d-flex align-items-center justify-content-between">
                  <div>
                    <div class="text-muted small">COPG</div>
                    <div class="fw-semibold">S/ {{ number_format(($doctorCOPG ?? 0), 2) }}</div>
                  </div>
                  <span class="badge bg-label-dark"><i class="bx bx-dollar"></i></span>
                </div>
              </div>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div><!-- /Columna derecha -->

    

    <!-- Gráfico -->
    <div class="col-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="mb-0">
              @if(($trendGranularity ?? 'monthly') === 'daily')
                Tendencia diaria de pacientes con diabetes ({{ $monthNames[$selectedMonth] ?? '' }} {{ $selectedYear ?? '' }})
              @else
                Tendencia mensual de pacientes con diabetes ({{ $selectedYear ?? '' }})
              @endif
            </h6>
            <div class="avatar">
              <div class="avatar-initial bg-label-danger rounded">
                <i class="bx bx-trending-up bx-lg"></i>
              </div>
            </div>
          </div>
          <canvas id="diabetesTrendChart" height="80"></canvas>
        </div>
      </div>
    </div>
  </div>
  @else
  <div class="row">
    <div class="col-12">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Dashboard</h5>
          <p class="mb-0">Bienvenido. Actualmente no tienes acceso a los indicadores avanzados del panel. Contacta al administrador si necesitas más permisos.</p>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@push('scripts')
@if(auth()->user() && auth()->user()->idrol == 1)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
  (function() {
    const labels = @json($trendLabels ?? []);
    const dataCounts = @json($trendCounts ?? []);
    const dataCountsNeg = @json($trendCountsNeg ?? []);

    const ctx = document.getElementById('diabetesTrendChart');
    if (!ctx) return;

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [
          {
            label: 'Positivos (Diabetes)',
            data: dataCounts,
            fill: false,
            borderColor: 'rgba(220, 53, 69, 1)',
            backgroundColor: 'rgba(220, 53, 69, 0.2)',
            tension: 0.3,
            pointRadius: 3,
            borderWidth: 2
          },
          {
            label: 'Negativos (Sin diabetes)',
            data: dataCountsNeg,
            fill: false,
            borderColor: 'rgba(13, 110, 253, 1)',
            backgroundColor: 'rgba(13, 110, 253, 0.2)',
            tension: 0.3,
            pointRadius: 3,
            borderWidth: 2
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: true },
          tooltip: { mode: 'index', intersect: false }
        },
        interaction: { mode: 'index', intersect: false },
        scales: {
          x: { title: { display: true, text: '{{ (($trendGranularity ?? 'monthly') === 'daily') ? 'Día' : 'Mes' }}' } },
          y: { beginAtZero: true, title: { display: true, text: 'Cantidad' }, ticks: { precision: 0 } }
        }
      }
    });
  })();
</script>
@endif
@endpush
