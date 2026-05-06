@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Añadir Personal</h2>
    <form action="{{ route('personals.store') }}" method="POST">
        @csrf
        <div>
            <label>Nombre:</label>
            <input type="text" name="nombre" required>
        </div>
        <div>
            <label>Apellido Paterno:</label>
            <input type="text" name="apaterno" required>
        </div>
        <div>
            <label>Apellido Materno:</label>
            <input type="text" name="amaterno" required>
        </div>
        <div>
            <label>CI:</label>
            <input type="text" name="ci" required>
        </div>
        <div>
            <label>Cargo:</label>
            <input type="text" name="cargo" required>
        </div>
        <div>
            <label>Fecha Contratación:</label>
            <input type="date" name="fecha_contratacion" required>
        </div>
        <div>
            <label>Fecha Pago:</label>
            <input type="date" name="fecha_pago" required>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>

    {{-- Botón volver --}}
    <div style="margin-top:15px;">
        <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary">Volver al Panel</a>
    </div>
</div>
@endsection
