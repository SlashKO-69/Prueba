<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">👥 Gestión de Clientes</h1>
        <div class="Acciones_Pagina">
            <a href="{{ route('clientes.create') }}" class="Boton Boton_Principal">+ Nuevo Cliente</a>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Filtros_Container">
        <form method="GET" action="{{ route('clientes.index') }}" class="Filtros_Form">
            <input type="hidden" name="ordenar" value="{{ $ordenar }}">
            <input type="hidden" name="direccion" value="{{ $direccion }}">

            <div class="Busqueda_Grupo">
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre o CI..."
                       class="Busqueda_Input">
                <button type="submit" class="Boton Buscar_Boton">Buscar</button>
                @if($buscar)
                    <a href="{{ route('clientes.index', ['estado' => $estado, 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                       class="Boton Buscar_Limpiar">Limpiar</a>
                @endif
            </div>

            <div class="Filtros_Botones">
                <span class="Filtros_Label">Filtrar por estado:</span>
                <a href="{{ route('clientes.index', ['buscar' => $buscar, 'estado' => '', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === '' ? 'Boton_Filtro_Activo' : '' }}">Todos</a>
                <a href="{{ route('clientes.index', ['buscar' => $buscar, 'estado' => 'activo', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'activo' ? 'Boton_Filtro_Activo' : '' }}">Activos</a>
                <a href="{{ route('clientes.index', ['buscar' => $buscar, 'estado' => 'por-vencer', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'por-vencer' ? 'Boton_Filtro_Activo' : '' }}">Por vencer</a>
                <a href="{{ route('clientes.index', ['buscar' => $buscar, 'estado' => 'vencido', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'vencido' ? 'Boton_Filtro_Activo' : '' }}">Vencidos</a>
            </div>
        </form>
    </div>

    <div class="Tarjeta">
        @if($clientes->isEmpty())
            <div class="Sin_Registros">No hay clientes registrados aún.</div>
        @else
            <table>
                <thead>
                    <tr>
                        @php
                            function urlOrdenar($col, $buscar, $estado, $ordenar, $direccion) {
                                $nuevaDireccion = ($ordenar === $col && $direccion === 'asc') ? 'desc' : 'asc';
                                return route('clientes.index', [
                                    'buscar' => $buscar,
                                    'estado' => $estado,
                                    'ordenar' => $col,
                                    'direccion' => $nuevaDireccion
                                ]);
                            }
                            function iconoOrden($col, $ordenar, $direccion) {
                                if ($ordenar !== $col) return '';
                                return $direccion === 'asc' ? ' &#9650;' : ' &#9660;';
                            }
                        @endphp
                        <th class="Th_Ordenable {{ $ordenar === 'Ci' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('Ci', $buscar, $estado, $ordenar, $direccion) }}">
                                CI {!! iconoOrden('Ci', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'nombre' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('nombre', $buscar, $estado, $ordenar, $direccion) }}">
                                Nombre {!! iconoOrden('nombre', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'apaterno' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('apaterno', $buscar, $estado, $ordenar, $direccion) }}">
                                Ap. Paterno {!! iconoOrden('apaterno', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'amaterno' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('amaterno', $buscar, $estado, $ordenar, $direccion) }}">
                                Ap. Materno {!! iconoOrden('amaterno', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'fecha_vencimiento' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('fecha_vencimiento', $buscar, $estado, $ordenar, $direccion) }}">
                                Vencimiento {!! iconoOrden('fecha_vencimiento', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'dias_restantes' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('dias_restantes', $buscar, $estado, $ordenar, $direccion) }}">
                                Dias restantes {!! iconoOrden('dias_restantes', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'estado' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('estado', $buscar, $estado, $ordenar, $direccion) }}">
                                Estado {!! iconoOrden('estado', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    @php
                        $dias = $cliente->dias_restantes;
                        if (is_null($dias)) { $sc='Estado-pendiente'; $st='Sin inscripción'; $dt='—'; $dc=''; }
                        elseif ($dias < 0) { $sc='Estado-vencido'; $st='Vencido'; $dt=abs($dias).' días vencido'; $dc='Dias_Peligro'; }
                        elseif ($dias <= 5) { $sc='Estado-por-vencer'; $st='Por vencer'; $dt=$dias.' días'; $dc='Dias_Aviso'; }
                        else { $sc='Estado-activo'; $st='Activo'; $dt=$dias.' días'; $dc='Dias_Ok'; }
                    @endphp
                    <tr>
                        <td>{{ $cliente->Ci }}</td>
                        <td>{{ $cliente->nombre }}</td>
                        <td>{{ $cliente->apaterno }}</td>
                        <td>{{ $cliente->amaterno ?? '—' }}</td>
                        <td>{{ $cliente->fecha_vencimiento ? \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') : '—' }}</td>
                        <td class="{{ $dc }}">{{ $dt }}</td>
                        <td><span class="Estado {{ $sc }}">{{ $st }}</span></td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones▾</button>
                                <div class="Menu_Lista">
                                    <a href="{{ route('clientes.show', $cliente->Ci) }}" class="Menu_Item">👁 Ver detalle</a>
                                    <a href="{{ route('clientes.edit', $cliente->Ci) }}" class="Menu_Item Opcion_Aviso">✏️ Editar</a>
                                    <button class="Menu_Item Opcion_Especial"
                                        onclick="abrirModal('{{ $cliente->Ci }}', '{{ $cliente->nombre }} {{ $cliente->apaterno }}')">
                                        🔄 Reinscribir
                                    </button>
                                    <div class="Menu_Divisor"></div>
                                    <form action="{{ route('clientes.destroy', $cliente->Ci) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar cliente {{ $cliente->nombre }}?')">
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
                <span class="Total_Registros">
                    Mostrando: <span>{{ $clientes->count() }}</span> clientes
                    @if($buscar || $estado)
                        (filtrados)
                    @endif
                </span>
            </div>
        @endif
    </div>
</div>

{{-- Modal Reinscribir --}}
<div class="Modal_Fondo" id="modalReinscribir">
    <div class="Modal_Caja">
        <h2 class="Modal_Titulo">🔄 Reinscribir Cliente</h2>
        <p id="modalNombreCliente" style="color:#888; font-size:13px; margin-bottom:5px;"></p>
        <form id="formReinscribir" method="POST">
            @csrf
            <label class="Modal_Etiqueta">Meses *</label>
            <input type="number" name="meses" class="Modal_Input" min="1" max="24" value="1"
                   oninput="calcularMontoModal(this.value)" required>
            <label class="Modal_Etiqueta">Promoción (opcional)</label>
            <select name="id_promocion" class="Modal_Select" onchange="aplicarPromocionModal(this)">
                <option value="">Sin promoción</option>
                @foreach($promociones as $promo)
                    <option value="{{ $promo->id_promocion }}"
                            data-descuento="{{ $promo->porcentaje_descuento }}"
                            data-requisito="{{ $promo->requisito }}">
                        {{ $promo->porcentaje_descuento }}% — {{ $promo->requisito }}
                    </option>
                @endforeach
            </select>
            <div id="promoInfoModal" style="display:none; background:rgba(46,204,113,0.08); border:1px solid rgba(46,204,113,0.2); border-radius:6px; padding:8px 12px; margin-top:8px; color:#2ECC71; font-size:12px;"></div>
            <label class="Modal_Etiqueta">Monto (Bs.) *</label>
            <input type="number" name="monto" id="montoModal" class="Modal_Input" value="100" min="1" step="0.01" required>
            <div class="Modal_Botones">
                <button type="button" class="Modal_Cancelar"
                        onclick="document.getElementById('modalReinscribir').classList.remove('active')">Cancelar</button>
                <button type="submit" class="Modal_Guardar">Reinscribir</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.Menu_Opciones.open').forEach(d => { if(d !== el) d.classList.remove('open'); });
    el.classList.toggle('open');
}
document.addEventListener('click', () => document.querySelectorAll('.Menu_Opciones.open').forEach(d => d.classList.remove('open')));

let mesesModal = 1, descuentoModal = 0;
function abrirModal(ci, nombre) {
    document.getElementById('modalNombreCliente').innerHTML = 'Cliente: <span style="color:#2ECC71;font-weight:600;">'+nombre+'</span>';
    document.getElementById('formReinscribir').action = '/clientes/'+ci+'/reinscribir';
    mesesModal = 1; descuentoModal = 0;
    document.getElementById('montoModal').value = '100.00';
    document.getElementById('promoInfoModal').style.display = 'none';
    document.getElementById('modalReinscribir').classList.add('active');
}
function calcularMontoModal(m) { mesesModal = parseInt(m)||1; actualizarMontoModal(); }
function aplicarPromocionModal(sel) {
    const op = sel.options[sel.selectedIndex];
    descuentoModal = parseFloat(op.dataset.descuento)||0;
    const info = document.getElementById('promoInfoModal');
    if(descuentoModal>0){info.style.display='block';info.innerHTML='🎁 '+op.dataset.requisito;}
    else{info.style.display='none';}
    actualizarMontoModal();
}
function actualizarMontoModal() {
    let m = 100*mesesModal;
    if(descuentoModal>0) m = m-(m*descuentoModal/100);
    document.getElementById('montoModal').value = m.toFixed(2);
}
</script>

</body>
</html>