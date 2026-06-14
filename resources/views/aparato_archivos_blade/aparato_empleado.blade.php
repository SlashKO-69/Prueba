<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">🏋️ Equipos del Gimnasio</h1>
    </div>

    @if(session('success')) <div class="alert-success">✅ {{ session('success') }}</div> @endif

    <div class="card">
        @if($aparatos->isEmpty())
            <div class="empty-state">No hay aparatos registrados.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Reportar falla</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aparatos as $aparato)
                    @php
                        $badgeClass = match($aparato->estado_aparato) {
                            'funcionando'         => 'badge-activo',
                            'en mantenimiento'    => 'badge-por-vencer',
                            'fuera de servicio'   => 'badge-vencido',
                            default               => 'badge-pendiente',
                        };
                    @endphp
                    <tr>
                        <td>{{ $aparato->id_aparato }}</td>
                        <td>{{ $aparato->nombre_aparato }}</td>
                        <td>{{ $aparato->tipo_aparato }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ ucfirst($aparato->estado_aparato) }}</span></td>
                        <td>
                            <button class="btn btn-danger" style="font-size:12px; padding:6px 12px;"
                                onclick="abrirReporte({{ $aparato->id_aparato }}, '{{ $aparato->nombre_aparato }}')">
                                ⚠️ Reportar falla
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Modal Reportar Falla --}}
<div class="modal-overlay" id="modalReporte">
    <div class="modal-box">
        <h2 class="modal-title">⚠️ Reportar Falla</h2>
        <p id="nombreAparato" style="color:#888; font-size:13px; margin-bottom:10px;"></p>
        <form id="formReporte" action="{{ route('aparatos.reportar') }}" method="POST">
            @csrf
            <input type="hidden" name="id_aparato" id="idAparato">
            <label class="modal-label">Descripción de la falla *</label>
            <textarea name="detalle" class="modal-textarea" placeholder="Describe el problema del aparato..." required></textarea>
            <div class="modal-botones">
                <button type="button" class="modal-btn-cancel" onclick="document.getElementById('modalReporte').classList.remove('active')">Cancelar</button>
                <button type="submit" class="modal-btn-save">Enviar reporte</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirReporte(id, nombre) {
    document.getElementById('idAparato').value = id;
    document.getElementById('nombreAparato').innerHTML = 'Aparato: <span style="color:#2ECC71;font-weight:600;">'+nombre+'</span>';
    document.getElementById('modalReporte').classList.add('active');
}
</script>

</body>
</html>