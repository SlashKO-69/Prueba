<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Empleado — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">➕ Nuevo Empleado</h1>
    </div>

    <div class="Tarjeta" style="max-width:500px;">
        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf

            <label class="Form_Etiqueta">CI *</label>
            <input type="text" name="ci_empleado" class="Form_Input" value="{{ old('ci_empleado') }}">
            @error('ci_empleado') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Nombre *</label>
            <input type="text" name="nombre" class="Form_Input" value="{{ old('nombre') }}">
            @error('nombre') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="Form_Input" value="{{ old('apaterno') }}">
            @error('apaterno') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Materno</label>
            <input type="text" name="amaterno" class="Form_Input" value="{{ old('amaterno') }}" placeholder="Opcional">

            <label class="Form_Etiqueta">Celular</label>
            <input type="text" name="celular" class="Form_Input" value="{{ old('celular') }}" placeholder="Opcional">

            <label class="Form_Etiqueta">Rol *</label>
            <select name="rol" class="Form_Select" required>
                <option value="empleado" {{ old('rol')=='empleado'?'selected':'' }}>Empleado</option>
                <option value="admin" {{ old('rol')=='admin'?'selected':'' }}>Administrador</option>
            </select>
            @error('rol') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Contraseña *</label>
            <input type="password" name="password" class="Form_Input">
            @error('password') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Confirmar Contraseña *</label>
            <input type="password" name="password_confirmation" class="Form_Input">

            <div class="Form_Botones">
                <a href="{{ route('empleados.index') }}" class="Boton Boton_Secundario">Cancelar</a>
                <button type="submit" class="Boton Boton_Principal">Guardar</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
