<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">✏️ Editar Empleado</h1>
        <p style="color:#888; font-size:13px; margin-top:4px;">CI: <span style="color:#2ECC71;">{{ $empleado->ci_empleado }}</span></p>
    </div>

    <div class="Tarjeta" style="max-width:500px;">
        <form action="{{ route('empleados.update', $empleado->ci_empleado) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="Form_Etiqueta">Nombre *</label>
            <input type="text" name="nombre" class="Form_Input" value="{{ old('nombre', $empleado->nombre) }}">
            @error('nombre') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="Form_Input" value="{{ old('apaterno', $empleado->apaterno) }}">
            @error('apaterno') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Materno</label>
            <input type="text" name="amaterno" class="Form_Input" value="{{ old('amaterno', $empleado->amaterno) }}">

            <label class="Form_Etiqueta">Celular</label>
            <input type="text" name="celular" class="Form_Input" value="{{ old('celular', $empleado->celular) }}">

            <label class="Form_Etiqueta">Rol *</label>
            <select name="rol" class="Form_Select" required>
                <option value="empleado" {{ $empleado->rol=='empleado'?'selected':'' }}>Empleado</option>
                <option value="admin" {{ $empleado->rol=='admin'?'selected':'' }}>Administrador</option>
            </select>

            <label class="Form_Etiqueta">Nueva Contraseña <span style="color:#555;">(dejar vacío para no cambiar)</span></label>
            <input type="password" name="password" class="Form_Input">
            @error('password') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="Form_Input">

            <div class="Form_Botones">
                <a href="{{ route('empleados.index') }}" class="Boton Boton_Secundario">Cancelar</a>
                <button type="submit" class="Boton Boton_Principal">Actualizar</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>