@extends('layouts.app')

@section('title', 'MangUP - Tu tienda de manga y anime')

@section('content')
    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h1>Bienvenido a MangUP</h1>
            <p class="lead mb-4">Tu tienda online de manga, figuras y merchandising anime</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-light btn-lg">
                    <i class="bi bi-book me-2"></i>Ver Mangas
                </a>
                <a href="#" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-stars me-2"></i>Ver Figuras
                </a>
            </div>
        </div>
    </section>

    <!-- Categorías destacadas -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Explora nuestras categorías</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-product h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-book display-1 text-primary mb-3"></i>
                        <h4>Mangas</h4>
                        <p class="text-muted">Descubre los mejores títulos de manga en español</p>
                        <a href="#" class="btn btn-outline-primary">Ver mangas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-product h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-stars display-1 text-primary mb-3"></i>
                        <h4>Figuras</h4>
                        <p class="text-muted">Colecciona las mejores figuras de tus series favoritas</p>
                        <a href="#" class="btn btn-outline-primary">Ver figuras</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-product h-100 text-center p-4">
                    <div class="card-body">
                        <i class="bi bi-bag display-1 text-primary mb-3"></i>
                        <h4>Merch</h4>
                        <p class="text-muted">Camisetas, tazas, posters y mucho más</p>
                        <a href="#" class="btn btn-outline-primary">Ver merch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Productos destacados (placeholder) -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Productos destacados</h2>
        <div class="row g-4">
            @for ($i = 1; $i <= 4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="card card-product h-100">
                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="bi bi-image display-1 text-muted"></i>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Producto de ejemplo {{ $i }}</h5>
                        <p class="text-muted small">Categoría</p>
                        <p class="price">€XX.XX</p>
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-cart-plus me-2"></i>Añadir al carrito
                        </button>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="text-center mt-4">
            <a href="#" class="btn btn-outline-primary btn-lg">Ver todos los productos</a>
        </div>
    </section>

    <!-- Banner promocional -->
    <section class="container mb-5">
        <div class="card border-0 text-white" style="background: linear-gradient(135deg, #E76F00 0%, #FFB800 100%);">
            <div class="card-body text-center py-5">
                <h3><i class="bi bi-truck me-2"></i>Envío gratis en pedidos superiores a 50€</h3>
                <p class="mb-0">Entrega en 24-48 horas en toda España</p>
            </div>
        </div>
    </section>

    <!-- Géneros de Manga -->
    <section class="container mb-5">
        <h2 class="text-center mb-4">Géneros de Manga</h2>
        <div class="row g-3 justify-content-center">
            @php
                $generos = ['Acción', 'Romance', 'Terror', 'Fantasía', 'Comedia', 'Aventura', 'Drama', 'Ciencia Ficción', 'Misterio', 'Deportes'];
            @endphp
            @foreach ($generos as $genero)
            <div class="col-auto">
                <a href="#" class="btn btn-outline-secondary">{{ $genero }}</a>
            </div>
            @endforeach
        </div>
    </section>
@endsection
