<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Inicio de Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    @if($errors->has('ci_empleado'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ $errors->first('ci_empleado') }}",
                background: '#0a1428',
                color: '#ff4444',
                confirmButtonColor: '#d33',
                backdrop: 'rgba(0,0,0,0.8)'
            });
        </script>
    @endif

    @if($errors->has('password'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Contraseña incorrecta',
                text: "{{ $errors->first('password') }}",
                background: '#0a1428',
                color: '#ff4444',
                confirmButtonColor: '#d33',
                backdrop: 'rgba(0,0,0,0.8)'
            });
        </script>
    @endif

    <div class="overlay">
        <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTrainner Logo" class="logo">
        <h1>Inicio de Sesión</h1>

        <form action="{{ route('empleados.login') }}" method="POST">
            @csrf

            <label for="rol">Rol:</label>
            <select name="rol" id="rol" required onchange="toggleCampos(this.value)">
                <option value="">-- Selecciona un rol --</option>
                <option value="empleado" {{ old('rol') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                <option value="admin" {{ old('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>

            <div id="campo_ci" style="display:none;">
                <label for="ci_empleado">CI del Empleado:</label>
                <input type="text" name="ci_empleado" id="ci_empleado"
                       value="{{ old('ci_empleado') }}" placeholder="Ej: 12345678">
            </div>

            <div id="campo_password" style="display:none;">
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" placeholder="••••••••">
            </div>

            <button type="submit">Ingresar</button>
        </form>
    </div>

    <script>
        function toggleCampos(rol) {
            const mostrar = rol !== '';
            document.getElementById('campo_ci').style.display       = mostrar ? 'block' : 'none';
            document.getElementById('campo_password').style.display = mostrar ? 'block' : 'none';
        }

        window.onload = function () {
            const rolActual = document.getElementById('rol').value;
            if (rolActual) toggleCampos(rolActual);
        };
    </script>

</body>
</html>