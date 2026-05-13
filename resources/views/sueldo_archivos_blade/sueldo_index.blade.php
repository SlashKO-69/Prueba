<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sueldos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagos/pagos_table.css') }}">
</head>
<body>

<div class="overlay-table">
    <div class="table-header">
        <h1>💰 Control de Sueldos</h1>
        <div class="nav-links">
            <a href="{{ route('empleados.index') }}" class="btn-nav">⚙️ Empleados</a>
            <a href="{{ route('inscripciones.index') }}" class="btn-nav">📋 Inscripciones</a>
            <button class="btn-nuevo" onclick="document.getElementById('modalNuevo').classList.add('active')">
                + Registrar Pago
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    @php
        $pendientes = $sueldos->where('estado', 'pendiente')->count();
        $pagados    = $sueldos->where('estado', 'pagado')->count();
        $totalMes   = $sueldos->filter(function($s) {
            return \Carbon\Carbon::parse($s->fecha_pago)->month === now()->month
                && \Carbon\Carbon::parse($s->fecha_pago)->year === now()->year;
        })->sum('monto');
    @endphp

    <div style="display:flex; gap:15px; margin-bottom:25px; flex-wrap:wrap;">
        <div style="background:rgba(255,193,7,0.1); border:1px solid rgba(255,193,7,0.3); padding:15px 20px; border-radius:8px; flex:1; min-width:150px;">
            <div style="color:#ffc107; font-size:12px; font-weight:600;">PENDIENTES</div>
            <div style="color:#fff; font-size:26px; font-weight:700;">{{ $pendientes }}</div>
        </div>
        <div style="background:rgba(46,204,113,0.1); border:1px solid rgba(46,204,113,0.3); padding:15px 20px; border-radius:8px; flex:1; min-width:150px;">
            <div style="color:#2ECC71; font-size:12px; font-weight:600;">PAGADOS ESTE MES</div>
            <div style="color:#fff; font-size:26px; font-weight:700;">{{ $pagados }}</div>
        </div>
        <div style="background:rgba(46,204,113,0.1); border:1px solid rgba(46,204,113,0.3); padding:15px 20px; border-radius:8px; flex:1; min-width:150px;">
            <div style="color:#2ECC71; font-size:12px; font-weight:600;">TOTAL PAGADO MES</div>
            <div style="color:#fff; font-size:26px; font-weight:700;">Bs. {{ number_format($totalMes, 0) }}</div>
        </div>
    </div>

    @if($sueldos->isEmpty())
        <div class="empty-state">No hay registros de sueldos aún.</div>
    @else
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>CI</th>
                    <th>Monto</th>
                    <th>Fecha registro</th>
                    <th>Días para cobro</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sueldos as $sueldo)
                @php
                    $dias = $sueldo->dias_para_cobro;
                    $diasClass = $dias <= 3 ? 'dias-warning' : 'dias-ok';
                    if ($sueldo->estado === 'pagado') $diasClass = '';
                @endphp
                <tr>
                    <td>
                        {{ $sueldo->empleado->nombre ?? '—' }}
                        {{ $sueldo->empleado->apaterno ?? '' }}
                    </td>
                    <td>{{ $sueldo->ci_empleado }}</td>
                    <td>Bs. {{ number_format($sueldo->monto, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($sueldo->fecha_pago)->format('d/m/Y') }}</td>
                    <td>
                        @if($sueldo->estado === 'pagado')
                            <span style="color:#555">—</span>
                        @else
                            <span class="{{ $diasClass }}">
                                {{ $dias <= 0 ? '¡Hoy!' : $dias . ' días' }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $sueldo->estado === 'pagado' ? 'badge-pagado' : 'badge-pendiente' }}">
                            {{ ucfirst($sueldo->estado) }}
                        </span>
                    </td>
                    <td>
                        <div class="acciones">
                            @if($sueldo->estado === 'pendiente')
                                <form action="{{ route('sueldos.pagar', $sueldo->id_sueldo) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-accion btn-pagar">✓ Pagar</button>
                                </form>
                            @endif
                            <form action="{{ route('sueldos.destroy', $sueldo->id_sueldo) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este registro?')">
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
        <span class="total-badge">Total registros: <span>{{ $sueldos->count() }}</span></span>
        <form action="{{ route('empleados.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>
</div>

{{-- Modal Registrar Pago --}}
<div class="modal-overlay" id="modalNuevo">
    <div class="modal-box">
        <h2>💰 Registrar Pago de Sueldo</h2>
        <form action="{{ route('sueldos.store') }}" method="POST">
            @csrf

            <label>Empleado *</label>
            <select name="ci_empleado" required>
                <option value="">Seleccionar empleado...</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">
                        {{ $emp->nombre }} {{ $emp->apaterno }} — CI: {{ $emp->ci_empleado }}
                    </option>
                @endforeach
            </select>

            <label>Monto (Bs.) *</label>
            <input type="number" name="monto" value="2000" min="1" step="0.01" required>

            <div class="modal-botones">
                <button type="button" class="btn-modal-cancelar"
                        onclick="document.getElementById('modalNuevo').classList.remove('active')">
                    Cancelar
                </button>
                <button type="submit" class="btn-modal-guardar">Registrar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>