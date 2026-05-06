<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Inicio de Sesión</title>
    {{-- Fondo global --}}
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    {{-- Estilos específicos del login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Bienvenida {{ session('nombre') }}!',
                text: "{{ session('success') }}",
                background: '#111',
                color: '#00ff66',
                confirmButtonColor: '#00ff66',
                timer: 2000,
                showConfirmButton: false,
                backdrop: 'rgba(0,0,0,0.8)'
            }).then(() => {
                @if(session('rol') === 'administradora')
                    window.location.href = "{{ route('dashboard.admin') }}";
                @elseif(session('rol') === 'recepcionista')
                    window.location.href = "{{ route('dashboard.recepcionista') }}";
                @endif
            });
        </script>
    @endif

    {{-- Mensaje de error --}}
    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Acceso denegado',
                text: "{{ session('error') }}",
                background: '#111',
                color: '#ff4444',
                confirmButtonColor: '#d33',
                backdrop: 'rgba(0,0,0,0.8)'
            });
        </script>
    @endif

    <div class="overlay">
        <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTrainner Logo" class="logo">
        <h1>Inicio de Sesión</h1>

        <form action="{{ route('authenticate') }}" method="POST">
            @csrf
            <label for="rol">Tipo de Operador:</label>
            <select name="rol" id="rol">
                <option value="administradora">Administradora</option>
                <option value="recepcionista">Recepcionista</option>
            </select>

            <label for="contraseña">Contraseña (solo administradora):</label>
            <input type="password" name="contraseña" id="contraseña">

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
