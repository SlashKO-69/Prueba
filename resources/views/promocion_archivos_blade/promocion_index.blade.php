<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🎁 Promociones</h1>
        <div class="page-actions">
            @if(session('empleado_rol') === 'admin')
                <button class="btn btn-primary" onclick="document.getElementById('modalNueva').classList.add('active')">+ Nueva Promoción</button>
            @endif
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($promociones->isEmpty())
            <div class="empty-state">No hay promociones registradas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descuento</th>
                        <th>Requisito</th>
                        @if(session('empleado_rol') === 'admin') <th>Acciones</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($promociones as $promo)
                    <tr>
                        <td>{{ $promo->id_promocion }}</td>
                        <td style="color:#2ECC71; font-weight:700; font-size:16px;">{{ $promo->porcentaje_descuento }}%</td>
                        <td>{{ $promo->requisito }}</td>
                        @if(session('empleado_rol') === 'admin')
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones ▾</button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item warning"
                                        onclick="abrirEditar({{ $promo->id_promocion }}, '{{ $promo->porcentaje_descuento }}', '{{ addslashes($promo->requisito) }}')">
                                        ✏️ Editar
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('promociones.destroy', $promo->id_promocion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item danger">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@if(session('empleado_rol') === 'admin')
<div class="modal-overlay" id="modalNueva">
    <div class="modal-box">
        <h2 class="modal-title">🎁 Nueva Promoción</h2>
        <form action="{{ route('promociones.store') }}" method="POST">
            @csrf
            <label class="modal-label">Descuento (%) *</label>
            <input type="number" name="porcentaje_descuento" class="modal-input" min="1" max="100" step="0.01" required>
            <label class="modal-label">Requisito *</label>
            <textarea name="requisito" class="modal-textarea" required></textarea>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalNueva').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalEditar">
    <div class="modal-box">
        <h2 class="modal-title">✏️ Editar Promoción</h2>
        <form id="formEditar" method="POST">
            @csrf @method('PUT')
            <label class="modal-label">Descuento (%) *</label>
            <input type="number" id="editDescuento" name="porcentaje_descuento" class="modal-input" required>
            <label class="modal-label">Requisito *</label>
            <textarea id="editRequisito" name="requisito" class="modal-textarea" required></textarea>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalEditar').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.dropdown.open').forEach(d => { if(d!==el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open')));

function abrirEditar(id, desc, req) {
    document.getElementById('editDescuento').value = desc;
    document.getElementById('editRequisito').value = req;
    document.getElementById('formEditar').action = '/promociones/'+id;
    document.getElementById('modalEditar').classList.add('active');
}
</script>

</body>
</html>