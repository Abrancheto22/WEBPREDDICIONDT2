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
    
    <div class="col-xxl-8 mb-6 order-0">
      <div class="card">
        <div class="d-flex align-items-start row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary mb-3">Bienvenido a la Predicción de DT</h5>
              <p class="mb-6">
                Sistema para la predicción de resultados en la disciplina de DT.
              </p>
              <a href="javascript:;" class="btn btn-sm btn-outline-primary">Ver Predicciones</a>
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
              <img
                src="/plantilla/assets/img/illustrations/man-with-laptop.png"
                height="175"
                alt="View Badge User" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
