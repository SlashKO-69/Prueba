<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente_show.css') }}">
</head>
<body>

<div class="overlay">
    <h1> Detalle del Cliente</h1>

    <div class="detalle">
        <div class="detalle-fila">
            <span class="detalle-label">CI</span>
            <span class="detalle-valor">{{ $cliente->Ci }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Nombre</span>
            <span class="detalle-valor">{{ $cliente->nombre }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Ap. Paterno</span>
            <span class="detalle-valor">{{ $cliente->apaterno }}</span>
        </div>
        <div class="detalle-fila">
            <span class="detalle-label">Ap. Materno</span>
            <span class="detalle-valor">{{ $cliente->amaterno ?? '—' }}</span>
        </div>
    </div>

    <div class="botones">
        <a href="{{ route('clientes.index') }}" class="btn-volver">← Volver</a>
        <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="btn-editar"> Editar</a>
    </div>
</div>

</body>
</html>