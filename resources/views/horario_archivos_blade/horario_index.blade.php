<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios — GymTrainer</title>

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

        <h1 class="Titulo_Pagina">
            🗓 Horarios de Empleados
        </h1>

        <div class="Acciones_Pagina">
            <button class="Boton Boton_Principal"
            onclick="document.getElementById('modalNuevo').classList.add('active')">
                + Nuevo Horario
            </button>
        </div>

    </div>


    @if(session('success'))
        <div class="Mostrar_Bien">
            ✅ {{ session('success') }}
        </div>
    @endif


    <div class="Tarjeta">

        @if($horarios->isEmpty())

            <div class="Sin_Registros">
                No hay horarios registrados aún.
            </div>

        @else


            <table>

                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Turno</th>
                        <th>Acciones</th>
                    </tr>
                </thead>


                <tbody>

                @foreach($horarios as $horario)

                    <tr>

                        <td>
                            {{ $horario->empleado->nombre ?? '—' }}
                            {{ $horario->empleado->apaterno ?? '' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($horario->fecha)->format('d/m/Y') }}
                        </td>

                        <td>{{ $horario->hora_entrada }}</td>

                        <td>{{ $horario->hora_salida }}</td>


                        <td>

                            <span class="Estado Estado-activo">
                                {{ ucfirst($horario->turno) }}
                            </span>

                        </td>


                        <td>

                            <div class="Menu_Opciones"
                            onclick="toggleDropdown(this)">

                                <button class="Boton_Opciones">
                                    Opciones ▾
                                </button>


                                <div class="Menu_Lista">

                                    <form action="{{ route('horarios.destroy',$horario->id_horario) }}"
                                    method="POST"
                                    onsubmit="return confirm('¿Eliminar este horario?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="Menu_Item Opcion_Peligro">
                                            🗑 Eliminar
                                        </button>

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
                    Total:
                    <span>{{ $horarios->count() }}</span>
                    horarios
                </span>

            </div>


        @endif


    </div>

</div>



<div class="Modal_Fondo" id="modalNuevo">

    <div class="Modal_Caja">

        <h2 class="Modal_Titulo">
            🗓 Nuevo Horario
        </h2>


        <form action="{{ route('horarios.store') }}" method="POST">

            @csrf


            <label class="Modal_Etiqueta">
                Empleado *
            </label>

            <select name="ci_empleado" class="Modal_Select" required>

                <option value="">
                    Seleccionar empleado...
                </option>

                @foreach($empleados as $emp)

                    <option value="{{ $emp->ci_empleado }}">
                        {{ $emp->nombre }} {{ $emp->apaterno }}
                    </option>

                @endforeach

            </select>


            <label class="Modal_Etiqueta">Fecha *</label>

            <input type="date" name="fecha" class="Modal_Input" required>


            <label class="Modal_Etiqueta">Hora entrada *</label>

            <input type="time" name="hora_entrada" class="Modal_Input" required>


            <label class="Modal_Etiqueta">Hora salida *</label>

            <input type="time" name="hora_salida" class="Modal_Input" required>


            <label class="Modal_Etiqueta">Turno *</label>

            <select name="turno" class="Modal_Select" required>

                <option value="mañana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>

            </select>


            <div class="Modal_Botones">

                <button type="button"
                class="Modal_Cancelar"
                onclick="document.getElementById('modalNuevo').classList.remove('active')">

                    Cancelar

                </button>


                <button type="submit"
                class="Modal_Guardar">

                    Guardar

                </button>

            </div>


        </form>


    </div>

</div>


<script>
function toggleDropdown(el) {
    event.stopPropagation();
    document.querySelectorAll('.Menu_Opciones.open').forEach(d => {
        if(d!==el) d.classList.remove('open');
    });
    el.classList.toggle('open');
}

document.addEventListener('click', () =>
    document.querySelectorAll('.Menu_Opciones.open')
    .forEach(d => d.classList.remove('open'))
);
</script>


</body>
</html>