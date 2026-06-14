<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuniones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📅 Reuniones</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="document.getElementById('modalNueva').classList.add('active')">+ Nueva Reunión</button>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($reuniones->isEmpty())
            <div class="empty-state">No hay reuniones registradas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Asistencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reuniones as $reunion)
                    @php
                        $asistencia  = $reunion->asistencia ?? [];
                        $asistieron  = collect($asistencia)->filter(fn($v) => $v === 'asistió')->count();
                        $total       = count($asistencia);
                    @endphp
                    <tr>
                        <td>{{ $reunion->id_reunion }}</td>
                        <td>{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</td>
                        <td>{{ $reunion->hora_reunion }}</td>
                        <td>{{ $reunion->motivo }}</td>
                        <td>
                            <span class="badge badge-activo">{{ $asistieron }}/{{ $total }} asistieron</span>
                        </td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones ▾</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('reuniones.show', $reunion->id_reunion) }}" class="dropdown-item">👁 Ver asistencia</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('reuniones.destroy', $reunion->id_reunion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta reunión?')">
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
                <span class="total-badge">Total: <span>{{ $reuniones->count() }}</span> reuniones</span>
            </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalNueva">
    <div class="modal-box">
        <h2 class="modal-title">📅 Nueva Reunión</h2>
        <form action="{{ route('reuniones.store') }}" method="POST">
            @csrf
            <label class="modal-label">Fecha *</label>
            <input type="date" name="fecha_reunion" class="modal-input" required>
            <label class="modal-label">Hora *</label>
            <input type="time" name="hora_reunion" class="modal-input" required>
            <label class="modal-label">Motivo *</label>
            <input type="text" name="motivo" class="modal-input" placeholder="Ej: Revisión mensual de metas" required>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalNueva').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Crear</button>
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
</script>

</body>
</html>