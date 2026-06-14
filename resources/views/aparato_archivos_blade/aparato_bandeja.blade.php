<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Reportes — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📬 Bandeja de Reportes</h1>
        <div class="page-actions">
            <a href="{{ route('aparatos.index') }}" class="btn btn-secondary">← Volver a Equipos</a>
        </div>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($informes->isEmpty())
            <div class="empty-state">No hay reportes de fallas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Aparato</th>
                        <th>Empleado</th>
                        <th>Detalle</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($informes as $informe)
                    <tr style="{{ !$informe->leido ? 'background:rgba(46,204,113,0.04);' : '' }}">
                        <td>{{ $informe->aparato->nombre_aparato ?? '—' }}</td>
                        <td>{{ $informe->empleado->nombre ?? '—' }} {{ $informe->empleado->apaterno ?? '' }}</td>
                        <td>{{ $informe->detalle }}</td>
                        <td>{{ \Carbon\Carbon::parse($informe->fecha_informe)->format('d/m/Y') }}</td>
                        <td>
                            @if($informe->leido)
                                <span class="badge badge-pagado">Leído</span>
                            @else
                                <span class="badge badge-por-vencer">Nuevo</span>
                            @endif
                        </td>
                        <td>
                            @if(!$informe->leido)
                                <form action="{{ route('aparatos.leido', $informe->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-primary" style="font-size:12px; padding:6px 12px;">✓ Marcar leído</button>
                                </form>
                            @else
                                <span style="color:#555; font-size:12px;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="total-badge">Total: <span>{{ $informes->count() }}</span> reportes</span>
            </div>
        @endif
    </div>
</div>

</body>
</html>