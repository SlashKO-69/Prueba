@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')
<div class="form-box">
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-input" value="{{ $cliente->nombre }}" required>
        </div>
        <div class="form-group">
            <label>Apellido Paterno:</label>
            <input type="text" name="apaterno" class="form-input" value="{{ $cliente->apaterno }}" required>
        </div>
        <div class="form-group">
            <label>Apellido Materno:</label>
            <input type="text" name="amaterno" class="form-input" value="{{ $cliente->amaterno }}" required>
        </div>
        <div class="form-group">
            <label>CI:</label>
            <input type="text" name="ci" class="form-input" value="{{ $cliente->ci }}" required>
        </div>
        <div class="form-group">
            <label>Fecha Inscripción:</label>
            <input type="date" name="fecha_inscripcion" class="form-input"
                   value="{{ \Carbon\Carbon::parse($cliente->fecha_inscripcion)->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>Fecha Vencimiento:</label>
            <input type="date" name="fecha_vencimiento" class="form-input"
                   value="{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('Y-m-d') }}" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Guardar Cambios</button>
            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver al Listado</a>
        </div>
    </form>
</div>
@endsection
