{{--
    Vista del Dashboard de Administración - MangUP
    Diseño: Elegante, simple y moderno
--}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    
    {{-- Bienvenida --}}
    <div class="welcome-banner mb-4">
        <div class="d-flex align-items-center">
            <div class="welcome-icon">
                <i class="bi bi-shop"></i>
            </div>
            <div>
                <h2 class="welcome-title mb-0">¡Bienvenido, {{ Auth::user()->name }}!</h2>
                <p class="welcome-subtitle mb-0">Panel de administración de MangUP</p>
            </div>
        </div>
    </div>
    
    {{-- Tarjetas de estadísticas --}}
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-mangas">
                <div class="stat-icon">
                    <i class="bi bi-book"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $stats['total_mangas'] }}</span>
                    <span class="stat-label">Mangas</span>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-figuras">
                <div class="stat-icon">
                    <i class="bi bi-trophy"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $stats['total_figuras'] }}</span>
                    <span class="stat-label">Figuras</span>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-merch">
                <div class="stat-icon">
                    <i class="bi bi-bag"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $stats['total_merch'] }}</span>
                    <span class="stat-label">Merchandising</span>
                </div>
            </div>
        </div>
        
        <div class="col-6 col-lg-3">
            <div class="stat-card stat-card-usuarios">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $stats['total_usuarios'] }}</span>
                    <span class="stat-label">Usuarios</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Acciones Rápidas --}}
    <div class="quick-actions mb-4">
        <h5 class="section-title">
            <i class="bi bi-lightning-charge"></i>
            Acciones Rápidas
        </h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.mangas.create') }}" class="quick-action-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nuevo Manga</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.figuras.create') }}" class="quick-action-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nueva Figura</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.merch.create') }}" class="quick-action-btn">
                    <i class="bi bi-plus-lg"></i>
                    <span>Nuevo Merch</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('admin.usuarios.index') }}" class="quick-action-btn">
                    <i class="bi bi-people"></i>
                    <span>Ver Usuarios</span>
                </a>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        {{-- Stock Bajo --}}
        <div class="col-12 col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                        Alertas de Stock
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    @if($stockBajo['mangas']->count() > 0 || $stockBajo['figuras']->count() > 0)
                        <div class="stock-list">
                            @foreach($stockBajo['mangas'] as $manga)
                                <div class="stock-item">
                                    <div class="stock-info">
                                        <span class="stock-type manga">M</span>
                                        <span class="stock-name">{{ Str::limit($manga->nombre, 25) }}</span>
                                    </div>
                                    <span class="stock-count {{ $manga->stock == 0 ? 'danger' : 'warning' }}">
                                        {{ $manga->stock }} uds
                                    </span>
                                </div>
                            @endforeach
                            
                            @foreach($stockBajo['figuras'] as $figura)
                                <div class="stock-item">
                                    <div class="stock-info">
                                        <span class="stock-type figura">F</span>
                                        <span class="stock-name">{{ Str::limit($figura->nombre, 25) }}</span>
                                    </div>
                                    <span class="stock-count {{ $figura->stock == 0 ? 'danger' : 'warning' }}">
                                        {{ $figura->stock }} uds
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-check-circle"></i>
                            <p>¡Todo el stock está bien!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Últimos Productos --}}
        <div class="col-12 col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5>
                        <i class="bi bi-clock-history"></i>
                        Últimos Productos
                    </h5>
                </div>
                <div class="dashboard-card-body">
                    @if($ultimosProductos->count() > 0)
                        <div class="products-list">
                            @foreach($ultimosProductos as $producto)
                                <div class="product-item">
                                    <div class="product-info">
                                        <span class="product-name">{{ Str::limit($producto->nombre, 28) }}</span>
                                        <span class="product-price">{{ number_format($producto->precio, 2) }}€</span>
                                    </div>
                                    <span class="product-stock {{ $producto->stock > 10 ? 'ok' : ($producto->stock > 0 ? 'low' : 'out') }}">
                                        @if($producto->stock > 0)
                                            {{ $producto->stock }}
                                        @else
                                            Agotado
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <p>No hay productos todavía</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</div>

<style>
/* Dashboard Styles - Elegante y Simple */
.dashboard-container {
    width: 100%;
}

/* Welcome Banner */
.welcome-banner {
    background: linear-gradient(135deg, #E4572E 0%, #ff7043 100%);
    border-radius: 16px;
    padding: 24px 32px;
    color: white;
}

.welcome-icon {
    width: 56px;
    height: 56px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 28px;
}

.welcome-title {
    font-size: 1.5rem;
    font-weight: 600;
}

.welcome-subtitle {
    opacity: 0.9;
    font-size: 0.95rem;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #eee;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-card-mangas .stat-icon { background: #e3f2fd; color: #1976d2; }
.stat-card-figuras .stat-icon { background: #e8f5e9; color: #388e3c; }
.stat-card-merch .stat-icon { background: #fff3e0; color: #f57c00; }
.stat-card-usuarios .stat-icon { background: #f3e5f5; color: #7b1fa2; }

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    margin-top: 4px;
}

/* Section Title */
.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-title i {
    color: #E4572E;
}

/* Quick Actions */
.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: white;
    border: 2px solid #eee;
    border-radius: 12px;
    padding: 20px 16px;
    text-decoration: none;
    color: #333;
    transition: all 0.2s;
    gap: 8px;
}

.quick-action-btn:hover {
    border-color: #E4572E;
    color: #E4572E;
    transform: translateY(-2px);
}

.quick-action-btn i {
    font-size: 24px;
}

.quick-action-btn span {
    font-size: 0.9rem;
    font-weight: 500;
}

/* Dashboard Cards */
.dashboard-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #eee;
    overflow: hidden;
}

.dashboard-card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #eee;
}

.dashboard-card-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.dashboard-card-body {
    padding: 16px 20px;
}

/* Stock List */
.stock-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.stock-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stock-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stock-type {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: white;
}

.stock-type.manga { background: #1976d2; }
.stock-type.figura { background: #388e3c; }

.stock-name {
    font-size: 0.9rem;
    color: #333;
}

.stock-count {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.stock-count.warning { background: #fff3e0; color: #e65100; }
.stock-count.danger { background: #ffebee; color: #c62828; }

/* Products List */
.products-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.product-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.product-info {
    display: flex;
    flex-direction: column;
}

.product-name {
    font-size: 0.9rem;
    color: #333;
    font-weight: 500;
}

.product-price {
    font-size: 0.8rem;
    color: #666;
}

.product-stock {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}

.product-stock.ok { background: #e8f5e9; color: #2e7d32; }
.product-stock.low { background: #fff3e0; color: #e65100; }
.product-stock.out { background: #ffebee; color: #c62828; }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 32px 20px;
    color: #888;
}

.empty-state i {
    font-size: 48px;
    color: #4caf50;
    margin-bottom: 12px;
}

.empty-state p {
    margin: 0;
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-banner {
        padding: 20px;
    }
    
    .welcome-icon {
        width: 48px;
        height: 48px;
        font-size: 24px;
    }
    
    .welcome-title {
        font-size: 1.2rem;
    }
    
    .stat-card {
        padding: 16px;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .quick-action-btn {
        padding: 16px 12px;
    }
}
</style>
@endsection
