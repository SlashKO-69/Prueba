<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Inscripción — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">📋 Detalle Inscripción</h1>
        <div class="Acciones_pagina_sb">
            <a href="{{ route('inscripciones.index') }}" class="Boton Boton_Secundario">← Volver</a>
        </div>
    </div>

    @php
        $dias = $inscripcion->dias_restantes;
        if ($dias < 0) { $st='Vencido'; $dc='Dias_Peligro'; $sc='Estado-vencido'; }
        elseif ($dias <= 5) { $st='Por vencer'; $dc='Dias_Aviso'; $sc='Estado-por-vencer'; }
        else { $st='Activo'; $dc='Dias_Ok'; $sc='Estado-activo'; }
    @endphp

    <div class="Tarjeta" style="max-width:500px;">
        <div class="Detalle">
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Cliente</span><span class="Detalle_Valor">{{ $inscripcion->cliente->nombre ?? '—' }} {{ $inscripcion->cliente->apaterno ?? '' }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">CI</span><span class="Detalle_Valor">{{ $inscripcion->ci_cliente }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Inscripción</span><span class="Detalle_Valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Vencimiento</span><span class="Detalle_Valor">{{ \Carbon\Carbon::parse($inscripcion->fecha_vencimiento)->format('d/m/Y') }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Días restantes</span><span class="Detalle_Valor {{ $dc }}">{{ $dias < 0 ? abs($dias).' días vencido' : $dias.' días' }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Monto</span><span class="Detalle_Valor">Bs. {{ number_format($inscripcion->monto, 2) }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Promoción</span><span class="Detalle_Valor">{{ $inscripcion->promocion ? $inscripcion->promocion->porcentaje_descuento.'% — '.$inscripcion->promocion->requisito : 'Sin promoción' }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Estado</span><span class="Detalle_Valor Estado {{ $sc }}">{{ $st }}</span></div>
        </div>
    </div>
</div>

</body>
</html>
