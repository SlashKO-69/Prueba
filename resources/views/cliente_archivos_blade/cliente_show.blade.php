<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Cliente — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">👤 Detalle del Cliente</h1>
        <div class="Acciones_Pagina">
            <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="Boton Boton_Aviso">✏️ Editar</a>
            <a href="{{ route('clientes.index') }}" class="Boton Boton_Secundario">← Volver</a>
        </div>
    </div>

    <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:flex-start;">
        <div class="Tarjeta" style="flex:1; min-width:280px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">DATOS PERSONALES</p>
            <div class="Detalle">
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">CI</span><span class="Detalle_Valor">{{ $cliente->Ci }}</span></div>
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Nombre</span><span class="Detalle_Valor">{{ $cliente->nombre }}</span></div>
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Ap. Paterno</span><span class="Detalle_Valor">{{ $cliente->apaterno }}</span></div>
                <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Ap. Materno</span><span class="Detalle_Valor">{{ $cliente->amaterno ?? '—' }}</span></div>
            </div>
        </div>

        <div class="Tarjeta" style="flex:2; min-width:300px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">HISTORIAL DE INSCRIPCIONES</p>
            @if($inscripciones->isEmpty())
                <p style="color:#555; font-size:13px;">Sin inscripciones.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Inscripción</th>
                            <th>Vencimiento</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripciones as $ins)
                        @php
                            $dias = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($ins->fecha_vencimiento), false);
                            if ($dias < 0) { $sc='Estado-vencido'; $st='Vencido'; }
                            elseif ($dias <= 5) { $sc='Estado-por-vencer'; $st='Por vencer'; }
                            else { $sc='Estado-activo'; $st='Activo'; }
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') }}</td>
                            <td>Bs. {{ number_format($ins->monto, 2) }}</td>
                            <td><span class="Estado {{ $sc }}">{{ $st }}</span></td>
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