<h1>Editar Personal</h1>

<form action="{{ route('personals.update', $personal->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Nombre:</label>
    <input type="text" name="nombre" value="{{ $personal->nombre }}">

    <label>Apellido Paterno:</label>
    <input type="text" name="apaterno" value="{{ $personal->apaterno }}">

    <label>Apellido Materno:</label>
    <input type="text" name="amaterno" value="{{ $personal->amaterno }}">

    <label>CI:</label>
    <input type="text" name="ci" value="{{ $personal->ci }}">

    <label>Cargo:</label>
    <input type="text" name="cargo" value="{{ $personal->cargo }}">

    <label>Fecha Contratación:</label>
    <input type="date" name="fecha_contratacion" value="{{ $personal->fecha_contratacion->format('Y-m-d') }}">

    <label>Fecha Pago:</label>
    <input type="date" name="fecha_pago" value="{{ $personal->fecha_pago->format('Y-m-d') }}">

    <button type="submit">Actualizar</button>
</form>
