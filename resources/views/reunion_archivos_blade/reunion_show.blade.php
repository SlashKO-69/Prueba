<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Reunión — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📅 Detalle de Reunión</h1>
        <div class="page-actions">
            <a href="{{ route('reuniones.index') }}" class="btn btn-secondary">← Volver</a>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div style="display:flex; gap:20px; flex-wrap:wrap; align-items:flex-start;">

        <div class="card" style="flex:1; min-width:250px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">DATOS DE LA REUNIÓN</p>
            <div class="detalle">
                <div class="detalle-fila"><span class="detalle-label">Fecha</span><span class="detalle-valor">{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</span></div>
                <div class="detalle-fila"><span class="detalle-label">Hora</span><span class="detalle-valor">{{ $reunion->hora_reunion }}</span></div>
                <div class="detalle-fila"><span class="detalle-label">Motivo</span><span class="detalle-valor">{{ $reunion->motivo }}</span></div>
            </div>
        </div>

        <div class="card" style="flex:2; min-width:300px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:12px;">CONTROL DE ASISTENCIA</p>
            @php $asistencia = $reunion->asistencia ?? []; @endphp
            @if($empleados->isEmpty())
                <p style="color:#555; font-size:13px;">No hay empleados registrados.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>CI</th>
                            <th>Estado</th>
                            <th>Marcar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($empleados as $emp)
                        @php
                            $estado = $asistencia[$emp->ci_empleado] ?? 'pendiente';
                            $badgeClass = $estado === 'asistió' ? 'badge-activo' : ($estado === 'no asistió' ? 'badge-vencido' : 'badge-pendiente');
                        @endphp
                        <tr>
                            <td>{{ $emp->nombre }} {{ $emp->apaterno }}</td>
                            <td>{{ $emp->ci_empleado }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ ucfirst($estado) }}</span></td>
                            <td>
                                <form action="{{ route('reuniones.asistencia', $reunion->id_reunion) }}" method="POST" style="display:flex; gap:6px;">
                                    @csrf
                                    <input type="hidden" name="ci_empleado" value="{{ $emp->ci_empleado }}">
                                    <button type="submit" name="estado" value="asistió"
                                        class="btn btn-primary" style="padding:5px 10px; font-size:12px;">✓</button>
                                    <button type="submit" name="estado" value="no asistió"
                                        class="btn btn-danger" style="padding:5px 10px; font-size:12px;">✗</button>
                                </form>
                            </td>
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