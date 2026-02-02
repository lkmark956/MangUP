{{-- 
    Layout principal del Panel de Administración
    
    Este layout define la estructura base de todas las páginas del admin:
    - Sidebar con navegación
    - Header con información del usuario
    - Área de contenido principal
    
    Las vistas hijas usan @extends('admin.layouts.app') y @section('content')
    
    CSS: Archivo externo en public/css/admin.css (mejores prácticas)
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Admin') - MangUP</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Admin CSS (archivo externo - mejores prácticas) -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand">
                Mang<span>UP</span>
            </a>
            <span class="admin-sidebar-badge">Admin</span>
        </div>
        
        <nav class="admin-nav">
            <div class="admin-nav-section">Principal</div>
            
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="admin-nav-section">Gestión de Productos</div>
            
            <a href="{{ route('admin.mangas.index') }}" class="admin-nav-link {{ request()->routeIs('admin.mangas.*') ? 'active' : '' }}">
                <i class="bi bi-book"></i>
                <span>Mangas</span>
            </a>
            
            <a href="{{ route('admin.figuras.index') }}" class="admin-nav-link {{ request()->routeIs('admin.figuras.*') ? 'active' : '' }}">
                <i class="bi bi-trophy"></i>
                <span>Figuras</span>
            </a>
            
            <a href="{{ route('admin.merch.index') }}" class="admin-nav-link {{ request()->routeIs('admin.merch.*') ? 'active' : '' }}">
                <i class="bi bi-bag"></i>
                <span>Merchandising</span>
            </a>
            
            <div class="admin-nav-section">Configuración</div>
            
            <a href="{{ route('admin.categorias.index', 'manga') }}" class="admin-nav-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                <span>Categorías</span>
            </a>
            
            <a href="{{ route('admin.usuarios.index') }}" class="admin-nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </a>
            
            <div class="admin-nav-section">Acciones</div>
            
            <a href="{{ route('home') }}" class="admin-nav-link" target="_blank">
                <i class="bi bi-shop"></i>
                <span>Ver tienda</span>
            </a>
            
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="admin-nav-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1 class="admin-header-title">@yield('page-title', 'Dashboard')</h1>
            
            <div class="admin-user-menu">
                <span class="admin-user-name">{{ auth()->user()->name }}</span>
                <div class="admin-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>
        
        <!-- Content -->
        <div class="admin-content">
            {{-- Mensajes flash --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
