<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reuniones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📬 Mi bandeja</h1>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($reuniones->isEmpty())
            <div class="empty-state">No tienes reuniones asignadas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Mi asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reuniones as $reunion)
                    @php
                        $ci         = session('empleado_ci');
                        $asistencia = $reunion->asistencia ?? [];
                        $estado     = $asistencia[$ci] ?? 'pendiente';
                        $badgeClass = $estado === 'asistió' ? 'badge-activo' : ($estado === 'no asistió' ? 'badge-vencido' : 'badge-pendiente');
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</td>
                        <td>{{ $reunion->hora_reunion }}</td>
                        <td>{{ $reunion->motivo }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($estado) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

</body>
</html>