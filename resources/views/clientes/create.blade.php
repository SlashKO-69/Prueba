@extends('layouts.app')

@section('title', 'Añadir Cliente')

@section('content')
<div class="form-box">
    <form action="{{ route('clientes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Apellido Paterno:</label>
            <input type="text" name="apaterno" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Apellido Materno:</label>
            <input type="text" name="amaterno" class="form-input" required>
        </div>
        <div class="form-group">
            <label>CI:</label>
            <input type="text" name="ci" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Fecha Inscripción:</label>
            <input type="date" name="fecha_inscripcion" class="form-input" required>
        </div>
        <div class="form-group">
            <label>Fecha Vencimiento:</label>
            <input type="date" name="fecha_vencimiento" class="form-input" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary">Volver al Panel</a>
        </div>
    </form>
</div>
@endsection
