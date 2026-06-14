<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📬 Bandeja</h1>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    @if(session('empleado_rol') === 'admin')
        {{-- ADMIN: reportes de máquinas --}}
        <div class="card">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:16px;">REPORTES DE EQUIPOS</p>
            @if($informes->isEmpty())
                <div class="empty-state">No hay reportes de fallas.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Máquina</th>
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
                            <td>#{{ $informe->id_aparato }} — {{ $informe->nombre_maquina }}</td>
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
                                    <form action="{{ route('bandeja.leido', $informe->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-primary" style="font-size:12px; padding:6px 12px;">✓ Leído</button>
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

    @else
        {{-- EMPLEADO: reuniones + reportar falla --}}
        <div class="card" style="margin-bottom:20px;">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:16px;">MIS REUNIONES</p>
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
                            $bc         = $estado === 'asistió' ? 'badge-activo' : ($estado === 'no asistió' ? 'badge-vencido' : 'badge-pendiente');
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</td>
                            <td>{{ $reunion->hora_reunion }}</td>
                            <td>{{ $reunion->motivo }}</td>
                            <td><span class="badge {{ $bc }}">{{ ucfirst($estado) }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="card">
            <p style="color:#2ECC71; font-size:12px; font-weight:700; letter-spacing:0.8px; margin-bottom:16px;">⚠️ REPORTAR FALLA DE EQUIPO</p>
            <form action="{{ route('bandeja.reportar') }}" method="POST" style="max-width:500px;">
                @csrf

                <label class="form-label">Seleccionar aparato *</label>
                <select name="id_aparato" class="form-select" required>
                    <option value="">Seleccionar...</option>
                    @foreach($aparatos as $ap)
                        <option value="{{ $ap->id_aparato }}">
                            #{{ $ap->id_aparato }} — {{ $ap->nombre_aparato }} ({{ $ap->tipo_aparato }})
                        </option>
                    @endforeach
                </select>

                <label class="form-label">Descripción de la falla *</label>
                <textarea name="detalle" class="form-input" rows="3"
                          placeholder="Describe el problema..." required
                          style="resize:vertical;"></textarea>

                <div style="margin-top:16px;">
                    <button type="submit" class="btn btn-danger">⚠️ Enviar reporte</button>
                </div>
            </form>
        </div>
    @endif
</div>

</body>
</html>