<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Editar Empleado</title>
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
        <h1>Editar Empleado</h1>

        <form action="{{ route('empleados.update', $empleado->ci_empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <label>CI:</label>
            <input type="text" value="{{ $empleado->ci_empleado }}" disabled>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre"
                   value="{{ old('nombre', $empleado->nombre) }}" required>

            <label for="apaterno">Apellido Paterno:</label>
            <input type="text" name="apaterno" id="apaterno"
                   value="{{ old('apaterno', $empleado->apaterno) }}" required>

            <label for="amaterno">Apellido Materno:</label>
            <input type="text" name="amaterno" id="amaterno"
                   value="{{ old('amaterno', $empleado->amaterno) }}">

            <label for="celular">Celular:</label>
            <input type="text" name="celular" id="celular"
                   value="{{ old('celular', $empleado->celular) }}">

            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required>
                <option value="empleado" {{ $empleado->rol === 'empleado' ? 'selected' : '' }}>Empleado</option>
                <option value="admin" {{ $empleado->rol === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>

            <label for="password">Nueva Contraseña <span style="color:#888; font-size:12px;">(dejar vacío para no cambiar)</span></label>
            <input type="password" name="password" id="password" placeholder="Mínimo 6 caracteres">

            <label for="password_confirmation">Confirmar Nueva Contraseña:</label>
            <input type="password" name="password_confirmation"
                   id="password_confirmation" placeholder="Repite la contraseña">

            <div class="botones">
                <button type="submit">Guardar Cambios</button>
                <a href="{{ route('empleados.index') }}" class="btn-cancelar">Cancelar</a>
            </div>
        </form>
    </div>

</body>
</html>