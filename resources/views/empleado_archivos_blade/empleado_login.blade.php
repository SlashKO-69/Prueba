<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            min-height:100vh;
            font-family:'Poppins',sans-serif;
            background:
                linear-gradient(rgba(0,0,0,0.2),rgba(0,0,0,0.2)),
                url('/images/Aparatos gym 1.png') left center / 50% 100% no-repeat,
                url('/images/Aparatos gym 2.png') right center / 50% 100% no-repeat;
            background-blend-mode: darken;
            display:flex; justify-content:center; align-items:center;
        }
        body::after { content:""; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:-1; }

        .login-box {
            background:rgba(8,16,32,0.95);
            border:1px solid rgba(46,204,113,0.2);
            padding:44px;
            border-radius:14px;
            width:380px;
            box-shadow:0 0 40px rgba(46,204,113,0.15);
            backdrop-filter:blur(8px);
            text-align:center;
        }

        .login-logo {
        width: 250px;          /* tamaño más grande */
        height: 250px;
        object-fit: contain;
        border-radius: 16px;
        margin-bottom: -70px;    /* pegado al campo de CI */
        margin-top:-70px;
        }


        label { display:block; color:#ccc; font-size:13px; text-align:left; margin-top:16px; }

        input, select {
            width:100%; padding:11px 14px; margin-top:6px;
            border:1px solid rgba(46,204,113,0.2); border-radius:8px;
            background:#0d1a30; color:#eee; font-size:14px;
            font-family:'Poppins',sans-serif; transition:border 0.2s;
        }
        input:focus, select:focus { outline:none; border-color:#2ECC71; box-shadow:0 0 8px rgba(46,204,113,0.2); }
        select option { background:#0d1a30; color:#eee; }

        .error-field { color:#ff6b6b; font-size:12px; margin-top:5px; text-align:left; }

        button {
            width:100%; padding:12px; margin-top:24px;
            background:#2ECC71; border:none; border-radius:8px;
            color:#111; font-weight:700; font-size:15px;
            cursor:pointer; font-family:'Poppins',sans-serif; transition:background 0.2s;
        }
        button:hover { background:#27AE60; }
    </style>
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