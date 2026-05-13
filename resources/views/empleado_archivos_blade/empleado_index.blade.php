<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>GymTrainner - Empleados</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empleados/tabla.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: "{{ session('success') }}",
                background: '#0a1428',
                color: '#2ECC71',
                confirmButtonColor: '#2ECC71',
                timer: 2000,
                showConfirmButton: false,
                backdrop: 'rgba(0,0,0,0.8)'
            });
        </script>
    @endif

    <div class="overlay-table">
       
        <div class="table-header">
            <h1>Empleados</h1>
            <a href="{{ route('empleados.create') }}" class="btn-nuevo">+ Nuevo Empleado</a>
             <a href="{{ route('clientes.index') }}" class="btn-nuevo">Mas opciones</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Ap. Paterno</th>
                    <th>Ap. Materno</th>
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
                        <td>{{ $empleado->amaterno }}</td>
                        <td>{{ $empleado->celular }}</td>
                        <td>
                            <span class="badge-rol {{ $empleado->rol === 'admin' ? 'badge-admin' : 'badge-empleado' }}">
                                {{ $empleado->rol === 'admin' ? 'Administrador' : 'Empleado' }}
                            </span>
                        </td>
                        <td class="acciones">
                        <a href="{{ route('empleados.show', $empleado->ci_empleado) }}" class="btn-accion btn-ver">Ver</a>
                        <a href="{{ route('empleados.edit', $empleado->ci_empleado) }}" class="btn-accion btn-editar">Editar</a>
                        
                        @if($empleado->rol !== 'admin')
                            <form action="{{ route('empleados.destroy', $empleado->ci_empleado) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion btn-eliminar"
                                    onclick="return confirm('¿Seguro que deseas eliminar este empleado?')">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pie">
            <form action="{{ route('empleados.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>

</body>
</html>