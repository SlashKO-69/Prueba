<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Empleado — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">👤 Detalle del Empleado</h1>
        <div class="page-actions">
            <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="btn btn-warning">✏️ Editar</a>
            <a href="{{ route('empleados.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div class="card" style="max-width:500px;">
        <div class="detalle">
            <div class="detalle-fila"><span class="detalle-label">CI</span><span class="detalle-valor">{{ $empleado->ci_empleado }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Nombre</span><span class="detalle-valor">{{ $empleado->nombre }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Ap. Paterno</span><span class="detalle-valor">{{ $empleado->apaterno }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Ap. Materno</span><span class="detalle-valor">{{ $empleado->amaterno ?? '—' }}</span></div>
            <div class="detalle-fila"><span class="detalle-label">Celular</span><span class="detalle-valor">{{ $empleado->celular ?? '—' }}</span></div>
            <div class="detalle-fila">
                <span class="detalle-label">Rol</span>
                <span class="badge {{ $empleado->rol==='admin'?'badge-activo':'badge-pendiente' }}">{{ ucfirst($empleado->rol) }}</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>