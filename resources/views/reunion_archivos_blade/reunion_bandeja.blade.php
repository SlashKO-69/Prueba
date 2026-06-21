<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reuniones — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">📬 Mi bandeja</h1>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Tarjeta">
        @if($reuniones->isEmpty())
            <div class="Sin_Registros">No tienes reuniones asignadas.</div>
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
                        $sc = $estado === 'asistió' ? 'Estado-activo' : ($estado === 'no asistió' ? 'Estado-vencido' : 'Estado-pendiente');
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</td>
                        <td>{{ $reunion->hora_reunion }}</td>
                        <td>{{ $reunion->motivo }}</td>
                        <td><span class="Estado {{ $sc }}">{{ ucfirst($estado) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
</body>
</html>
