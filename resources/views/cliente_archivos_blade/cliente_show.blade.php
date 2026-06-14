<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Cliente — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">👤 Detalle del Cliente</h1>
        <div class="page-actions">
            <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="btn btn-warning">✏️ Editar</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:flex-start;">
        <div class="card" style="flex:1; min-width:280px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">DATOS PERSONALES</p>
            <div class="detalle">
                <div class="detalle-fila"><span class="detalle-label">CI</span><span class="detalle-valor">{{ $cliente->Ci }}</span></div>
                <div class="detalle-fila"><span class="detalle-label">Nombre</span><span class="detalle-valor">{{ $cliente->nombre }}</span></div>
                <div class="detalle-fila"><span class="detalle-label">Ap. Paterno</span><span class="detalle-valor">{{ $cliente->apaterno }}</span></div>
                <div class="detalle-fila"><span class="detalle-label">Ap. Materno</span><span class="detalle-valor">{{ $cliente->amaterno ?? '—' }}</span></div>
            </div>
        </div>

        <div class="card" style="flex:2; min-width:300px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">HISTORIAL DE INSCRIPCIONES</p>
            @if($inscripciones->isEmpty())
                <p style="color:#555; font-size:13px;">Sin inscripciones.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Inscripción</th>
                            <th>Vencimiento</th>
                            <th>Monto</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripciones as $ins)
                        @php
                            $dias = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($ins->fecha_vencimiento), false);
                            if ($dias < 0) { $sc='badge-vencido'; $st='Vencido'; }
                            elseif ($dias <= 5) { $sc='badge-por-vencer'; $st='Por vencer'; }
                            else { $sc='badge-activo'; $st='Activo'; }
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') }}</td>
                            <td>Bs. {{ number_format($ins->monto, 2) }}</td>
                            <td><span class="badge {{ $sc }}">{{ $st }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

</body>
</html>