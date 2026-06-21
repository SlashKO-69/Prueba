<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Empleado — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">👤 Detalle del Empleado</h1>
        <div class="Acciones_Pagina">
            <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="Boton Boton_Aviso">✏️ Editar</a>
            <a href="{{ route('empleados.index') }}" class="Boton Boton_Secundario">← Volver</a>
        </div>
    </div>

    <div class="Tarjeta" style="max-width:500px;">
        <div class="Detalle">
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">CI</span><span class="Detalle_Valor">{{ $empleado->ci_empleado }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Nombre</span><span class="Detalle_Valor">{{ $empleado->nombre }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Ap. Paterno</span><span class="Detalle_Valor">{{ $empleado->apaterno }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Ap. Materno</span><span class="Detalle_Valor">{{ $empleado->amaterno ?? '—' }}</span></div>
            <div class="Detalle_Fila"><span class="Detalle_Etiqueta">Celular</span><span class="Detalle_Valor">{{ $empleado->celular ?? '—' }}</span></div>
            <div class="Detalle_Fila">
                <span class="Detalle_Etiqueta">Rol</span>
                <span class="Estado {{ $empleado->rol==='admin'?'Estado-activo':'Estado-pendiente' }}">{{ ucfirst($empleado->rol) }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>