<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">✏️ Editar Cliente</h1>
        <p style="color:#888; font-size:13px; margin-top:4px;">CI: <span style="color:#2ECC71;">{{ $cliente->Ci }}</span></p>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ route('clientes.update', $cliente->Ci) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-input" value="{{ old('nombre', $cliente->nombre) }}">
            @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="form-input" value="{{ old('apaterno', $cliente->apaterno) }}">
            @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Materno</label>
            <input type="text" name="amaterno" class="form-input" value="{{ old('amaterno', $cliente->amaterno) }}">

            <div class="form-botones">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>