<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🗓 Horarios de Empleados</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('active')">+ Nuevo Horario</button>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($horarios->isEmpty())
            <div class="empty-state">No hay horarios registrados aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Turno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($horarios as $horario)
                    <tr>
                        <td>{{ $horario->empleado->nombre ?? '—' }} {{ $horario->empleado->apaterno ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}</td>
                        <td>{{ $horario->hora_entrada }}</td>
                        <td>{{ $horario->hora_salida }}</td>
                        <td>
                            <span class="badge badge-activo">{{ ucfirst($horario->turno) }}</span>
                        </td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones ▾</button>
                                <div class="dropdown-menu">
                                    <form action="{{ route('horarios.destroy', $horario->id_horario) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este horario?')">
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
                <span class="total-badge">Total: <span>{{ $horarios->count() }}</span> horarios</span>
            </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalNuevo">
    <div class="modal-box">
        <h2 class="modal-title">🗓 Nuevo Horario</h2>
        <form action="{{ route('horarios.store') }}" method="POST">
            @csrf
            <label class="modal-label">Empleado *</label>
            <select name="ci_empleado" class="modal-select" required>
                <option value="">Seleccionar empleado...</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">{{ $emp->nombre }} {{ $emp->apaterno }}</option>
                @endforeach
            </select>
            <label class="modal-label">Fecha *</label>
            <input type="date" name="fecha" class="modal-input" required>
            <label class="modal-label">Hora entrada *</label>
            <input type="time" name="hora_entrada" class="modal-input" required>
            <label class="modal-label">Hora salida *</label>
            <input type="time" name="hora_salida" class="modal-input" required>
            <label class="modal-label">Turno *</label>
            <select name="turno" class="modal-select" required>
                <option value="mañana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>
            </select>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalNuevo').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Guardar</button>
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