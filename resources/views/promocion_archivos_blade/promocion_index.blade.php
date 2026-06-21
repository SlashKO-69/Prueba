<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">🎁 Promociones</h1>
        <div class="Acciones_pagina_sb">
            @if(session('empleado_rol') === 'admin')
                <button class="Boton Boton_Principal" onclick="document.getElementById('modalNueva').classList.add('active')">+ Nueva Promoción</button>
            @endif
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Tarjeta">
        @if($promociones->isEmpty())
            <div class="Sin_Registros">No hay promociones registradas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descuento</th>
                        <th>Requisito</th>
                        @if(session('empleado_rol') === 'admin') <th>Acciones</th> @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($promociones as $promo)
                    <tr>
                        <td>{{ $promo->id_promocion }}</td>
                        <td style="color:#2ECC71; font-weight:700; font-size:16px;">{{ $promo->porcentaje_descuento }}%</td>
                        <td>{{ $promo->requisito }}</td>
                        @if(session('empleado_rol') === 'admin')
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    <button class="Menu_Item Opcion_Aviso"
                                        onclick="abrirEditar({{ $promo->id_promocion }}, '{{ $promo->porcentaje_descuento }}', '{{ addslashes($promo->requisito) }}')">
                                        ✏️ Editar
                                    </button>
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('promociones.destroy', $promo->id_promocion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="Menu_Item Opcion_Peligro">🗑 Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@if(session('empleado_rol') === 'admin')
<div class="Modal_Fondo" id="modalNueva">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🎁 Nueva Promoción</h2>
        <form action="{{ route('promociones.store') }}" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Descuento (%) *</label>
            <input type="number" name="porcentaje_descuento" class="Modal_Input" min="1" max="100" step="0.01" required>
            <label class="Modal_Etiqueta">Requisito *</label>
            <textarea name="requisito" class="Modal_Textarea" required></textarea>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalNueva').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div class="Modal_Fondo" id="modalEditar">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">✏️ Editar Promoción</h2>
        <form id="formEditar" method="POST">
            @csrf @method('PUT')
            <label class="Modal_Etiqueta">Descuento (%) *</label>
            <input type="number" id="editDescuento" name="porcentaje_descuento" class="Modal_Input" required>
            <label class="Modal_Etiqueta">Requisito *</label>
            <textarea id="editRequisito" name="requisito" class="Modal_Textarea" required></textarea>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalEditar').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.Menu_Opciones.open').forEach(d => { if(d!==el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.Menu_Opciones.open').forEach(d => d.classList.remove('open')));

function abrirEditar(id, desc, req) {
    document.getElementById('editDescuento').value = desc;
    document.getElementById('editRequisito').value = req;
    document.getElementById('formEditar').action = '/promociones/'+id;
    document.getElementById('modalEditar').classList.add('active');
}
</script>

</body>
</html>
