<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">✏️ Editar Cliente</h1>
        <p style="color:#888; font-size:13px; margin-top:4px;">CI: <span style="color:#2ECC71;">{{ $cliente->Ci }}</span></p>
    </div>

    <div class="Tarjeta" style="max-width:500px;">
        <form action="{{ route('clientes.update', $cliente->Ci) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="Form_Etiqueta">Nombre *</label>
            <input type="text" name="nombre" class="Form_Input" value="{{ old('nombre', $cliente->nombre) }}">
            @error('nombre') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="Form_Input" value="{{ old('apaterno', $cliente->apaterno) }}">
            @error('apaterno') <p class="Form_Error">{{ $message }}</p> @enderror

            <label class="Form_Etiqueta">Apellido Materno</label>
            <input type="text" name="amaterno" class="Form_Input" value="{{ old('amaterno', $cliente->amaterno) }}">

            <div class="Form_Botones">
                <a href="{{ route('clientes.index') }}" class="Boton Boton_Secundario">Cancelar</a>
                <button type="submit" class="Boton Boton_Principal">Actualizar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>