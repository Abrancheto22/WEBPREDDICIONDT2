@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <!-- Tarjeta de Total de Predicciones -->
    <div class="col-md-6 col-xxl-3 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="me-2">
              <h6 class="mb-0">Total de Predicciones</h6>
              <h2 class="mb-2 mt-3">{{ number_format($totalPredicciones) }}</h2>
              <p class="mb-0">Predicciones realizadas en total</p>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-success rounded">
                <i class="bx bx-bar-chart-alt-2 bx-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Tarjeta de Tiempo Promedio por Predicción -->
    <div class="col-md-6 col-xxl-3 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="me-2">
              <h6 class="mb-0">Tiempo Promedio</h6>
              @php
                  $promedioMinutos = floor($tiempoPromedio / 60);
                  $promedioSegundos = floor($tiempoPromedio % 60);
                  $promedioMilisegundos = round(fmod($tiempoPromedio, 1) * 100);
              @endphp
              <h4 class="mb-2 mt-3">
                  @if($promedioMinutos > 0) {{ $promedioMinutos }}<small class="text-muted">m </small> @endif
                  {{ $promedioSegundos }}<small class="text-muted">s </small>
                  {{ str_pad($promedioMilisegundos, 2, '0', STR_PAD_LEFT) }}<small class="text-muted">ms</small>
              </h4>
              <p class="mb-0">Tiempo promedio por predicción</p>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-info rounded">
                <i class="bx bx-timer bx-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Tarjeta de Tiempo Total de Predicciones -->
    <div class="col-md-6 col-xxl-3 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div class="me-2">
              <h6 class="mb-0">Tiempo Total</h6>
              @php
                  $minutos = floor($totalTiempoPrediccion / 60);
                  $segundos = floor($totalTiempoPrediccion % 60);
                  $milisegundos = round(fmod($totalTiempoPrediccion, 1) * 100);
              @endphp
              <h4 class="mb-2 mt-3">
                  @if($minutos > 0) {{ $minutos }}<small class="text-muted">m </small> @endif
                  {{ $segundos }}<small class="text-muted">s </small>
                  {{ str_pad($milisegundos, 2, '0', STR_PAD_LEFT) }}<small class="text-muted">ms</small>
              </h4>
              <p class="mb-0">Tiempo total de todas las predicciones</p>
            </div>
            <div class="avatar">
              <div class="avatar-initial bg-label-primary rounded">
                <i class="bx bx-time-five bx-lg"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 mb-4">
      <div class="card mb-4">
        <div class="card-body">
          <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
              <label for="year" class="form-label">Año</label>
              @php $currentYear = now()->year; @endphp
              <select id="year" name="year" class="form-select">
                @for($y = $currentYear; $y >= $currentYear - 5; $y--)
                  <option value="{{ $y }}" {{ (isset($selectedYear) && (int)$selectedYear === $y) ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
              </select>
            </div>
            <div class="col-md-4">
              <label for="month" class="form-label">Mes</label>
              @php $monthNames = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre']; @endphp
              <select id="month" name="month" class="form-select">
                <option value="">Todos</option>
                @for($m = 1; $m <= 12; $m++)
                  <option value="{{ $m }}" {{ (isset($selectedMonth) && (int)$selectedMonth === $m) ? 'selected' : '' }}>{{ $monthNames[$m] }}</option>
                @endfor
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Aplicar</button>
            </div>
          </form>
          <br>
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
</div>
@endsection

@push('scripts')
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
@endpush
