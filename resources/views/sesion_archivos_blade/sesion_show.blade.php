<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Sesión — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detalle.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">🏋️ Detalle de Sesión</h1>
        <div class="Acciones_pagina_sb">
            <a href="{{ route('sesiones.index') }}" class="Boton Boton_Secundario">← Volver</a>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Tarjeta" style="max-width:500px;">
        <div class="Detalle">
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Cliente</span><span class="Detalle_Valor">{{ $sesion->cliente->nombre ?? '—' }} {{ $sesion->cliente->apaterno ?? '' }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">CI</span><span class="Detalle_Valor">{{ $sesion->ci_cliente }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Entrada</span><span class="Detalle_Valor">{{ \Carbon\Carbon::parse($sesion->Inicio)->format('d/m/Y H:i') }}</span></div>
            <div class="Detalle_Fila">
                <span class="Detalle_Etiqueta">Salida</span>
                <span class="Detalle_Valor">{{ $sesion->Final ? \Carbon\Carbon::parse($sesion->Final)->format('d/m/Y H:i') : 'Aún en gym' }}</span>
            </div>
            @if($sesion->Inicio && $sesion->Final)
            <div class="Detalle_Fila">
                <span class="Detalle_Etiqueta">Duración</span>
                <span class="Detalle_Valor">
                    @php
                        $totalMins = (int) \Carbon\Carbon::parse($sesion->Inicio)->diffInMinutes(\Carbon\Carbon::parse($sesion->Final));
                        $horas = intdiv($totalMins, 60); $minutos = $totalMins % 60;
                        echo $horas > 0 ? "{$horas}h {$minutos}min" : "{$totalMins}min";
                    @endphp
                </span>
            </div>
            @endif
            <div class="Detalle_Fila">
                <span class="Detalle_Etiqueta">Empleado</span>
                <span class="Detalle_Valor">{{ $detalle && $detalle->empleado ? $detalle->empleado->nombre.' '.$detalle->empleado->apaterno : '—' }}</span>
            </div>
            <div class="Detalle_Fila">
                <span class="Detalle_Etiqueta">Detalle</span>
                <span class="Detalle_Valor">{{ $detalle->Detalles ?? '—' }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>
