<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sueldos — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">💰 Control de Sueldos</h1>
        <div class="page-actions">
            <button class="btn btn-primary" onclick="document.getElementById('modalNuevo').classList.add('active')">+ Registrar Pago</button>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert-error">⚠️ {{ session('error') }}</div> @endif

    @php
        $pendientes = $sueldos->where('estado', 'pendiente')->count();
        $pagados    = $sueldos->where('estado', 'pagado')->count();
        $totalMes   = $sueldos->filter(function($s) {
            return \Carbon\Carbon::parse($s->fecha_pago)->month === now()->month
                && \Carbon\Carbon::parse($s->fecha_pago)->year === now()->year;
        })->sum('monto');
    @endphp

    <div class="resumen">
        <div class="resumen-card">
            <p class="resumen-label" style="color:#ffc107;">PENDIENTES</p>
            <p class="resumen-valor">{{ $pendientes }}</p>
        </div>
        <div class="resumen-card green">
            <p class="resumen-label">PAGADOS ESTE MES</p>
            <p class="resumen-valor">{{ $pagados }}</p>
        </div>
        <div class="resumen-card green">
            <p class="resumen-label">TOTAL PAGADO MES</p>
            <p class="resumen-valor">Bs. {{ number_format($totalMes, 0) }}</p>
        </div>
    </div>

    <div class="card">
        @if($sueldos->isEmpty())
            <div class="empty-state">No hay registros de sueldos aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>CI</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Días para cobro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sueldos as $sueldo)
                    @php
                        $dias = $sueldo->dias_para_cobro;
                        $dc = $dias <= 3 ? 'dias-warning' : 'dias-ok';
                        if($sueldo->estado === 'pagado') $dc = '';
                    @endphp
                    <tr>
                        <td>{{ $sueldo->empleado->nombre ?? '—' }} {{ $sueldo->empleado->apaterno ?? '' }}</td>
                        <td>{{ $sueldo->ci_empleado }}</td>
                        <td>Bs. {{ number_format($sueldo->monto, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sueldo->fecha_pago)->format('d/m/Y') }}</td>
                        <td>
                            @if($sueldo->estado === 'pagado') <span style="color:#555">—</span>
                            @else <span class="{{ $dc }}">{{ $dias <= 0 ? '¡Hoy!' : $dias.' días' }}</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $sueldo->estado==='pagado'?'badge-pagado':'badge-pendiente' }}">{{ ucfirst($sueldo->estado) }}</span></td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones▾</button>
                                <div class="dropdown-menu">
                                    @if($sueldo->estado === 'pendiente')
                                        <form action="{{ route('sueldos.pagar', $sueldo->id_sueldo) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="dropdown-item success">✓ Marcar como pagado</button>
                                        </form>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    <form action="{{ route('sueldos.destroy', $sueldo->id_sueldo) }}" method="POST"
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
                <span class="total-badge">Total: <span>{{ $sueldos->count() }}</span> registros</span>
            </div>
        @endif
    </div>
</div>

<div class="modal-overlay" id="modalNuevo">
    <div class="modal-box">
        <h2 class="modal-title">💰 Registrar Pago de Sueldo</h2>
        <form action="{{ route('sueldos.store') }}" method="POST">
            @csrf
            <label class="modal-label">Empleado *</label>
            <select name="ci_empleado" class="modal-select" required>
                <option value="">Seleccionar empleado...</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">{{ $emp->nombre }} {{ $emp->apaterno }} — CI: {{ $emp->ci_empleado }}</option>
                @endforeach
            </select>
            <label class="modal-label">Monto (Bs.) *</label>
            <input type="number" name="monto" class="modal-input" value="2000" min="1" step="0.01" required>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalNuevo').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Registrar</button>
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