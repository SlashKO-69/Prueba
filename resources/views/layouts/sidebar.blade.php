<div class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/Logo_imagen.png') }}" alt="GymTrainer"
             style="width:100%; height:auto; object-fit:contain; display:block;">
    </div>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">{{ strtoupper(substr(session('empleado_nombre', 'U'), 0, 1)) }}</div>
        <div>
            <p class="sidebar-user-name">{{ session('empleado_nombre') }}</p>
            <p class="sidebar-user-rol">{{ session('empleado_rol') === 'admin' ? 'Administrador' : 'Empleado' }}</p>
        </div>
    </div>

    <nav class="sidebar-nav">
        <p class="sidebar-section-label">PRINCIPAL</p>

        <a href="{{ route('clientes.index') }}"
           class="sidebar-link {{ request()->is('clientes*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">👥</span> Clientes
        </a>

        <a href="{{ route('inscripciones.index') }}"
           class="sidebar-link {{ request()->is('inscripciones*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">📋</span> Inscripciones
        </a>

        <a href="{{ route('promociones.index') }}"
           class="sidebar-link {{ request()->is('promociones*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">🎁</span> Promociones
        </a>

        <a href="{{ route('sesiones.index') }}"
           class="sidebar-link {{ request()->is('sesiones*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">🏋️</span> Sesiones
        </a>

        <a href="{{ route('bandeja.index') }}"
           class="sidebar-link {{ request()->is('bandeja*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">📬</span> Bandeja
            @php $noLeidos = session('empleado_rol') === 'admin' ? \App\Models\Informe::where('leido', 0)->count() : 0; @endphp
            @if($noLeidos > 0)
                <span style="background:#dc3545; color:#fff; border-radius:50%; padding:1px 6px; font-size:10px; margin-left:auto;">{{ $noLeidos }}</span>
            @endif
        </a>

        @if(session('empleado_rol') === 'admin')
        <p class="sidebar-section-label" style="margin-top:16px;">ADMINISTRACIÓN</p>

        <a href="{{ route('empleados.index') }}"
           class="sidebar-link {{ request()->is('empleados*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">⚙️</span> Empleados
        </a>

        <a href="{{ route('horarios.index') }}"
           class="sidebar-link {{ request()->is('horarios*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">🗓</span> Horarios
        </a>

        <a href="{{ route('aparatos.index') }}"
        class="sidebar-link {{ request()->is('aparatos*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">🔧</span> Aparatos
        </a>
        
        <a href="{{ route('reuniones.index') }}"
           class="sidebar-link {{ request()->is('reuniones') ? 'active' : '' }}">
            <span class="sidebar-link-icon">📅</span> Reuniones
        </a>

        
        <a href="{{ route('sueldos.index') }}"
           class="sidebar-link {{ request()->is('sueldos*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">💰</span> Sueldos
        </a>

        <a href="{{ route('flujo-caja.index') }}"
           class="sidebar-link {{ request()->is('flujo-caja*') ? 'active' : '' }}">
            <span class="sidebar-link-icon">💵</span> Flujo de Caja
        </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form action="{{ route('empleados.logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-logout">⬅ Cerrar sesión</button>
        </form>
    </div>
</div>