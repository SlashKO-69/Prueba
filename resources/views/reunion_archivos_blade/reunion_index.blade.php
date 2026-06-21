<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reuniones — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">📅 Reuniones</h1>
        <div class="Acciones_pagina_sb">
            <button class="Boton Boton_Principal" onclick="document.getElementById('modalNueva').classList.add('active')">+ Nueva Reunión</button>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Tarjeta">
        @if($reuniones->isEmpty())
            <div class="Sin_Registros">No hay reuniones registradas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Motivo</th>
                        <th>Asistencia</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reuniones as $reunion)
                    @php
                        $asistencia  = $reunion->asistencia ?? [];
                        $asistieron  = collect($asistencia)->filter(fn($v) => $v === 'asistió')->count();
                        $total       = count($asistencia);
                    @endphp
                    <tr>
                        <td>{{ $reunion->id_reunion }}</td>
                        <td>{{ \Carbon\Carbon::parse($reunion->fecha_reunion)->format('d/m/Y') }}</td>
                        <td>{{ $reunion->hora_reunion }}</td>
                        <td>{{ $reunion->motivo }}</td>
                        <td>
                            <span class="Estado Estado-activo">{{ $asistieron }}/{{ $total }} asistieron</span>
                        </td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    <a href="{{ route('reuniones.show', $reunion->id_reunion) }}" class="Menu_Item">👁 Ver asistencia</a>
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('reuniones.destroy', $reunion->id_reunion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta reunión?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="Menu_Item Opcion_Peligro">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="Total_Registros">Total: <span>{{ $reuniones->count() }}</span> reuniones</span>
            </div>
        @endif
    </div>
</div>

<div class="Modal_Fondo" id="modalNueva">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">📅 Nueva Reunión</h2>
        <form action="{{ route('reuniones.store') }}" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Fecha *</label>
            <input type="date" name="fecha_reunion" class="Modal_Input" required>
            <label class="Modal_Etiqueta">Hora *</label>
            <input type="time" name="hora_reunion" class="Modal_Input" required>
            <label class="Modal_Etiqueta">Motivo *</label>
            <input type="text" name="motivo" class="Modal_Input" placeholder="Ej: Revisión mensual de metas" required>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalNueva').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Crear</button>
            </div>
        </form>
    </div>
</div>
<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.Menu_Opciones.open').forEach(d => { if(d!==el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.Menu_Opciones.open').forEach(d => d.classList.remove('open')));
</script>
</body>
</html>
