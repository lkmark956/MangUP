<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        {{-- Logo --}}
        <a class="navbar-brand" href="{{ route('home') }}">
            Mang<span>UP</span>
        </a>
        
        {{-- Mobile Toggle --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        {{-- Navigation --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'manga' ? 'active' : '' }}" href="{{ route('productos.index', ['tipo' => 'manga']) }}">Mangas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'figura' ? 'active' : '' }}" href="{{ route('productos.index', ['tipo' => 'figura']) }}">Figuras</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('tipo') == 'merch' ? 'active' : '' }}" href="{{ route('productos.index', ['tipo' => 'merch']) }}">Merch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('productos.index') && !request('tipo') ? 'active' : '' }}" href="{{ route('productos.index') }}">Catálogo</a>
                </li>
            </ul>
            
            {{-- Icons & Auth --}}
            <div class="nav-icons">
                {{-- Search --}}
                <a href="{{ route('productos.index') }}" class="nav-icon-btn" title="Buscar">
                    <i class="bi bi-search"></i>
                </a>
                
                @auth
                    {{-- User Dropdown --}}
                    <div class="user-dropdown">
                        <div class="user-dropdown-toggle">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="user-name d-none d-lg-inline">{{ Str::limit(Auth::user()->name, 10) }}</span>
                            <i class="bi bi-chevron-down d-none d-lg-inline" style="font-size: 0.7rem;"></i>
                        </div>
                        <div class="user-dropdown-menu">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="admin-link">
                                    <i class="bi bi-speedometer2"></i>Panel de Administración
                                </a>
                                <hr>
                            @endif
                            <a href="{{ route('cuenta.datos-personales') }}">
                                <i class="bi bi-person"></i>Mi cuenta
                            </a>
                            <a href="{{ route('cuenta.pedidos') }}">
                                <i class="bi bi-box-seam"></i>Mis pedidos
                            </a>
                            <hr>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit">
                                    <i class="bi bi-box-arrow-right"></i>Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    {{-- Cart --}}
                    <a href="{{ route('carrito.index') }}" class="btn-cart position-relative">
                        <i class="bi bi-bag"></i>
                        <span class="cart-badge" id="cartBadge">{{ array_sum(array_column(session('carrito', []), 'cantidad')) }}</span>
                    </a>
                @else
                    {{-- Login Button --}}
                    <a href="{{ route('login') }}" class="nav-icon-btn" title="Iniciar sesión">
                        <i class="bi bi-person"></i>
                    </a>
                    
                    {{-- Cart (disabled for guests) --}}
                    <a href="{{ route('login') }}" class="btn-cart position-relative" title="Inicia sesión para ver tu carrito">
                        <i class="bi bi-bag"></i>
                        <span class="cart-badge">0</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
