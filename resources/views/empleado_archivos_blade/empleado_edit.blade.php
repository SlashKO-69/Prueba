<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">✏️ Editar Empleado</h1>
        <p style="color:#888; font-size:13px; margin-top:4px;">CI: <span style="color:#2ECC71;">{{ $empleado->ci_empleado }}</span></p>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ route('empleados.update', $empleado->ci_empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-input" value="{{ old('nombre', $empleado->nombre) }}">
            @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="form-input" value="{{ old('apaterno', $empleado->apaterno) }}">
            @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Materno</label>
            <input type="text" name="amaterno" class="form-input" value="{{ old('amaterno', $empleado->amaterno) }}">

            <label class="form-label">Celular</label>
            <input type="text" name="celular" class="form-input" value="{{ old('celular', $empleado->celular) }}">

            <label class="form-label">Rol *</label>
            <select name="rol" class="form-select" required>
                <option value="empleado" {{ $empleado->rol=='empleado'?'selected':'' }}>Empleado</option>
                <option value="admin" {{ $empleado->rol=='admin'?'selected':'' }}>Administrador</option>
            </select>

            <label class="form-label">Nueva Contraseña <span style="color:#555;">(dejar vacío para no cambiar)</span></label>
            <input type="password" name="password" class="form-input">
            @error('password') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="form-input">

            <div class="form-botones">
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>