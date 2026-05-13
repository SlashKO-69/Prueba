<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Nuevo Empleado</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados/base.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                background: '#0a1428',
                color: '#ff4444',
                confirmButtonColor: '#d33',
                backdrop: 'rgba(0,0,0,0.8)'
            });
        </script>
    @endif

    <div class="overlay">
        <h1>Nuevo Empleado</h1>

        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <input type="hidden" name="rol" value="empleado">

            <label for="ci_empleado">CI:</label>
            <input type="text" name="ci_empleado" id="ci_empleado"
                   value="{{ old('ci_empleado') }}" placeholder="Ej: 12345678" required>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre') }}" placeholder="Ej: Juan" required>

            <label for="apaterno">Apellido Paterno:</label>
            <input type="text" name="apaterno" id="apaterno"
                   value="{{ old('apaterno') }}" placeholder="Ej: Pérez" required>

            <label for="amaterno">Apellido Materno:</label>
            <input type="text" name="amaterno" id="amaterno"
                   value="{{ old('amaterno') }}" placeholder="Ej: López">

            <label for="celular">Celular:</label>
            <input type="text" name="celular" id="celular"
                   value="{{ old('celular') }}" placeholder="Ej: 70000000">

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password"
                   placeholder="Mínimo 6 caracteres" required>

            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input type="password" name="password_confirmation"
                   id="password_confirmation" placeholder="Repite la contraseña" required>

            <div class="botones">
                <button type="submit">Guardar</button>
                <a href="{{ route('empleados.index') }}" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>

</body>
</html>