@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Botón azul Volver al Login -->
<div style="margin-top:10px;">
    <a href="{{ route('login') }}" class="btn btn-primary">Volver al Login</a>
</div>

<!-- Botón rojo Volver debajo del azul -->
<div style="margin-top:10px;">
    <a href="{{ route('dashboard.admin') }}" class="btn btn-danger">Volver</a>
</div>

    <!-- Letras verdes -->
    <h2 style="color:#00ff66; margin-top:15px;">Clientes</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Botón azul Añadir Cliente debajo de las letras verdes -->
    <div style="margin-top:10px;">
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">Añadir Cliente</a>
    </div>

    <!-- Tabla -->
    <table style="margin-top:15px; width:100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>CI</th>
                <th>Fecha Inscripción</th>
                <th>Fecha Vencimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datos_clientes as $cliente)
            <tr>
                <td>{{ $cliente->id }}</td>
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->apaterno }}</td>
                <td>{{ $cliente->amaterno }}</td>
                <td>{{ $cliente->ci }}</td>
                <td>{{ $cliente->fecha_inscripcion }}</td>
                <td>{{ $cliente->fecha_vencimiento }}</td>
                <td class="acciones-fila" style="display:flex; gap:5px;">
                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-info">Ver</a>
                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
