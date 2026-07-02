<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones — GymTrainer</title>
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
        <h1 class="Titulo_Pagina">📋 Inscripciones</h1>
        <div class="Acciones_Pagina">
            <a href="{{ route('inscripciones.exportarExcel', ['buscar' => $buscar, 'estado' => $estado, 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
               class="Boton Boton_Exportar">Descargar Excel</a>
            <a href="{{ route('inscripciones.create') }}" class="Boton Boton_Principal">+ Nueva Inscripción</a>
        </div>
    </div>

    @if(session('success')) <div class="Mostrar_Bien">✅ {{ session('success') }}</div> @endif

    <div class="Filtros_Container">
        <form method="GET" action="{{ route('inscripciones.index') }}" class="Filtros_Form">
            <input type="hidden" name="ordenar" value="{{ $ordenar }}">
            <input type="hidden" name="direccion" value="{{ $direccion }}">

            <div class="Busqueda_Grupo">
                <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por cliente o CI..."
                       class="Busqueda_Input">
                <button type="submit" class="Boton Buscar_Boton">Buscar</button>
                @if($buscar)
                    <a href="{{ route('inscripciones.index', ['estado' => $estado, 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                       class="Boton Buscar_Limpiar">Limpiar</a>
                @endif
            </div>

            <div class="Filtros_Botones">
                <span class="Filtros_Label">Filtrar por estado:</span>
                <a href="{{ route('inscripciones.index', ['buscar' => $buscar, 'estado' => '', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === '' ? 'Boton_Filtro_Activo' : '' }}">Todos</a>
                <a href="{{ route('inscripciones.index', ['buscar' => $buscar, 'estado' => 'activo', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'activo' ? 'Boton_Filtro_Activo' : '' }}">Activos</a>
                <a href="{{ route('inscripciones.index', ['buscar' => $buscar, 'estado' => 'por-vencer', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'por-vencer' ? 'Boton_Filtro_Activo' : '' }}">Por vencer</a>
                <a href="{{ route('inscripciones.index', ['buscar' => $buscar, 'estado' => 'vencido', 'ordenar' => $ordenar, 'direccion' => $direccion]) }}"
                   class="Boton_Filtro {{ $estado === 'vencido' ? 'Boton_Filtro_Activo' : '' }}">Vencidos</a>
            </div>
        </form>
    </div>

    <div class="Tarjeta">
        @if($inscripciones->isEmpty())
            <div class="Sin_Registros">No hay inscripciones aún. Se generan al registrar un cliente.</div>
        @else
            <table>
                <thead>
                    <tr>
                        @php
                            function urlOrdenar($col, $buscar, $estado, $ordenar, $direccion) {
                                $nuevaDireccion = ($ordenar === $col && $direccion === 'asc') ? 'desc' : 'asc';
                                return route('inscripciones.index', [
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
                        <th class="Th_Ordenable {{ $ordenar === 'id' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('id', $buscar, $estado, $ordenar, $direccion) }}">
                                # {!! iconoOrden('id', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'cliente' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('cliente', $buscar, $estado, $ordenar, $direccion) }}">
                                Cliente {!! iconoOrden('cliente', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'ci_cliente' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('ci_cliente', $buscar, $estado, $ordenar, $direccion) }}">
                                CI {!! iconoOrden('ci_cliente', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'fecha_inscripcion' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('fecha_inscripcion', $buscar, $estado, $ordenar, $direccion) }}">
                                Inscripción {!! iconoOrden('fecha_inscripcion', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'fecha_vencimiento' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('fecha_vencimiento', $buscar, $estado, $ordenar, $direccion) }}">
                                Vencimiento {!! iconoOrden('fecha_vencimiento', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'dias_restantes' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('dias_restantes', $buscar, $estado, $ordenar, $direccion) }}">
                                Días restantes {!! iconoOrden('dias_restantes', $ordenar, $direccion) !!}
                            </a>
                        </th>
                        <th class="Th_Ordenable {{ $ordenar === 'monto' ? 'Th_Orden_Activo' : '' }}">
                            <a href="{{ urlOrdenar('monto', $buscar, $estado, $ordenar, $direccion) }}">
                                Monto {!! iconoOrden('monto', $ordenar, $direccion) !!}
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
                    @foreach($inscripciones as $ins)
                    @php
                        $dias = $ins->dias_restantes;
                        if ($dias < 0) { $sc='Estado-vencido'; $st='Vencido'; $dc='Dias_Peligro'; }
                        elseif ($dias <= 5) { $sc='Estado-por-vencer'; $st='Por vencer'; $dc='Dias_Aviso'; }
                        else { $sc='Estado-activo'; $st='Activo'; $dc='Dias_Ok'; }
                    @endphp
                    <tr>
                        <td>{{ $ins->id }}</td>
                        <td>{{ $ins->cliente->nombre ?? '—' }} {{ $ins->cliente->apaterno ?? '' }}</td>
                        <td>{{ $ins->ci_cliente }}</td>
                        <td>{{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($ins->fecha_vencimiento)->format('d/m/Y') }}</td>
                        <td class="{{ $dc }}">{{ $dias < 0 ? abs($dias).' días vencido' : $dias.' días' }}</td>
                        <td>Bs. {{ number_format($ins->monto, 2) }}</td>
                        <td><span class="Estado {{ $sc }}">{{ $st }}</span></td>
                        <td>
                            <div class="Menu_Opciones" onclick="toggleDropdown(this)">
                                <button class="Boton_Opciones">Opciones▾</button>
                                <div class="Menu_Lista">
                                    <a href="{{ route('inscripciones.show', $ins->id) }}" class="Menu_Item">👁 Ver detalle</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:16px;">
                <span class="Total_Registros">
                    Total: <span>{{ $inscripciones->count() }}</span> inscripciones
                    @if($buscar || $estado)
                        (filtrados)
                    @endif
                </span>
            </div>
        @endif
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
