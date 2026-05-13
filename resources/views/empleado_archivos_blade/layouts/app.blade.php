<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gym Tr4iner</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="overlay centrado">
        {{-- Logo --}}
        <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTr4iner Logo" class="logo">

        {{-- Título dinámico --}}
        <h1>@yield('title', 'Panel de Administradora')</h1>

        {{-- Contenido dinámico --}}
        <div class="container">
            @yield('content')
        </div>
    </div>
</body>
</html>
