<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Sesión — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🏋️ Detalle de Sesión</h1>
        <div class="page-actions">
            <a href="{{ route('sesiones.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card" style="max-width:500px;">
        <div class="detalle">
            <div class="detalle-fila"><span class="detalle-label">Cliente</span><span class="detalle-valor">{{ $sesion->cliente->nombre ?? '—' }} {{ $sesion->cliente->apaterno ?? '' }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">CI</span><span class="detalle-valor">{{ $sesion->ci_cliente }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Entrada</span><span class="detalle-valor">{{ \Carbon\Carbon::parse($sesion->Inicio)->format('d/m/Y H:i') }}</span></div>
            <div class="detalle-fila">
                <span class="detalle-label">Salida</span>
                <span class="detalle-valor">{{ $sesion->Final ? \Carbon\Carbon::parse($sesion->Final)->format('d/m/Y H:i') : 'Aún en gym' }}</span>
            </div>
            @if($sesion->Inicio && $sesion->Final)
            <div class="detalle-fila">
                <span class="detalle-label">Duración</span>
                <span class="detalle-valor">
                    @php
                        $totalMins = (int) \Carbon\Carbon::parse($sesion->Inicio)->diffInMinutes(\Carbon\Carbon::parse($sesion->Final));
                        $horas = intdiv($totalMins, 60); $minutos = $totalMins % 60;
                        echo $horas > 0 ? "{$horas}h {$minutos}min" : "{$totalMins}min";
                    @endphp
                </span>
            </div>
            @endif
            <div class="detalle-fila">
                <span class="detalle-label">Empleado</span>
                <span class="detalle-valor">{{ $detalle && $detalle->empleado ? $detalle->empleado->nombre.' '.$detalle->empleado->apaterno : '—' }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Detalle</span>
                <span class="detalle-valor">{{ $detalle->Detalles ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>