<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aparatos — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tabla.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="page-header">
        <h1 class="Titulo_Pagina">🔧 Aparatos</h1>
        <div class="Acciones_pagina_sb">
            <button class="Boton Boton_Principal" onclick="document.getElementById('modalNuevo').classList.add('active')">+ Nuevo Aparato</button>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Fondo_Contenido">
        @if($aparatos->isEmpty())
            <div class="Sin_Registros">No hay aparatos registrados.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aparatos as $aparato)
                    @php
                        $badgeClass = match($aparato->estado_aparato) {
                            'funcionando'       => 'Estado-activo',
                            'en mantenimiento'  => 'Estado-por-vencer',
                            'fuera de servicio' => 'Estado-vencido',
                            default             => 'Estado-pendiente',
                        };
                    @endphp
                    <tr>
                        <td>{{ $aparato->id_aparato }}</td>
                        <td>{{ $aparato->nombre_aparato }}</td>
                        <td>{{ $aparato->tipo_aparato }}</td>
                        <td><span class="Estado {{ $badgeClass }}">{{ ucfirst($aparato->estado_aparato) }}</span></td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    <button class="Menu_Item success"
                                        onclick="abrirEstado({{ $aparato->id_aparato }}, '{{ $aparato->estado_aparato }}')">
                                        🔄 Cambiar estado
                                    </button>
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('aparatos.destroy', $aparato->id_aparato) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este aparato?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="Menu_Item danger">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="Total_Registros">Total: <span>{{ $aparatos->count() }}</span> aparatos</span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Nuevo Aparato --}}
<div class="Modal_Fondo" id="modalNuevo">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🔧 Nuevo Aparato</h2>
        <form action="{{ route('aparatos.store') }}" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Nombre *</label>
            <input type="text" name="nombre_aparato" class="Modal_Input" placeholder="Ej: Caminadora" required>
            <label class="Modal_Etiqueta">Tipo *</label>
            <input type="text" name="tipo_aparato" class="Modal_Input" placeholder="Ej: Cardio" required>
            <label class="Modal_Etiqueta">Estado *</label>
            <select name="estado_aparato" class="Modal_Select" required>
                <option value="funcionando">Funcionando</option>
                <option value="en mantenimiento">En mantenimiento</option>
                <option value="fuera de servicio">Fuera de servicio</option>
            </select>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalNuevo').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Cambiar Estado --}}
<div class="Modal_Fondo" id="modalEstado">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🔄 Cambiar Estado</h2>
        <form id="formEstado" method="POST">
            @csrf @method('PATCH')
            <label class="Modal_Etiqueta">Nuevo estado *</label>
            <select name="estado_aparato" id="selectEstado" class="Modal_Select" required>
                <option value="funcionando">Funcionando</option>
                <option value="en mantenimiento">En mantenimiento</option>
                <option value="fuera de servicio">Fuera de servicio</option>
            </select>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalEstado').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Actualizar</button>
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

function abrirEstado(id, estadoActual) {
    document.getElementById('formEstado').action = '/aparatos/'+id+'/estado';
    document.getElementById('selectEstado').value = estadoActual;
    document.getElementById('modalEstado').classList.add('active');
}
</script>

</body>
</html>