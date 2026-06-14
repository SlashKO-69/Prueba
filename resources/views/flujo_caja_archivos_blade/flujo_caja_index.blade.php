<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flujo de Caja — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">💵 Flujo de Caja</h1>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="resumen">
        <div class="resumen-card green">
            <p class="resumen-label">TOTAL INGRESOS</p>
            <p class="resumen-valor">Bs. {{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="resumen-card red">
            <p class="resumen-label">TOTAL EGRESOS</p>
            <p class="resumen-valor">Bs. {{ number_format($totalEgresos, 2) }}</p>
        </div>
        <div class="resumen-card {{ $saldo >= 0 ? 'green' : 'red' }}">
            <p class="resumen-label">SALDO</p>
            <p class="resumen-valor" style="color: {{ $saldo >= 0 ? '#2ECC71' : '#dc3545' }}">Bs. {{ number_format($saldo, 2) }}</p>
        </div>
    </div>

    <div class="card">
        @if($movimientos->isEmpty())
            <div class="empty-state">No hay movimientos aún. Se generan automáticamente al inscribir clientes o pagar sueldos.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asunto</th>
                        <th>Referencia</th>
                        <th>Glosa</th>
                        <th>Tipo</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimientos as $mov)
                    <tr>
                        <td>{{ $mov->id_caja }}</td>
                        <td>{{ $mov->asunto }}</td>
                        <td>
                            @if($mov->cliente) 👤 {{ $mov->cliente->nombre }} {{ $mov->cliente->apaterno }}
                            @elseif($mov->empleado) 👷 {{ $mov->empleado->nombre }} {{ $mov->empleado->apaterno }}
                            @else <span style="color:#555">—</span>
                            @endif
                        </td>
                        <td>{{ $mov->glosa ?? '—' }}</td>
                        <td><span class="badge badge-{{ $mov->tipo }}">{{ ucfirst($mov->tipo) }}</span></td>
                        <td style="color:{{ $mov->tipo==='ingreso'?'#2ECC71':'#dc3545' }};font-weight:600;">
                            {{ $mov->tipo==='egreso'?'-':'+' }} Bs. {{ number_format($mov->cantidad_dinero, 2) }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="total-badge">Total: <span>{{ $movimientos->count() }}</span> movimientos</span>
            </div>
        @endif
    </div>
</div>

</body>
</html>