<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">👥 Gestión de Clientes</h1>
        <div class="page-actions">
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Nuevo Cliente</a>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($clientes->isEmpty())
            <div class="empty-state">No hay clientes registrados aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>CI</th>
                        <th>Nombre</th>
                        <th>Ap. Paterno</th>
                        <th>Ap. Materno</th>
                        <th>Vencimiento</th>
                        <th>Días restantes</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    @php
                        $dias = $cliente->dias_restantes;
                        if (is_null($dias)) { $sc='badge-pendiente'; $st='Sin inscripción'; $dt='—'; $dc=''; }
                        elseif ($dias < 0) { $sc='badge-vencido'; $st='Vencido'; $dt=abs($dias).' días vencido'; $dc='dias-danger'; }
                        elseif ($dias <= 5) { $sc='badge-por-vencer'; $st='Por vencer'; $dt=$dias.' días'; $dc='dias-warning'; }
                        else { $sc='badge-activo'; $st='Activo'; $dt=$dias.' días'; $dc='dias-ok'; }
                    @endphp
                    <tr>
                        <td>{{ $cliente->Ci }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->apaterno }}</td>
                        <td>{{ $cliente->amaterno ?? '—' }}</td>
                        <td>{{ $cliente->fecha_vencimiento ? \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                        <td class="{{ $dc }}">{{ $dt }}</td>
                        <td><span class="badge {{ $sc }}">{{ $st }}</span></td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones▾</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('clientes.show', $cliente->Ci) }}" class="dropdown-item">👁 Ver detalle</a>
                                    <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="dropdown-item warning">✏️ Editar</a>
                                    <button class="dropdown-item purple"
                                        onclick="abrirModal('{{ $cliente->Ci }}', '{{ $cliente->nombre }} {{ $cliente->apaterno }}')">
                                        🔄 Reinscribir
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('clientes.destroy', $cliente->Ci) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar cliente {{ $cliente->nombre }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item danger">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="total-badge">Total: <span>{{ $clientes->count() }}</span> clientes</span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Reinscribir --}}
<div class="modal-overlay" id="modalReinscribir">
    <div class="modal-box">
        <h2 class="modal-title">🔄 Reinscribir Cliente</h2>
        <p id="modalNombreCliente" style="color:#888; font-size:13px; margin-bottom:5px;"></p>
        <form id="formReinscribir" method="POST">
            @csrf
            <label class="modal-label">Meses *</label>
            <input type="number" name="meses" class="modal-input" min="1" max="24" value="1"
                   oninput="calcularMontoModal(this.value)" required>
            <label class="modal-label">Promoción (opcional)</label>
            <select name="id_promocion" class="modal-select" onchange="aplicarPromocionModal(this)">
                <option value="">Sin promoción</option>
                @foreach($promociones as $promo)
                    <option value="{{ $promo->id_promocion }}"
                            data-descuento="{{ $promo->porcentaje_descuento }}"
                            data-requisito="{{ $promo->requisito }}">
                        {{ $promo->porcentaje_descuento }}% — {{ $promo->requisito }}
                    </option>
                @endforeach
            </select>
            <div id="promoInfoModal" style="display:none; background:rgba(46,204,113,0.08); border:1px solid rgba(46,204,113,0.2); border-radius:6px; padding:8px 12px; margin-top:8px; color:#2ECC71; font-size:12px;"></div>
            <label class="modal-label">Monto (Bs.) *</label>
            <input type="number" name="monto" id="montoModal" class="modal-input" value="100" min="1" step="0.01" required>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel"
                        onclick="document.getElementById('modalReinscribir').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Reinscribir</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.dropdown.open').forEach(d => { if(d !== el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open')));

let mesesModal = 1, descuentoModal = 0;
function abrirModal(ci, nombre) {
    document.getElementById('modalNombreCliente').innerHTML = 'Cliente: <span style="color:#2ECC71;font-weight:600;">'+nombre+'</span>';
    document.getElementById('formReinscribir').action = '/clientes/'+ci+'/reinscribir';
    mesesModal = 1; descuentoModal = 0;
    document.getElementById('montoModal').value = '100.00';
    document.getElementById('promoInfoModal').style.display = 'none';
    document.getElementById('modalReinscribir').classList.add('active');
}
function calcularMontoModal(m) { mesesModal = parseInt(m)||1; actualizarMontoModal(); }
function aplicarPromocionModal(sel) {
    const op = sel.options[sel.selectedIndex];
    descuentoModal = parseFloat(op.dataset.descuento)||0;
    const info = document.getElementById('promoInfoModal');
    if(descuentoModal>0){info.style.display='block';info.innerHTML='🎁 '+op.dataset.requisito;}
    else{info.style.display='none';}
    actualizarMontoModal();
}
function actualizarMontoModal() {
    let m = 100*mesesModal;
    if(descuentoModal>0) m = m-(m*descuentoModal/100);
    document.getElementById('montoModal').value = m.toFixed(2);
}
</script>

</body>
</html>