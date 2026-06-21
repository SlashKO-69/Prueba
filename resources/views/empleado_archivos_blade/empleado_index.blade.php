<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">⚙️ Gestión de Empleados</h1>
        <div class="Acciones_Pagina">
            <a href="{{ route('empleados.create') }}" class="Boton Boton_Principal">+ Nuevo Empleado</a>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Tarjeta">
        @if($empleados->isEmpty())
            <div class="Sin_Registros">No hay empleados registrados.</div>
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
                        <td><span class="Estado {{ $empleado->rol==='admin'?'Estado-activo':'Estado-pendiente' }}">{{ ucfirst($empleado->rol) }}</span></td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    <a href="{{ route('empleados.show', $empleado->ci_empleado) }}" class="Menu_Item">👁 Ver detalle</a>
                                    <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="Menu_Item Opcion_Aviso">✏️ Editar</a>
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('empleados.destroy', $empleado->ci_empleado) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar empleado {{ $empleado->nombre }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="Menu_Item Opcion_Peligro">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="Total_Registros">Total: <span>{{ $empleados->count() }}</span> empleados</span>
            </div>
        @endif
    </div>
</div>

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.Menu_Opciones.open').forEach(d => { if(d!==el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.Menu_Opciones.open').forEach(d => d.classList.remove('open')));
</script>
</body>
</html>