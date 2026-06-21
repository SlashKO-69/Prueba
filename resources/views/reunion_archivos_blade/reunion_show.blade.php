<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Reunión — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">📅 Detalle de Reunión</h1>
        <div class="Acciones_pagina_sb">
            <a href="{{ route('reuniones.index') }}" class="Boton Boton_Secundario">← Volver</a>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:flex-start;">

        <div class="Tarjeta" style="flex:1; min-width:250px;">
            <p class="Detalle_Etiqueta" style="font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">DATOS DE LA REUNIÓN</p>
            <div class="Detalle">
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Fecha</span><span class="Detalle_Valor">{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</span></div>
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Hora</span><span class="Detalle_Valor">{{ $reunion->hora_reunion }}</span></div>
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Motivo</span><span class="Detalle_Valor">{{ $reunion->motivo }}</span></div>
            </div>
        </div>

        <div class="Tarjeta" style="flex:2; min-width:300px;">
            <p class="Detalle_Etiqueta" style="font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">CONTROL DE ASISTENCIA</p>
            @php $asistencia = $reunion->asistencia ?? []; @endphp
            @if($empleados->isEmpty())
                <p style="color:#555; font-size:13px;">No hay empleados registrados.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>CI</th>
                            <th>Estado</th>
                            <th>Marcar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $emp)
                        @php
                            $estado = $asistencia[$emp->ci_empleado] ?? 'pendiente';
                            $sc = $estado === 'asistió' ? 'Estado-activo' : ($estado === 'no asistió' ? 'Estado-vencido' : 'Estado-pendiente');
                        @endphp
                        <tr>
                            <td>{{ $emp->nombre }} {{ $emp->apaterno }}</td>
                            <td>{{ $emp->ci_empleado }}</td>
                            <td><span class="Estado {{ $sc }}">{{ ucfirst($estado) }}</span></td>
                            <td>
                                <form action="{{ route('reuniones.asistencia', $reunion->id_reunion) }}" method="POST" class="acciones">
                                    @csrf
                                    <input type="hidden" name="ci_empleado" value="{{ $emp->ci_empleado }}">
                                    <button type="submit" name="estado" value="asistió" class="Boton Boton_Principal" style="padding:5px 10px; font-size:12px;">✓</button>
                                    <button type="submit" name="estado" value="no asistió" class="Boton btn-danger" style="padding:5px 10px; font-size:12px;">✗</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

    </div>
</div>
</body>
</html>
