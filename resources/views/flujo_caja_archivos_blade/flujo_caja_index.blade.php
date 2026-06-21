<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flujo de Caja — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Dinero.css') }}">
</head>
<body>
@include('layouts.sidebar')
<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">💵 Flujo de Caja</h1>
    </div>
    @if(session('success'))
        <div class="Mostrar_Bien">✅ {{ session('success') }}</div>
    @endif
    <div class="dinero">
        <div class="dinero-card green">
            <p class="dinero-label">TOTAL INGRESOS</p>
            <p class="dinero-valor">Bs. {{ number_format($totalIngresos, 2) }}</p>
        </div>
        <div class="dinero-card red">
            <p class="dinero-label">TOTAL EGRESOS</p>
            <p class="dinero-valor">Bs. {{ number_format($totalEgresos, 2) }}</p>
        </div>
        <div class="dinero-card {{ $saldo >= 0 ? 'green' : 'red' }}">
            <p class="dinero-label">SALDO</p>
            <p class="dinero-valor">Bs. {{ number_format($saldo, 2) }}</p>
        </div>
    </div>
    <div class="Tarjeta">
        @if($movimientos->isEmpty())
            <div class="Sin_Registros">
                No hay movimientos aún. Se generan automáticamente al inscribir clientes o pagar sueldos.
            </div>
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
                            @if($mov->cliente)
                                👤 {{ $mov->cliente->nombre }} {{ $mov->cliente->apaterno }}
                            @elseif($mov->empleado)
                                👷 {{ $mov->empleado->nombre }} {{ $mov->empleado->apaterno }}
                            @else
                                <span style="color:#555">—</span>
                            @endif
                        </td>
                        <td>{{ $mov->glosa ?? '—' }}</td>
                        <td>
                            <span class="Estado {{ $mov->tipo == 'ingreso' ? 'Estado-activo':'Estado-vencido' }}">
                                {{ ucfirst($mov->tipo) }}
                            </span>
                        </td>
                        <td class="{{ $mov->tipo==='ingreso' ? 'Dias_Ok':'Dias_Peligro' }}">
                            {{ $mov->tipo==='egreso'?'-':'+' }}
                            Bs. {{ number_format($mov->cantidad_dinero, 2) }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($mov->created_at)->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="Total_Registros">
                    Total:
                    <span>{{ $movimientos->count() }}</span>
                    movimientos
                </span>
            </div>
        @endif
    </div>
</div>
</body>
</html>