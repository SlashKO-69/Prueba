<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesiones — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">🏋️ Control de Sesiones</h1>
        <div class="Acciones_pagina_sb">
            <button class="Boton Boton_Principal" onclick="document.getElementById('modalEntrada').classList.add('active')">🟢 Registrar Entrada</button>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert-error">⚠️ {{ session('error') }}</div> @endif

    <div class="Tarjeta">
        @if($sesiones->isEmpty())
            <div class="Sin_Registros">No hay sesiones registradas aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>CI</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Duración</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiones as $ses)
                    @php
                        $enGym = is_null($ses->Final);
                        $duracion = '—';
                        if (!$enGym && $ses->Inicio && $ses->Final) {
                            $totalMins = (int) \Carbon\Carbon::parse($ses->Inicio)->diffInMinutes(\Carbon\Carbon::parse($ses->Final));
                            $horas = intdiv($totalMins, 60); $minutos = $totalMins % 60;
                            $duracion = $horas > 0 ? "{$horas}h {$minutos}min" : "{$totalMins}min";
                        }
                    @endphp
                    <tr>
                        <td>{{ $ses->id_sesion }}</td>
                        <td>{{ $ses->cliente->nombre ?? '—' }} {{ $ses->cliente->apaterno ?? '' }}</td>
                        <td>{{ $ses->ci_cliente }}</td>
                        <td>{{ \Carbon\Carbon::parse($ses->Inicio)->format('d/m/Y H:i') }}</td>
                        <td>{{ $ses->Final ? \Carbon\Carbon::parse($ses->Final)->format('d/m/Y H:i') : '—' }}</td>
                        <td>{{ $duracion }}</td>
                        <td>
                            @if($enGym)
                                <span class="Estado Estado-activo">En gym</span>
                            @else
                                <span class="Estado Estado-pendiente">Finalizado</span>
                            @endif
                        </td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones ▾</button>
                                <div class="Menu_Lista">
                                    <a href="{{ route('sesiones.show', $ses->id_sesion) }}" class="Menu_Item">👁 Ver detalle</a>
                                    @if($enGym)
                                        <button class="Menu_Item Opcion_Peligro"
                                            onclick="abrirSalida({{ $ses->id_sesion }}, '{{ $ses->cliente->nombre ?? '' }} {{ $ses->cliente->apaterno ?? '' }}')">
                                            🔴 Registrar Salida
                                        </button>
                                    @endif
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('sesiones.destroy', $ses->id_sesion) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar?')">
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
                <span class="Total_Registros">Total: <span>{{ $sesiones->count() }}</span> sesiones</span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Entrada --}}
<div class="Modal_Fondo" id="modalEntrada">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🟢 Registrar Entrada</h2>
        <form action="{{ route('sesiones.entrada') }}" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Buscar cliente</label>
            <input type="text" id="buscadorCliente" class="Modal_Input" placeholder="Escribe nombre o CI..."
                   oninput="filtrarClientes(this.value)">
            <label class="Modal_Etiqueta">Cliente inscrito *</label>
            <select name="ci_cliente" id="selectCliente" class="Modal_Select" required size="5">
                <option value="">Seleccionar...</option>
                @foreach($clientesActivos as $cliente)
                    <option value="{{ $cliente->Ci }}"
                            data-texto="{{ strtolower($cliente->nombre.' '.$cliente->apaterno.' '.$cliente->Ci) }}">
                        {{ $cliente->nombre }} {{ $cliente->apaterno }} — CI: {{ $cliente->Ci }}
                    </option>
                @endforeach
            </select>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="cerrarModalEntrada()">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Registrar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Salida --}}
<div class="Modal_Fondo" id="modalSalida">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🔴 Registrar Salida</h2>
        <p id="nombreClienteSalida" style="color:#888; font-size:13px; margin-bottom:10px;"></p>
        <form id="formSalida" method="POST">
            @csrf @method('PATCH')
            <label class="Modal_Etiqueta">Empleado que atendió (opcional)</label>
            <select name="ci_empleado" class="Modal_Select">
                <option value="">Sin empleado asignado</option>
                @foreach($empleados as $emp)
                    <option value="{{ $emp->ci_empleado }}">{{ $emp->nombre }} {{ $emp->apaterno }}</option>
                @endforeach
            </select>
            <label class="Modal_Etiqueta">Detalle de la sesión *</label>
            <textarea name="Detalles" class="Modal_Textarea" placeholder="Máquinas usadas, actividades..." required></textarea>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar" onclick="document.getElementById('modalSalida').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Registrar Salida</button>
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

function filtrarClientes(texto) {
    const filtro = texto.toLowerCase();
    document.querySelectorAll('#selectCliente option').forEach(op => {
        if(!op.value) return;
        op.style.display = (op.dataset.texto||'').includes(filtro) ? '' : 'none';
    });
}
function cerrarModalEntrada() {
    document.getElementById('modalEntrada').classList.remove('active');
    document.getElementById('buscadorCliente').value = '';
    filtrarClientes('');
}
function abrirSalida(id, nombre) {
    document.getElementById('nombreClienteSalida').innerHTML = 'Cliente: <span style="color:#2ECC71;font-weight:600;">'+nombre+'</span>';
    document.getElementById('formSalida').action = '/sesiones/'+id+'/salida';
    document.getElementById('modalSalida').classList.add('active');
}
</script>
</body>
</html>
