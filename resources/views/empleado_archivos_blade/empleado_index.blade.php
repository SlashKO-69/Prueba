<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">⚙️ Gestión de Empleados</h1>
        <div class="page-actions">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">+ Nuevo Empleado</a>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($empleados->isEmpty())
            <div class="empty-state">No hay empleados registrados.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>CI</th>
                        <th>Nombre</th>
                        <th>Ap. Paterno</th>
                        <th>Celular</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->ci_empleado }}</td>
                        <td>{{ $empleado->nombre }}</td>
                        <td>{{ $empleado->apaterno }}</td>
                        <td>{{ $empleado->celular ?? '—' }}</td>
                        <td><span class="badge {{ $empleado->rol==='admin'?'badge-activo':'badge-pendiente' }}">{{ ucfirst($empleado->rol) }}</span></td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones ▾</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('empleados.show', $empleado->ci_empleado) }}" class="dropdown-item">👁 Ver detalle</a>
                                    <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="dropdown-item warning">✏️ Editar</a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('empleados.destroy', $empleado->ci_empleado) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar empleado {{ $empleado->nombre }}?')">
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
                <span class="total-badge">Total: <span>{{ $empleados->count() }}</span> empleados</span>
            </div>
        @endif
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