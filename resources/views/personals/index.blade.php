@extends('layouts.app')

@section('content')
    <h1>Lista de Personal</h1>
    <a href="{{ route('personals.create') }}" class="btn btn-primary">Añadir Personal</a>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido Paterno</th><th>CI</th><th>Cargo</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personals as $personal)
                <tr>
                    <td>{{ $personal->id }}</td>
                    <td>{{ $personal->nombre }}</td>
                    <td>{{ $personal->apaterno }}</td>
                    <td>{{ $personal->ci }}</td>
                    <td>{{ $personal->cargo }}</td>
                    <td>
                        <a href="{{ route('personals.show',$personal->id) }}">Ver</a>
                        <a href="{{ route('personals.edit',$personal->id) }}">Editar</a>
                        <form action="{{ route('personals.destroy',$personal->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
