<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/clientes/cliente_table.css') }}">
    <style>
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 100;
            justify-content: center;
            align-items: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: rgba(10,20,40,0.97);
            border: 1px solid rgba(46,204,113,0.3);
            padding: 35px;
            border-radius: 12px;
            width: 380px;
            box-shadow: 0 0 30px rgba(46,204,113,0.3);
        }
        .modal-box h2 { color: #2ECC71; font-size: 18px; margin-bottom: 5px; }
        .modal-box .ci-badge { color: #888; font-size: 13px; margin-bottom: 15px; }
        .modal-box .ci-badge span { color: #2ECC71; font-weight: 600; }
        .modal-box label { display: block; color: #eee; font-size: 13px; margin-top: 12px; text-align: left; }
        .modal-box input {
            width: 100%; padding: 10px; margin-top: 5px;
            border: 1px solid rgba(46,204,113,0.25); border-radius: 6px;
            background: #1a1a2e; color: #eee; font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        .modal-box input:focus { outline: none; border-color: #2ECC71; box-shadow: 0 0 8px rgba(46,204,113,0.3); }
        .modal-botones { display: flex; gap: 10px; margin-top: 20px; }
        .btn-modal-cancelar {
            flex: 1; padding: 11px; background: transparent;
            border: 1px solid #ff4444; border-radius: 6px; color: #ff4444;
            font-weight: bold; cursor: pointer; font-family: 'Poppins', sans-serif;
            font-size: 14px; transition: background 0.3s;
        }
        .btn-modal-cancelar:hover { background: rgba(255,68,68,0.15); }
        .btn-modal-guardar {
            flex: 1; padding: 11px; background: #2ECC71; border: none;
            border-radius: 6px; color: #111; font-weight: bold; cursor: pointer;
            font-family: 'Poppins', sans-serif; font-size: 14px; transition: background 0.3s;
        }
        .btn-modal-guardar:hover { background: #27AE60; }
        .btn-reinscribir { background: #6f42c1; color: #fff; }
    </style>
</head>
<body>

<div class="overlay-table">
    <div class="table-header">
        <h1>👥 Gestión de Clientes</h1>
        <div class="nav-links">
            <a href="{{ route('inscripciones.index') }}" class="btn-nav">📋 Inscripciones</a>
            @if(session('empleado_rol') === 'admin')
                <a href="{{ route('empleados.index') }}" class="btn-nav">⚙️ Empleados</a>
                <a href="{{ route('sueldos.index') }}" class="btn-nav">💰 Sueldos</a>
            @endif
            <a href="{{ route('clientes.create') }}" class="btn-nuevo">+ Nuevo Cliente</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

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
                    if (is_null($dias)) {
                        $estadoClass = 'badge-pendiente';
                        $estadoTexto = 'Sin inscripción';
                        $diasTexto   = '—';
                        $diasClass   = '';
                    } elseif ($dias < 0) {
                        $estadoClass = 'badge-vencido';
                        $estadoTexto = 'Vencido';
                        $diasTexto   = abs($dias) . ' días vencido';
                        $diasClass   = 'dias-danger';
                    } elseif ($dias <= 5) {
                        $estadoClass = 'badge-por-vencer';
                        $estadoTexto = 'Por vencer';
                        $diasTexto   = $dias . ' días';
                        $diasClass   = 'dias-warning';
                    } else {
                        $estadoClass = 'badge-activo';
                        $estadoTexto = 'Activo';
                        $diasTexto   = $dias . ' días';
                        $diasClass   = 'dias-ok';
                    }
                @endphp
                <tr>
                    <td>{{ $cliente->Ci }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->apaterno }}</td>
                    <td>{{ $cliente->amaterno ?? '—' }}</td>
                    <td>
                        {{ $cliente->fecha_vencimiento
                            ? \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y')
                            : '—' }}
                    </td>
                    <td class="{{ $diasClass }}">{{ $diasTexto }}</td>
                    <td><span class="badge {{ $estadoClass }}">{{ $estadoTexto }}</span></td>
                    <td>
                        <div class="acciones">
                            <a href="{{ route('clientes.show', $cliente->Ci) }}" class="btn-accion btn-ver">Ver</a>
                            <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="btn-accion btn-editar">Editar</a>
                            <button type="button" class="btn-accion btn-reinscribir"
                                onclick="abrirModal('{{ $cliente->Ci }}', '{{ $cliente->nombre }} {{ $cliente->apaterno }}')">
                                🔄 
                            </button>
                            <form action="{{ route('clientes.destroy', $cliente->Ci) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar cliente {{ $cliente->nombre }}?')">
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
        <span class="total-badge">Total: <span>{{ $clientes->count() }}</span> clientes</span>
        <form action="{{ route('empleados.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</div>

{{-- Modal Reinscribir --}}
<div class="modal-overlay" id="modalReinscribir">
    <div class="modal-box">
        <h2>🔄 Reinscribir Cliente</h2>
        <p class="ci-badge" id="modalNombreCliente"></p>
        <form id="formReinscribir" method="POST">
            @csrf

            <label>Meses a inscribir *</label>
            <input type="number" name="meses" min="1" max="24" value="1"
                   oninput="calcularMontoReinscribir(this.value)" required>

            <label>Monto (Bs.) *</label>
            <input type="number" name="monto" id="montoReinscribir" value="100" min="1" step="0.01" required>

            <div class="modal-botones">
                <button type="button" class="btn-modal-cancelar"
                        onclick="document.getElementById('modalReinscribir').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn-modal-guardar">Reinscribir</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModal(ci, nombre) {
    document.getElementById('modalNombreCliente').innerHTML = 'Cliente: <span>' + nombre + '</span>';
    document.getElementById('formReinscribir').action = '/clientes/' + ci + '/reinscribir';
    document.getElementById('modalReinscribir').classList.add('active');
}

function calcularMontoReinscribir(meses) {
    meses = parseInt(meses) || 1;
    document.getElementById('montoReinscribir').value = (100 * meses).toFixed(2);
}
</script>

</body>
</html>