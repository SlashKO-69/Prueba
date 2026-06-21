<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
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

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Correcto',
        text: "{{ session('success') }}",
        background: '#0a1428',
        color: '#2ECC71',
        confirmButtonColor: '#2ECC71',
        backdrop: 'rgba(0,0,0,0.8)'
    });
</script>
@endif

<div class="login-box">
    <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTrainer" class="login-logo">

    <form action="{{ route('empleados.login') }}" method="POST">
        @csrf

        <label for="ci_empleado">CI del empleado</label>
        <input type="text" name="ci_empleado" id="ci_empleado"
               value="{{ old('ci_empleado') }}" placeholder="Ej: 12345678" autofocus>
        @error('ci_empleado') <p class="error-field">{{ $message }}</p> @enderror

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="••••••••">
        @error('password') <p class="error-field">{{ $message }}</p> @enderror

        <button type="submit">Ingresar</button>
    </form>
</div>
</body>
</html>