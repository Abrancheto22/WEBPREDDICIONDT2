<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Predicción de Diabetes</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #007bff;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 18px;
            color: #0056b3;
            border-bottom: 1px solid #0056b3;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #000;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .info-item {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
        }
        .info-item strong {
            color: #0056b3;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            color: #fff;
            font-size: 12px;
            font-weight: bold;
        }
        .bg-danger { background-color: #dc3545; }
        .bg-success { background-color: #28a745; }
        .bg-warning { background-color: #ffc107; }
        .bg-secondary { background-color: #6c757d; }
        .ai-analysis {
            margin-top: 20px;
            padding: 15px;
            background-color: #eef7ff;
            border-left: 4px solid #007bff;
            border-radius: 5px;
        }
        .ai-analysis h3 {
            font-size: 16px;
            color: #0056b3;
            margin-top: 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
        .ai-analysis-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ai-analysis-content table th {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #000;
            font-weight: 600;
            padding: 15px 12px;
            text-align: left;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .ai-analysis-content table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            font-size: 13px;
            color: #495057;
            vertical-align: top;
            line-height: 1.5;
        }

        .ai-analysis-content table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .ai-analysis-content table tr:hover {
            background-color: #e3f2fd;
            transition: background-color 0.3s ease;
        }

        .ai-analysis-content table tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte de Predicción de Diabetes</h1>
            <p>Fecha de Emisión: {{ date('d/m/Y H:i') }}</p>
        </div>

        <div class="section">
            <h2>Información de la Cita</h2>
            <table class="table">
                <tr>
                    <th>ID Cita</th>
                    <td>{{ $prediccion->cita->idcita }}</td>
                    <th>Fecha</th>
                    <td>{{ date('d/m/Y', strtotime($prediccion->cita->fecha_cita)) }}</td>
                </tr>
                <tr>
                    <th>Hora</th>
                    <td>{{ date('H:i', strtotime($prediccion->cita->hora_cita)) }}</td>
                    <th>Estado</th>
                    <td>
                        <span class="badge {{ $prediccion->cita->estado === 'pendiente' ? 'bg-warning' : ($prediccion->cita->estado === 'atendida' ? 'bg-success' : 'bg-secondary') }}">
                            {{ ucfirst($prediccion->cita->estado) }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Datos del Paciente</h2>
            <table class="table">
                <tr>
                    <th>Nombre Completo</th>
                    <td>{{ $prediccion->cita->paciente->nombre }} {{ $prediccion->cita->paciente->apellido }}</td>
                    <th>DNI</th>
                    <td>{{ $prediccion->cita->paciente->DNI }}</td>
                </tr>
                <tr>
                    <th>Edad</th>
                    <td>{{ $prediccion->edad }} años</td>
                    <th>Sexo</th>
                    <td>{{ $prediccion->cita->paciente->sexo }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Parámetros de la Predicción</h2>
            <table class="table">
                <tr>
                    <th>Embarazos</th>
                    <td>{{ $prediccion->embarazos }}</td>
                    <th>Glucosa</th>
                    <td>{{ $prediccion->glucosa }} mg/dl</td>
                </tr>
                <tr>
                    <th>Presión Sanguínea</th>
                    <td>{{ $prediccion->presion_sanguinea }} mmHg</td>
                    <th>Grosor de Piel</th>
                    <td>{{ $prediccion->grosor_piel }} mm</td>
                </tr>
                <tr>
                    <th>Insulina</th>
                    <td>{{ $prediccion->insulina }} μU/ml</td>
                    <th>Índice de Masa Corporal (BMI)</th>
                    <td>{{ number_format($prediccion->BMI, 2) }} kg/m²</td>
                </tr>
                <tr>
                    <th>Función Pedigrí Diabetes</th>
                    <td colspan="3">{{ number_format($prediccion->pedigree, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h2>Resultado de la Predicción</h2>
            <table class="table">
                <tr>
                    <th>Probabilidad de Diabetes</th>
                    <td>{{ number_format($prediccion->resultado * 100, 2) }}%</td>
                </tr>
                <tr>
                    <th>Diagnóstico Final</th>
                    <td>
                        <span class="badge {{ $prediccion->resultado >= 0.5 ? 'bg-danger' : 'bg-success' }}">
                            {{ $prediccion->resultado >= 0.5 ? 'Positivo' : 'Negativo' }}
                        </span>
                    </td>
                </tr>
                @if($prediccion->observacion)
                <tr>
                    <th>Observaciones Médicas</th>
                    <td>{{ $prediccion->observacion }}</td>
                </tr>
                @endif
            </table>
        </div>

        @if($prediccion->analisis_ia)
        <div class="section ai-analysis">
            <h2>Análisis de Inteligencia Artificial</h2>
            <div class="ai-analysis-content">
                {!! $prediccion->analisis_ia !!}
            </div>
        </div>
        @endif

        <div class="footer">
            <p>Este es un reporte autogenerado por el sistema de predicción de diabetes.</p>
            <p>La información contenida debe ser interpretada por un profesional de la salud.</p>
        </div>
    </div>
</body>
</html>