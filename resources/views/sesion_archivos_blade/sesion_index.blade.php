<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🏋️ Control de Sesiones</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="document.getElementById('modalEntrada').classList.add('active')">🟢 Registrar Entrada</button>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert-error">⚠️ {{ session('error') }}</div> @endif

    <div class="card">
        @if($sesiones->isEmpty())
            <div class="empty-state">No hay sesiones registradas aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>CI</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Duración</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiones as $ses)
                    @php
                        $enGym = is_null($ses->Final);
                        $duracion = '—';
                        if (!$enGym && $ses->Inicio && $ses->Final) {
                            $totalMins = (int) \Carbon\Carbon::parse($ses->Inicio)->diffInMinutes(\Carbon\Carbon::parse($ses->Final));
                            $horas = intdiv($totalMins, 60); $minutos = $totalMins % 60;
                            $duracion = $horas > 0 ? "{$horas}h {$minutos}min" : "{$totalMins}min";
                        }
                    @endphp
                    <tr>
                        <td>{{ $ses->id_sesion }}</td>
                        <td>{{ $ses->cliente->nombre ?? '—' }} {{ $ses->cliente->apaterno ?? '' }}</td>
                        <td>{{ $ses->ci_cliente }}</td>
                        <td>{{ \Carbon\Carbon::parse($ses->Inicio)->format('d/m/Y H:i') }}</td>
                        <td>{{ $ses->Final ? \Carbon\Carbon::parse($ses->Final)->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ $duracion }}</td>
                        <td>
                            @if($enGym)
                                <span class="badge badge-activo">En gym</span>
                            @else
                                <span class="badge badge-pagado">Finalizado</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones▾</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('sesiones.show', $ses->id_sesion) }}" class="dropdown-item">👁 Ver detalle</a>
                                    @if($enGym)
                                        <button class="dropdown-item danger"
                                            onclick="abrirSalida({{ $ses->id_sesion }}, '{{ $ses->cliente->nombre ?? '' }} {{ $ses->cliente->apaterno ?? '' }}')">
                                            🔴 Registrar Salida
                                        </button>
                                    @endif
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('sesiones.destroy', $ses->id_sesion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar?')">
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
                <span class="total-badge">Total: <span>{{ $sesiones->count() }}</span> sesiones</span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Entrada --}}
<div class="modal-overlay" id="modalEntrada">
    <div class="modal-box">
        <h2 class="modal-title">🟢 Registrar Entrada</h2>
        <form action="{{ route('sesiones.entrada') }}" method="POST">
            @csrf
            <label class="modal-label">Buscar cliente</label>
            <input type="text" id="buscadorCliente" class="modal-input" placeholder="Escribe nombre o CI..."
                   oninput="filtrarClientes(this.value)">
            <label class="modal-label">Cliente inscrito *</label>
            <select name="ci_cliente" id="selectCliente" class="modal-select" required size="5">
                <option value="">Seleccionar...</option>
                @foreach($clientesActivos as $cliente)
                    <option value="{{ $cliente->Ci }}"
                            data-texto="{{ strtolower($cliente->nombre.' '.$cliente->apaterno.' '.$cliente->Ci) }}">
                        {{ $cliente->nombre }} {{ $cliente->apaterno }} — CI: {{ $cliente->Ci }}
                    </option>
                @endforeach
            </select>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="cerrarModalEntrada()">Cancelar</button>
                <button type="submit" class="modal-btn-save">Registrar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Salida --}}
<div class="modal-overlay" id="modalSalida">
    <div class="modal-box">
        <h2 class="modal-title">🔴 Registrar Salida</h2>
        <p id="nombreClienteSalida" style="color:#888; font-size:13px; margin-bottom:10px;"></p>
        <form id="formSalida" method="POST">
            @csrf @method('PATCH')
            <label class="modal-label">Empleado que atendió (opcional)</label>
            <select name="ci_empleado" class="modal-select">
                <option value="">Sin empleado asignado</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">{{ $emp->nombre }} {{ $emp->apaterno }}</option>
                @endforeach
            </select>
            <label class="modal-label">Detalle de la sesión *</label>
            <textarea name="Detalles" class="modal-textarea" placeholder="Máquinas usadas, actividades..." required></textarea>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalSalida').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Registrar Salida</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.dropdown.open').forEach(d => { if(d!==el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.dropdown.open').forEach(d => d.classList.remove('open')));

function filtrarClientes(texto) {
    const filtro = texto.toLowerCase();
    document.querySelectorAll('#selectCliente option').forEach(op => {
        if(!op.value) return;
        op.style.display = (op.dataset.texto||'').includes(filtro) ? '' : 'none';
    });
}
function cerrarModalEntrada() {
    document.getElementById('modalEntrada').classList.remove('active');
    document.getElementById('buscadorCliente').value = '';
    filtrarClientes('');
}
function abrirSalida(id, nombre) {
    document.getElementById('nombreClienteSalida').innerHTML = 'Cliente: <span style="color:#2ECC71;font-weight:600;">'+nombre+'</span>';
    document.getElementById('formSalida').action = '/sesiones/'+id+'/salida';
    document.getElementById('modalSalida').classList.add('active');
}
</script>

</body>
</html>