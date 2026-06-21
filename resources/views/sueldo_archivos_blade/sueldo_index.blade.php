<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sueldos — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dinero.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">💰 Control de Sueldos</h1>
        <div class="Acciones_pagina_sb">
            <button class="Boton Boton_Principal" onclick="document.getElementById('modalNuevo').classList.add('active')">+ Registrar Pago</button>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert-error">⚠️ {{ session('error') }}</div> @endif

    @php
        $pendientes = $sueldos->where('estado', 'pendiente')->count();
        $pagados    = $sueldos->where('estado', 'pagado')->count();
        $totalMes   = $sueldos->filter(function($s) {
            return \Carbon\Carbon::parse($s->fecha_pago)->month === now()->month
                && \Carbon\Carbon::parse($s->fecha_pago)->year === now()->year;
        })->sum('monto');
    @endphp

    <div class="dinero">
        <div class="dinero-card">
            <p class="dinero-label" style="color:#ffc107;">PENDIENTES</p>
            <p class="dinero-valor">{{ $pendientes }}</p>
        </div>
        <div class="dinero-card green">
            <p class="dinero-label">PAGADOS ESTE MES</p>
            <p class="dinero-valor">{{ $pagados }}</p>
        </div>
        <div class="dinero-card green">
            <p class="dinero-label">TOTAL PAGADO MES</p>
            <p class="dinero-valor">Bs. {{ number_format($totalMes, 0) }}</p>
        </div>
    </div>

    <div class="Tarjeta">
        @if($sueldos->isEmpty())
            <div class="Sin_Registros">No hay registros de sueldos aún.</div>
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
                        $dc = $dias <= 3 ? 'Dias_Aviso' : 'Dias_Ok';
                        if($sueldo->estado === 'pagado') $dc = '';
                        $sc = $sueldo->estado === 'pagado' ? 'Estado-activo' : 'Estado-pendiente';
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
                        <td><span class="Estado {{ $sc }}">{{ ucfirst($sueldo->estado) }}</span></td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    @if($sueldo->estado === 'pendiente')
                                        <form action="{{ route('sueldos.pagar', $sueldo->id_sueldo) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="Menu_Item Opcion_Exito">✓ Marcar como pagado</button>
                                        </form>
                                        <div class="Menu_Divisor"></div>
                                    @endif
                                    <form action="{{ route('sueldos.destroy', $sueldo->id_sueldo) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar?')">
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
                <span class="Total_Registros">Total: <span>{{ $sueldos->count() }}</span> registros</span>
            </div>
        @endif
    </div>
</div>
<div class="Modal_Fondo" id="modalNuevo">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">💰 Registrar Pago de Sueldo</h2>
        <form action="{{ route('sueldos.store') }}" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Empleado *</label>
            <select name="ci_empleado" class="Modal_Select" required>
                <option value="">Seleccionar empleado...</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">{{ $emp->nombre }} {{ $emp->apaterno }} — CI: {{ $emp->ci_empleado }}</option>
                @endforeach
            </select>
            <label class="Modal_Etiqueta">Monto (Bs.) *</label>
            <input type="number" name="monto" class="Modal_Input" value="2000" min="1" step="0.01" required>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalNuevo').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Registrar</button>
            </div>
        </form>
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
