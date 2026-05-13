<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagos/pagos_table.css') }}">
</head>
<body>

<div class="overlay-table">
    <div class="table-header">
        <h1>📋 Inscripciones</h1>
        <div class="nav-links">
            <a href="{{ route('clientes.index') }}" class="btn-nav">👥 Clientes</a>
            @if(session('empleado_rol') === 'admin')
                <a href="{{ route('empleados.index') }}" class="btn-nav">⚙️ Empleados</a>
                <a href="{{ route('sueldos.index') }}" class="btn-nav">💰 Sueldos</a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($inscripciones->isEmpty())
        <div class="empty-state">No hay inscripciones registradas aún.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>CI</th>
                    <th>Fecha Inscripción</th>
                    <th>Vencimiento</th>
                    <th>Días restantes</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $ins)
                @php
                    $dias = $ins->dias_restantes;
                    if ($dias < 0) {
                        $estadoClass = 'badge-vencido';
                        $estadoTexto = 'Vencido';
                        $diasClass   = 'dias-danger';
                    } elseif ($dias <= 5) {
                        $estadoClass = 'badge-por-vencer';
                        $estadoTexto = 'Por vencer';
                        $diasClass   = 'dias-warning';
                    } else {
                        $estadoClass = 'badge-activo';
                        $estadoTexto = 'Activo';
                        $diasClass   = 'dias-ok';
                    }
                @endphp
                <tr>
                    <td>{{ $ins->id }}</td>
                    <td>
                        @if($ins->cliente)
                            {{ $ins->cliente->nombre }} {{ $ins->cliente->apaterno }}
                        @else
                            <span style="color:#666">—</span>
                        @endif
                    </td>
                    <td>{{ $ins->ci_cliente ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') }}</td>
                    <td class="{{ $diasClass }}">
                        {{ $dias < 0 ? abs($dias) . ' días vencido' : $dias . ' días' }}
                    </td>
                    <td>Bs. {{ number_format($ins->monto, 2) }}</td>
                    <td><span class="badge {{ $estadoClass }}">{{ $estadoTexto }}</span></td>
                    <td>
                        <div class="acciones">
                            <a href="{{ route('inscripciones.show', $ins->id) }}" class="btn-accion btn-ver">Ver</a>
                            <form action="{{ route('inscripciones.destroy', $ins->id) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta inscripción?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-accion btn-eliminar">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="pie">
        <span class="total-badge">Total: <span>{{ $inscripciones->count() }}</span> inscripciones</span>
        <form action="{{ route('empleados.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</div>

</body>
</html>