<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/clientes/cliente_form.css">
</head>
<body>

<div class="overlay">
    <h1> Editar Cliente</h1>
    <p class="ci-badge">CI: <span>{{ $cliente->Ci }}</span></p>

    <form action="{{ route('clientes.update', $cliente->Ci) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre *</label>
        <input type="text" name="nombre" value="{{ old('nombre', $cliente->nombre) }}">
        @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

        <label>Apellido Paterno *</label>
        <input type="text" name="apaterno" value="{{ old('apaterno', $cliente->apaterno) }}">
        @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

        <label>Apellido Materno</label>
        <input type="text" name="amaterno" value="{{ old('amaterno', $cliente->amaterno) }}">
        @error('amaterno') <p class="error-msg">{{ $message }}</p> @enderror

        <div class="botones">
            <a href="{{ route('clientes.index') }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar">Actualizar</button>
        </div>
    </form>
</div>

</body>
</html>