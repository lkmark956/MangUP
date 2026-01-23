<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ url('/') }}">
            Mang<span>UP</span>
        </a>
        
        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-book"></i> Mangas
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Todos los Mangas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Acción</a></li>
                        <li><a class="dropdown-item" href="#">Romance</a></li>
                        <li><a class="dropdown-item" href="#">Terror</a></li>
                        <li><a class="dropdown-item" href="#">Fantasía</a></li>
                        <li><a class="dropdown-item" href="#">Comedia</a></li>
                        <li><a class="dropdown-item" href="#">Ver todas las categorías</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-stars"></i> Figuras
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Todas las Figuras</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">One Piece</a></li>
                        <li><a class="dropdown-item" href="#">Naruto</a></li>
                        <li><a class="dropdown-item" href="#">Dragon Ball</a></li>
                        <li><a class="dropdown-item" href="#">Demon Slayer</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-bag"></i> Merch
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Todo el Merch</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Camisetas</a></li>
                        <li><a class="dropdown-item" href="#">Sudaderas</a></li>
                        <li><a class="dropdown-item" href="#">Tazas</a></li>
                        <li><a class="dropdown-item" href="#">Posters</a></li>
                        <li><a class="dropdown-item" href="#">Llaveros</a></li>
                    </ul>
                </li>
            </ul>
            
            <!-- Search -->
            <form class="d-flex me-3" role="search">
                <div class="input-group">
                    <input class="form-control" type="search" placeholder="Buscar productos..." aria-label="Buscar">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- User & Cart -->
            <div class="d-flex align-items-center">
                <a href="#" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-person"></i>
                </a>
                <a href="#" class="btn btn-cart">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>
