<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Ver Empleado</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados/detalle.css') }}">
</head>
<body>

    <div class="overlay">
        <h1>Detalle del Empleado</h1>

        <div class="detalle">
            <div class="detalle-fila">
                <span class="detalle-label">CI</span>
                <span class="detalle-valor">{{ $empleado->ci_empleado }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Nombre</span>
                <span class="detalle-valor">{{ $empleado->nombre }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Ap. Paterno</span>
                <span class="detalle-valor">{{ $empleado->apaterno }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Ap. Materno</span>
                <span class="detalle-valor">{{ $empleado->amaterno ?? '—' }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Celular</span>
                <span class="detalle-valor">{{ $empleado->celular ?? '—' }}</span>
            </div>
            <div class="detalle-fila">
                <span class="detalle-label">Rol</span>
                <span class="badge-rol {{ $empleado->rol === 'admin' ? 'badge-admin' : 'badge-empleado' }}">
                    {{ $empleado->rol === 'admin' ? 'Administrador' : 'Empleado' }}
                </span>
            </div>
        </div>

        <div class="botones">
            <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="btn-guardar">Editar</a>
            <a href="{{ route('empleados.index') }}" class="btn-cancelar">Volver</a>
        </div>
    </div>

</body>
</html>