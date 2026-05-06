<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administradora</title>
    <link rel="stylesheet" href="{{ asset('css/fondo.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script>
        function mostrarTabla(tipo) {
            document.getElementById('clientes').style.display = tipo === 'clientes' ? 'block' : 'none';
            document.getElementById('personal').style.display = tipo === 'personal' ? 'block' : 'none';
            document.getElementById('botones').style.display = 'none';
            document.getElementById('volver').style.display = 'block';
            document.querySelector('.overlay').classList.remove('centrado');
            document.querySelector('.overlay').classList.add('arriba');
        }

        function volverMenu() {
            document.getElementById('clientes').style.display = 'none';
            document.getElementById('personal').style.display = 'none';
            document.getElementById('botones').style.display = 'flex';
            document.getElementById('volver').style.display = 'none';
            document.querySelector('.overlay').classList.remove('arriba');
            document.querySelector('.overlay').classList.add('centrado');
        }
    </script>
</head>
<body>
    <div class="overlay centrado">
        <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTrainner Logo" class="logo">
        <h1>Panel de Administradora</h1>

        {{-- Botón volver al login --}}
        <div class="volver-login">
            <a href="{{ route('login') }}" class="btn-login">Volver al Login</a>
        </div>

        <div class="container">
            {{-- Botones principales --}}
            <div id="botones" class="botones">
                <button onclick="mostrarTabla('clientes')" class="btn-ver">Ver Clientes</button>
                <button onclick="mostrarTabla('personal')" class="btn-ver">Ver Personal</button>
            </div>

            {{-- Botón volver --}}
            <div id="volver" style="display:none; text-align:center;">
                <button onclick="volverMenu()" class="btn-volver">Volver</button>
            </div>

            {{-- Tabla Clientes --}}
            <div id="clientes" style="display:none;">
                <h2>Clientes</h2>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">Añadir Cliente</a>
                <table>
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
                        @foreach($clientes as $cliente)
                            <tr>
                                <td>{{ $cliente->id }}</td>
                                <td>{{ $cliente->nombre }}</td>
                                <td>{{ $cliente->apaterno }}</td>
                                <td>{{ $cliente->amaterno }}</td>
                                <td>{{ $cliente->ci }}</td>
                                <td>{{ $cliente->fecha_inscripcion }}</td>
                                <td>{{ $cliente->fecha_vencimiento }}</td>
                                <td class="acciones-fila">
                                    <a href="{{ route('clientes.show',$cliente->id) }}" class="btn btn-info">Ver</a>
                                    <a href="{{ route('clientes.edit',$cliente->id) }}" class="btn btn-warning">Editar</a>
                                    <form action="{{ route('clientes.destroy',$cliente->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tabla Personal --}}
            <div id="personal" style="display:none;">
                <h2>Personal</h2>
                <a href="{{ route('personals.create') }}" class="btn btn-primary">Añadir Personal</a>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>CI</th>
                            <th>Cargo</th>
                            <th>Fecha Contratación</th>
                            <th>Fecha Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($personals as $personal)
                            <tr>
                                <td>{{ $personal->id }}</td>
                                <td>{{ $personal->nombre }}</td>
                                <td>{{ $personal->apaterno }}</td>
                                <td>{{ $personal->amaterno }}</td>
                                <td>{{ $personal->ci }}</td>
                                <td>{{ $personal->cargo }}</td>
                                <td>{{ $personal->fecha_contratacion }}</td>
                                <td>{{ $personal->fecha_pago }}</td>
                                <td class="acciones-fila">
                                    <a href="{{ route('personals.show',$personal->id) }}" class="btn btn-info">Ver</a>
                                    <a href="{{ route('personals.edit',$personal->id) }}" class="btn btn-warning">Editar</a>
                                    <form action="{{ route('personals.destroy',$personal->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
