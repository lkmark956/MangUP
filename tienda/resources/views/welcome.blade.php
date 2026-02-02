@extends('layouts.app')

@section('title', 'MangUP - Tu tienda de manga y anime')

@section('content')
    {{-- Hero Cinematográfico --}}
    <section class="hero-cinematic">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-badge">マンガアップ</span>
            <h1 class="hero-title">Descubre el universo manga</h1>
            <p class="hero-subtitle">Colecciones exclusivas · Figuras premium · Merchandising oficial</p>
            <div class="hero-actions">
                <a href="{{ route('productos.index') }}" class="btn-hero-primary">
                    Explorar catálogo
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    {{-- Marquesina de series --}}
    <div class="marquee-container">
        <div class="marquee-content">
            <span>JUJUTSU KAISEN</span>
            <span>•</span>
            <span>MY HERO ACADEMIA</span>
            <span>•</span>
            <span>CHAINSAW MAN</span>
            <span>•</span>
            <span>ONE PIECE</span>
            <span>•</span>
            <span>DEMON SLAYER</span>
            <span>•</span>
            <span>NARUTO</span>
            <span>•</span>
            <span>DRAGON BALL</span>
            <span>•</span>
            <span>BLEACH</span>
            <span>•</span>
            <span>JUJUTSU KAISEN</span>
            <span>•</span>
            <span>MY HERO ACADEMIA</span>
            <span>•</span>
            <span>CHAINSAW MAN</span>
            <span>•</span>
            <span>ONE PIECE</span>
            <span>•</span>
        </div>
    </div>

    {{-- Categorías con diseño editorial --}}
    <section class="section-categories">
        <div class="container">
            <div class="section-header">
                <span class="section-number">01</span>
                <h2 class="section-title">Categorías</h2>
            </div>
            
            <div class="categories-grid">
                <a href="{{ route('productos.index', ['tipo' => 'manga']) }}" class="category-card category-manga">
                    <div class="category-icon">
                        <i class="bi bi-book"></i>
                    </div>
                    <div class="category-info">
                        <h3>Mangas</h3>
                        <p>Los mejores títulos en español</p>
                    </div>
                    <span class="category-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
                
                <a href="{{ route('productos.index', ['tipo' => 'figura']) }}" class="category-card category-figuras">
                    <div class="category-icon">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="category-info">
                        <h3>Figuras</h3>
                        <p>Coleccionables premium</p>
                    </div>
                    <span class="category-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
                
                <a href="{{ route('productos.index', ['tipo' => 'merch']) }}" class="category-card category-merch">
                    <div class="category-icon">
                        <i class="bi bi-bag"></i>
                    </div>
                    <div class="category-info">
                        <h3>Merch</h3>
                        <p>Camisetas, tazas y más</p>
                    </div>
                    <span class="category-arrow">
                        <i class="bi bi-arrow-right"></i>
                    </span>
                </a>
            </div>
        </div>
    </section>

    {{-- Productos destacados --}}
    <section class="section-featured">
        <div class="container">
            <div class="section-header">
                <span class="section-number">02</span>
                <h2 class="section-title">Destacados</h2>
                <a href="{{ route('productos.index') }}" class="section-link">
                    Ver todo <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="products-showcase">
                @foreach($productosDestacados as $producto)
                    @include('partials.card-producto', ['producto' => $producto])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Banner de valor --}}
    <section class="value-banner">
        <div class="container">
            <div class="value-grid">
                <div class="value-item">
                    <div class="value-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="value-text">
                        <h4>Envío gratuito</h4>
                        <p>En pedidos +50€</p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="value-text">
                        <h4>Pago seguro</h4>
                        <p>100% protegido</p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </div>
                    <div class="value-text">
                        <h4>Devolución fácil</h4>
                        <p>30 días garantía</p>
                    </div>
                </div>
                <div class="value-item">
                    <div class="value-icon">
                        <i class="bi bi-headset"></i>
                    </div>
                    <div class="value-text">
                        <h4>Soporte 24/7</h4>
                        <p>Siempre disponibles</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Últimos Mangas --}}
    <section class="section-latest">
        <div class="container">
            <div class="section-header">
                <span class="section-number">03</span>
                <h2 class="section-title">Últimos Mangas</h2>
            </div>
            
            <div class="products-showcase">
                @foreach($ultimosMangas as $manga)
                    @include('partials.card-producto', ['producto' => $manga])
                @endforeach
            </div>
        </div>
    </section>

    {{-- Figuras Destacadas --}}
    <section class="section-figures">
        <div class="container">
            <div class="section-header">
                <span class="section-number">04</span>
                <h2 class="section-title">Figuras Colección</h2>
            </div>
            
            <div class="products-showcase">
                @foreach($figurasDestacadas as $figura)
                    @include('partials.card-producto', ['producto' => $figura])
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Final --}}
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>¿Listo para tu próxima aventura?</h2>
                <p>Únete a miles de otakus que ya confían en MangUP</p>
                <a href="{{ route('productos.index') }}" class="btn-cta">
                    Explorar tienda
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>
@endsection
