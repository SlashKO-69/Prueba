<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Inscripción</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente_show.css') }}">
</head>
<body>

<div class="overlay">
    <h1>📋 Detalle Inscripción</h1>

    @php
        $dias = $inscripcion->dias_restantes;
        if ($dias < 0) {
            $estadoTexto = 'Vencido';
            $estadoColor = '#dc3545';
        } elseif ($dias <= 5) {
            $estadoTexto = 'Por vencer';
            $estadoColor = '#ffc107';
        } else {
            $estadoTexto = 'Activo';
            $estadoColor = '#2ECC71';
        }
    @endphp

    <div class="detalle">
        <div class="detalle-fila">
            <span class="detalle-label">Cliente</span>
            <span class="detalle-valor">
                {{ $inscripcion->cliente->nombre ?? '—' }}
                {{ $inscripcion->cliente->apaterno ?? '' }}
            </span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">CI</span>
            <span class="detalle-valor">{{ $inscripcion->ci_cliente }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Fecha inscripción</span>
            <span class="detalle-valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Vencimiento</span>
            <span class="detalle-valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_vencimiento)->format('d/m/Y') }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Días restantes</span>
            <span class="detalle-valor" style="color: {{ $estadoColor }}; font-weight:600;">
                {{ $dias < 0 ? abs($dias) . ' días vencido' : $dias . ' días' }}
            </span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Monto pagado</span>
            <span class="detalle-valor">Bs. {{ number_format($inscripcion->monto, 2) }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Promoción</span>
            <span class="detalle-valor">
                @if($inscripcion->promocion)
                    {{ $inscripcion->promocion->porcentaje_descuento }}% — {{ $inscripcion->promocion->requisito }}
                @else
                    Sin promoción
                @endif
            </span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Estado</span>
            <span class="detalle-valor" style="color: {{ $estadoColor }}; font-weight:600;">{{ $estadoTexto }}</span>
        </div>
    </div>

    <div class="botones">
        <a href="{{ route('inscripciones.index') }}" class="btn-volver">← Volver</a>
    </div>
</div>

</body>
</html>