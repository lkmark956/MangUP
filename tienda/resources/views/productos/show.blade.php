@extends('layouts.app')

@section('title', isset($producto) ? $producto->nombre . ' - MangUP' : 'Producto - MangUP')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ isset($producto) ? $producto->nombre : 'Detalle' }}
            </li>
        </ol>
    </nav>

    @if(isset($producto))
    <div class="row">
        <!-- Galería de imágenes -->
        <div class="col-lg-6 mb-4">
            <div class="product-gallery">
                <!-- Imagen principal -->
                <div class="main-image-container mb-3">
                    <img src="{{ $producto->imagen_principal ?? asset('images/placeholder.jpg') }}" 
                        alt="{{ $producto->nombre }}" 
                        class="img-fluid rounded shadow main-image"
                        id="mainImage">
                </div>
                
                <!-- Miniaturas de galería -->
                @if(isset($producto->imagenes) && $producto->imagenes->count() > 0)
                <div class="gallery-thumbnails d-flex gap-2 flex-wrap">
                    @foreach($producto->imagenes as $index => $imagen)
                    <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}" 
                        onclick="cambiarImagen('{{ $imagen->url }}', this)">
                        <img src="{{ $imagen->url }}" 
                            alt="{{ $producto->nombre }} - Imagen {{ $index + 1 }}" 
                            class="img-thumbnail">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Información del producto -->
        <div class="col-lg-6">
            <div class="product-info">
                <!-- Categoría -->
                @if(isset($producto->categoria))
                <span class="badge bg-secondary mb-2">{{ $producto->categoria->nombre }}</span>
                @endif

                <!-- Nombre -->
                <h1 class="product-title mb-3">{{ $producto->nombre }}</h1>

                <!-- Precio -->
                <div class="product-price mb-4">
                    @if(isset($producto->precio_descuento) && $producto->precio_descuento < $producto->precio)
                        <span class="precio-original text-decoration-line-through text-muted me-2">
                            {{ number_format($producto->precio, 2) }}€
                        </span>
                        <span class="precio-actual h2 text-danger fw-bold">
                            {{ number_format($producto->precio_descuento, 2) }}€
                        </span>
                        <span class="badge bg-danger ms-2">
                            -{{ round((1 - $producto->precio_descuento / $producto->precio) * 100) }}%
                        </span>
                    @else
                        <span class="precio-actual h2 text-primary fw-bold">
                            {{ number_format($producto->precio, 2) }}€
                        </span>
                    @endif
                </div>

                <!-- Stock y disponibilidad -->
                <div class="stock-info mb-4">
                    @if($producto->stock > 10)
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle me-1"></i>En stock
                        </span>
                        <small class="text-muted ms-2">({{ $producto->stock }} unidades disponibles)</small>
                    @elseif($producto->stock > 0)
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="bi bi-exclamation-triangle me-1"></i>Últimas unidades
                        </span>
                        <small class="text-muted ms-2">(Solo quedan {{ $producto->stock }})</small>
                    @else
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-x-circle me-1"></i>Agotado
                        </span>
                    @endif
                </div>

                <!-- Descripción -->
                <div class="product-description mb-4">
                    <h5>Descripción</h5>
                    <p class="text-muted">
                        {{ $producto->descripcion ?? 'Sin descripción disponible.' }}
                    </p>
                </div>

                <!-- Características adicionales -->
                @if(isset($producto->caracteristicas) && count($producto->caracteristicas) > 0)
                <div class="product-features mb-4">
                    <h5>Características</h5>
                    <ul class="list-unstyled">
                        @foreach($producto->caracteristicas as $key => $valor)
                        <li class="mb-2">
                            <i class="bi bi-check2 text-success me-2"></i>
                            <strong>{{ $key }}:</strong> {{ $valor }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Selector de cantidad y botón añadir -->
                <div class="add-to-cart-section">
                    <form action="#" method="POST" class="d-flex gap-3 align-items-end">
                        @csrf
                        <div class="quantity-selector">
                            <label for="cantidad" class="form-label fw-semibold">Cantidad</label>
                            <div class="input-group" style="width: 130px;">
                                <button type="button" class="btn btn-outline-secondary" id="btnDecrementar">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" 
                                    id="cantidad" 
                                    name="cantidad" 
                                    class="form-control text-center" 
                                    value="1" 
                                    min="1" 
                                    data-max-stock="{{ $producto->stock }}"
                                    @if($producto->stock <= 0) disabled @endif>
                                <button type="button" class="btn btn-outline-secondary" id="btnIncrementar" data-max-stock="{{ $producto->stock }}">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" 
                                class="btn btn-primary btn-lg flex-grow-1"
                                @if($producto->stock <= 0) disabled @endif>
                            <i class="bi bi-cart-plus me-2"></i>
                            @if($producto->stock > 0) Añadir al carrito @else No disponible @endif
                        </button>
                    </form>

                    <!-- Botón wishlist -->
                    <button class="btn btn-outline-danger w-100 mt-3">
                        <i class="bi bi-heart me-2"></i>Añadir a favoritos
                    </button>
                </div>

                <!-- Información adicional -->
                <div class="additional-info mt-4 pt-4 border-top">
                    <div class="row text-center">
                        <div class="col-4">
                            <i class="bi bi-truck fs-4 text-primary"></i>
                            <p class="small mb-0 mt-1">Envío gratis +50€</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-shield-check fs-4 text-primary"></i>
                            <p class="small mb-0 mt-1">Pago seguro</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-arrow-return-left fs-4 text-primary"></i>
                            <p class="small mb-0 mt-1">Devolución 30 días</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    @if(isset($productosRelacionados) && $productosRelacionados->count() > 0)
    <section class="related-products mt-5 pt-5 border-top">
        <h3 class="mb-4">Productos relacionados</h3>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($productosRelacionados as $relacionado)
                @include('partials.card-producto', ['producto' => $relacionado])
            @endforeach
        </div>
    </section>
    @endif

    @else
    <div class="text-center py-5">
        <i class="bi bi-exclamation-circle display-1 text-warning"></i>
        <h3 class="mt-4">Producto no encontrado</h3>
        <p class="text-muted">El producto que buscas no existe o ha sido eliminado.</p>
        <a href="{{ route('productos.index') }}" class="btn btn-primary mt-3">
            <i class="bi bi-arrow-left me-2"></i>Volver a productos
        </a>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cambiar imagen principal
        window.cambiarImagen = function(url, element) {
            document.getElementById('mainImage').src = url;
            document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        };

        // Botón incrementar
        const btnIncrementar = document.getElementById('btnIncrementar');
        if (btnIncrementar) {
            btnIncrementar.addEventListener('click', function() {
                const input = document.getElementById('cantidad');
                const max = parseInt(this.dataset.maxStock) || 999;
                const currentValue = parseInt(input.value) || 0;
                if (currentValue < max) {
                    input.value = currentValue + 1;
                }
            });
        }

        // Botón decrementar
        const btnDecrementar = document.getElementById('btnDecrementar');
        if (btnDecrementar) {
            btnDecrementar.addEventListener('click', function() {
                const input = document.getElementById('cantidad');
                const currentValue = parseInt(input.value) || 0;
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            });
        }
    });
</script>
@endpush
