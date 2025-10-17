<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Predicción Médica</title>
</head>
<body>
    <h1>Reporte de Predicción Médica</h1>
    <p>Estimado/a paciente,</p>
    <p>Adjunto encontrará el reporte de predicción médica para el paciente <strong>{{ $prediccion->cita->paciente->nombre }} {{ $prediccion->cita->paciente->apellido }}</strong>.</p>
    <p>Este reporte incluye el análisis de IA y los parámetros evaluados.</p>
    <p>Saludos cordiales,</p>
    <p>El equipo de Predicción Médica</p>
</body>
</html>