@extends('layouts.app')

@section('title', 'Costos Operacionales - Doctores')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Costos Operacionales por Doctor</h5>
          <a href="{{ route('doctores.costos.export') }}" class="btn btn-success">
            <i class="bx bx-export"></i> Exportar Excel
          </a>
        </div>
        <div class="card-body table-responsive p-0">
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Doctor</th>
                <th>Predicciones</th>
                <th>Tiempo Total(m)</th>
                <th>Salario</th>
                <th>Costo por hora</th>
                <th>Costo Total</th>
                <th>COPG</th>
              </tr>
            </thead>
            <tbody>
              @forelse($stats as $row)
                @php
                  $seconds = (int) round($row['total_time']);
                  $h = intdiv($seconds, 3600);
                  $m = intdiv($seconds % 3600, 60);
                  $s = $seconds % 60;
                  $salary = (float) ($row['doctor']->sueldo ?? 0);
                  $hourly = $salary / 192; // costo por hora
                  $totalHours = $seconds / 3600; // segundos a horas
                  $totalCost = $hourly * $totalHours; // costo total por tiempo usado
                  $copg = $row['pred_count'] > 0 ? ($totalCost / $row['pred_count']) : 0;
                @endphp
                <tr>
                  <td>{{ $row['doctor']->nombre }} {{ $row['doctor']->apellido }}</td>
                  <td>{{ $row['pred_count'] }}</td>
                  <td>{{ sprintf('%02d:%02d:%02d', $h, $m, $s) }}</td>
                  <td>S/. {{ number_format($salary, 2) }}</td>
                  <td>S/. {{ number_format($hourly, 2) }}</td>
                  <td>S/. {{ number_format($totalCost, 2) }}</td>
                  <td>S/. {{ number_format($copg, 2) }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center">Sin datos</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
