<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📋 Inscripciones</h1>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($inscripciones->isEmpty())
            <div class="empty-state">No hay inscripciones aún. Se generan al registrar un cliente.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>CI</th>
                        <th>Inscripción</th>
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
                        if ($dias < 0) { $sc='badge-vencido'; $st='Vencido'; $dc='dias-danger'; }
                        elseif ($dias <= 5) { $sc='badge-por-vencer'; $st='Por vencer'; $dc='dias-warning'; }
                        else { $sc='badge-activo'; $st='Activo'; $dc='dias-ok'; }
                    @endphp
                    <tr>
                        <td>{{ $ins->id }}</td>
                        <td>{{ $ins->cliente->nombre ?? '—' }} {{ $ins->cliente->apaterno ?? '' }}</td>
                        <td>{{ $ins->ci_cliente }}</td>
                        <td>{{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') }}</td>
                        <td class="{{ $dc }}">{{ $dias < 0 ? abs($dias).' días vencido' : $dias.' días' }}</td>
                        <td>Bs. {{ number_format($ins->monto, 2) }}</td>
                        <td><span class="badge {{ $sc }}">{{ $st }}</span></td>
                        <td>
                            <div class="dropdown" onclick="toggleDropdown(this)">
                                <button class="dropdown-btn">Opciones▾</button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('inscripciones.show', $ins->id) }}" class="dropdown-item">👁 Ver detalle</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="total-badge">Total: <span>{{ $inscripciones->count() }}</span> inscripciones</span>
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