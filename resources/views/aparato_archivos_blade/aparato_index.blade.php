<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aparatos — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🔧 Aparatos</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('active')">+ Nuevo Aparato</button>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($aparatos->isEmpty())
            <div class="empty-state">No hay aparatos registrados.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aparatos as $aparato)
                    @php
                        $badgeClass = match($aparato->estado_aparato) {
                            'funcionando'       => 'badge-activo',
                            'en mantenimiento'  => 'badge-por-vencer',
                            'fuera de servicio' => 'badge-vencido',
                            default             => 'badge-pendiente',
                        };
                    @endphp
                    <tr>
                        <td>{{ $aparato->id_aparato }}</td>
                        <td>{{ $aparato->nombre_aparato }}</td>
                        <td>{{ $aparato->tipo_aparato }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($aparato->estado_aparato) }}</span></td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones ▾</button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item success"
                                        onclick="abrirEstado({{ $aparato->id_aparato }}, '{{ $aparato->estado_aparato }}')">
                                        🔄 Cambiar estado
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('aparatos.destroy', $aparato->id_aparato) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este aparato?')">
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
                <span class="total-badge">Total: <span>{{ $aparatos->count() }}</span> aparatos</span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Nuevo Aparato --}}
<div class="modal-overlay" id="modalNuevo">
    <div class="modal-box">
        <h2 class="modal-title">🔧 Nuevo Aparato</h2>
        <form action="{{ route('aparatos.store') }}" method="POST">
            @csrf
            <label class="modal-label">Nombre *</label>
            <input type="text" name="nombre_aparato" class="modal-input" placeholder="Ej: Caminadora" required>
            <label class="modal-label">Tipo *</label>
            <input type="text" name="tipo_aparato" class="modal-input" placeholder="Ej: Cardio" required>
            <label class="modal-label">Estado *</label>
            <select name="estado_aparato" class="modal-select" required>
                <option value="funcionando">Funcionando</option>
                <option value="en mantenimiento">En mantenimiento</option>
                <option value="fuera de servicio">Fuera de servicio</option>
            </select>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalNuevo').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Cambiar Estado --}}
<div class="modal-overlay" id="modalEstado">
    <div class="modal-box">
        <h2 class="modal-title">🔄 Cambiar Estado</h2>
        <form id="formEstado" method="POST">
            @csrf @method('PATCH')
            <label class="modal-label">Nuevo estado *</label>
            <select name="estado_aparato" id="selectEstado" class="modal-select" required>
                <option value="funcionando">Funcionando</option>
                <option value="en mantenimiento">En mantenimiento</option>
                <option value="fuera de servicio">Fuera de servicio</option>
            </select>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalEstado').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Actualizar</button>
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

function abrirEstado(id, estadoActual) {
    document.getElementById('formEstado').action = '/aparatos/'+id+'/estado';
    document.getElementById('selectEstado').value = estadoActual;
    document.getElementById('modalEstado').classList.add('active');
}
</script>

</body>
</html>