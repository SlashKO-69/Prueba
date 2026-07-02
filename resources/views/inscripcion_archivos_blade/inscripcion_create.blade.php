<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Inscripción — GymTrainer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
</head>
<body>

@include('layouts.sidebar')

<div class="Contenido_Principal">
    <div class="Encabezado_Pagina">
        <h1 class="Titulo_Pagina">📋 Nueva Inscripción</h1>
    </div>

    @if($errors->any())
        <div class="Mostrar_Error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="Tarjeta">
        <form action="{{ route('inscripciones.store') }}" method="POST">
            @csrf
            <div class="Campo">
                <label for="ci_cliente">CI del Cliente</label>
                <input type="text" name="ci_cliente" id="ci_cliente" required placeholder="Ingrese CI del cliente">
            </div>

            <div class="Campo">
                <label for="meses">Meses de inscripción</label>
                <input type="number" name="meses" id="meses" min="1" max="24" value="1" required>
            </div>

            <div class="Campo">
                <label for="monto">Monto (Bs.)</label>
                <input type="number" name="monto" id="monto" min="1" step="0.01" required placeholder="0.00">
            </div>

            <div class="Campo">
                <label for="id_promocion">Promocion (opcional)</label>
                <select name="id_promocion" id="id_promocion">
                    <option value="">Sin promoción</option>
                    @foreach($promociones as $promo)
                        <option value="{{ $promo->id_promocion }}">
                            {{ $promo->requisito }} - {{ $promo->porcentaje_descuento }}% descuento
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="Botones">
                <button type="submit" class="Boton_Buscar"> Registrar Inscripción </button>
                <a href="{{ route('inscripciones.index') }}" class="Boton_Secundario"> Cancelar </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
