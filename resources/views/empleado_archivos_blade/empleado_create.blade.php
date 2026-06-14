<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">➕ Nuevo Empleado</h1>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <label class="form-label">CI *</label>
            <input type="text" name="ci_empleado" class="form-input" value="{{ old('ci_empleado') }}">
            @error('ci_empleado') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-input" value="{{ old('nombre') }}">
            @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="form-input" value="{{ old('apaterno') }}">
            @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Materno</label>
            <input type="text" name="amaterno" class="form-input" value="{{ old('amaterno') }}" placeholder="Opcional">

            <label class="form-label">Celular</label>
            <input type="text" name="celular" class="form-input" value="{{ old('celular') }}" placeholder="Opcional">

            <label class="form-label">Rol *</label>
            <select name="rol" class="form-select" required>
                <option value="empleado" {{ old('rol')=='empleado'?'selected':'' }}>Empleado</option>
                <option value="admin" {{ old('rol')=='admin'?'selected':'' }}>Administrador</option>
            </select>
            @error('rol') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Contraseña *</label>
            <input type="password" name="password" class="form-input">
            @error('password') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Confirmar Contraseña *</label>
            <input type="password" name="password_confirmation" class="form-input">

            <div class="form-botones">
                <a href="{{ route('empleados.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>