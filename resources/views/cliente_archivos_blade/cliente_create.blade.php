<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Cliente — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">➕ Nuevo Cliente</h1>
    </div>

    <div class="card" style="max-width:500px;">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            <label class="form-label">CI *</label>
            <input type="text" name="Ci" class="form-input" value="{{ old('Ci') }}" placeholder="Ej: 12345678">
            @error('Ci') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Nombre *</label>
            <input type="text" name="nombre" class="form-input" value="{{ old('nombre') }}">
            @error('nombre') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Paterno *</label>
            <input type="text" name="apaterno" class="form-input" value="{{ old('apaterno') }}">
            @error('apaterno') <p class="error-msg">{{ $message }}</p> @enderror

            <label class="form-label">Apellido Materno</label>
            <input type="text" name="amaterno" class="form-input" value="{{ old('amaterno') }}" placeholder="Opcional">

            <div style="border-top:1px solid rgba(46,204,113,0.15); margin-top:20px; padding-top:16px;">
                <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:4px;">📋 INSCRIPCIÓN</p>

                <label class="form-label">Meses a inscribir *</label>
                <input type="number" name="meses" class="form-input" value="{{ old('meses', 1) }}" min="1" max="24"
                       oninput="calcularMonto(this.value)" required>

                <label class="form-label">Promoción (opcional)</label>
                <select name="id_promocion" class="form-select" onchange="aplicarPromocion(this)">
                    <option value="">Sin promoción</option>
                    @foreach($promociones as $promo)
                        <option value="{{ $promo->id_promocion }}"
                                data-descuento="{{ $promo->porcentaje_descuento }}"
                                data-requisito="{{ $promo->requisito }}">
                            {{ $promo->porcentaje_descuento }}% — {{ $promo->requisito }}
                        </option>
                    @endforeach
                </select>
                <div id="promoInfo" style="display:none; background:rgba(46,204,113,0.08); border:1px solid rgba(46,204,113,0.2); border-radius:6px; padding:8px 12px; margin-top:8px; color:#2ECC71; font-size:12px;"></div>

                <label class="form-label">Monto (Bs.) *</label>
                <input type="number" name="monto" id="montoInput" class="form-input" value="100" min="1" step="0.01" required>
            </div>

            <div class="form-botones">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
let mesesActual = 1, descuentoActual = 0;
function calcularMonto(meses) { mesesActual = parseInt(meses) || 1; actualizarMonto(); }
function aplicarPromocion(select) {
    const op = select.options[select.selectedIndex];
    descuentoActual = parseFloat(op.dataset.descuento) || 0;
    const info = document.getElementById('promoInfo');
    if (descuentoActual > 0) { info.style.display='block'; info.innerHTML='🎁 Requisito: '+op.dataset.requisito; }
    else { info.style.display='none'; }
    actualizarMonto();
}
function actualizarMonto() {
    let m = 100 * mesesActual;
    if (descuentoActual > 0) m = m - (m * descuentoActual / 100);
    document.getElementById('montoInput').value = m.toFixed(2);
}
</script>

</body>
</html>