<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/clientes/cliente_form.css">
</head>
<body>

<div class="overlay">
    <h1>➕ Nuevo Cliente</h1>

    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf

        <label>CI *</label>
        <input type="text" name="Ci" value="{{ old('Ci') }}" placeholder="Ej: 12345678">
        @error('Ci') <p class="error-msg">{{ $message }}</p> @enderror

        <label>Nombre *</label>
        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Juan">
        @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

        <label>Apellido Paterno *</label>
        <input type="text" name="apaterno" value="{{ old('apaterno') }}" placeholder="Ej: García">
        @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

        <label>Apellido Materno</label>
        <input type="text" name="amaterno" value="{{ old('amaterno') }}" placeholder="Opcional">
        @error('amaterno') <p class="error-msg">{{ $message }}</p> @enderror

        <div style="border-top: 1px solid rgba(46,204,113,0.2); margin-top: 20px; padding-top: 15px;">
            <p style="color: #2ECC71; font-size: 13px; font-weight: 600; margin-bottom: 5px;">📋 INSCRIPCIÓN</p>

            <label>Meses a inscribir *</label>
            <input type="number" name="meses" value="{{ old('meses', 1) }}" min="1" max="24"
                   oninput="calcularMonto(this.value)" required>
            @error('meses') <p class="error-msg">{{ $message }}</p> @enderror

            <label>Monto (Bs.) *</label>
            <input type="number" name="monto" id="montoInput" value="100" min="1" step="0.01" required>
            @error('monto') <p class="error-msg">{{ $message }}</p> @enderror
        </div>

        <div class="botones">
            <a href="{{ route('clientes.index') }}" class="btn-cancelar">Cancelar</a>
            <button type="submit" class="btn-guardar">Guardar</button>
        </div>
    </form>
</div>

<script>
function calcularMonto(meses) {
    meses = parseInt(meses) || 1;
    document.getElementById('montoInput').value = (100 * meses).toFixed(2);
}
</script>

</body>
</html>