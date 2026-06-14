<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Inscripción — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📋 Detalle Inscripción</h1>
        <div class="page-actions">
            <a href="{{ route('inscripciones.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    @php
        $dias = $inscripcion->dias_restantes;
        if ($dias < 0) { $st='Vencido'; $color='#dc3545'; }
        elseif ($dias <= 5) { $st='Por vencer'; $color='#ffc107'; }
        else { $st='Activo'; $color='#2ECC71'; }
    @endphp

    <div class="card" style="max-width:500px;">
        <div class="detalle">
            <div class="detalle-fila"><span class="detalle-label">Cliente</span><span class="detalle-valor">{{ $inscripcion->cliente->nombre ?? '—' }} {{ $inscripcion->cliente->apaterno ?? '' }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">CI</span><span class="detalle-valor">{{ $inscripcion->ci_cliente }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Inscripción</span><span class="detalle-valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Vencimiento</span><span class="detalle-valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_vencimiento)->format('d/m/Y') }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Días restantes</span><span class="detalle-valor" style="color:{{ $color }};font-weight:600;">{{ $dias < 0 ? abs($dias).' días vencido' : $dias.' días' }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Monto</span><span class="detalle-valor">Bs. {{ number_format($inscripcion->monto, 2) }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Promoción</span><span class="detalle-valor">{{ $inscripcion->promocion ? $inscripcion->promocion->porcentaje_descuento.'% — '.$inscripcion->promocion->requisito : 'Sin promoción' }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Estado</span><span class="detalle-valor" style="color:{{ $color }};font-weight:600;">{{ $st }}</span></div>
        </div>
    </div>
</div>

</body>
</html>