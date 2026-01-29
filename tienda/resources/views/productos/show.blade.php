@extends('layouts.app')

@section('title', isset($producto) ? $producto->nombre . ' - MangUP' : 'Producto - MangUP')

@section('content')
@if(isset($producto) && $producto)
<div class="product-detail-page">
    {{-- Navegación breadcrumb minimalista --}}
    <nav class="detail-breadcrumb">
        <div class="container">
            <a href="{{ route('home') }}">Inicio</a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('productos.index') }}">Productos</a>
            <i class="bi bi-chevron-right"></i>
            <span>{{ Str::limit($producto->nombre, 30) }}</span>
        </div>
    </nav>

    {{-- Contenido principal --}}
    <div class="container">
        <div class="product-detail-grid">
            {{-- Columna izquierda: Imagen --}}
            <div class="product-image-section">
                <div class="product-main-image" onclick="openLightbox('{{ $producto->imagen_principal ?? asset('images/placeholder.svg') }}')">
                    <img src="{{ $producto->imagen_principal ?? asset('images/placeholder.svg') }}" 
                         alt="{{ $producto->nombre }}"
                         id="mainProductImage">
                    
                    {{-- Icono de zoom --}}
                    <div class="image-zoom-hint">
                        <i class="bi bi-zoom-in"></i>
                    </div>
                    
                    {{-- Badge de tipo --}}
                    <span class="detail-type-badge {{ $producto->tipo }}">
                        {{ ucfirst($producto->tipo) }}
                    </span>
                </div>
                
                {{-- Miniaturas si hay varias --}}
                @if(isset($producto->imagenes) && $producto->imagenes->count() > 1)
                <div class="product-thumbnails">
                    @foreach($producto->imagenes as $index => $imagen)
                    <button class="thumbnail-btn {{ $index === 0 ? 'active' : '' }}" 
                            onclick="changeImage('{{ asset($imagen->ruta) }}', this)">
                        <img src="{{ asset($imagen->ruta) }}" alt="Vista {{ $index + 1 }}">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Columna derecha: Info --}}
            <div class="product-info-section">
                {{-- Categoría --}}
                @if(isset($producto->categoria))
                <span class="detail-category">{{ $producto->categoria->nombre }}</span>
                @endif

                {{-- Título --}}
                <h1 class="detail-title">{{ $producto->nombre }}</h1>

                {{-- Precio --}}
                <div class="detail-price">
                    <span class="price-value">{{ number_format($producto->precio, 2) }}€</span>
                    <span class="price-tax">IVA incluido</span>
                </div>

                {{-- Stock --}}
                <div class="detail-stock">
                    @if(isset($producto->stock) && $producto->stock > 10)
                        <span class="stock-indicator available">
                            <i class="bi bi-check-circle-fill"></i>
                            En stock ({{ $producto->stock }} disponibles)
                        </span>
                    @elseif(isset($producto->stock) && $producto->stock > 0)
                        <span class="stock-indicator low">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            ¡Solo quedan {{ $producto->stock }} unidades!
                        </span>
                    @else
                        <span class="stock-indicator out">
                            <i class="bi bi-x-circle-fill"></i>
                            Agotado temporalmente
                        </span>
                    @endif
                </div>

                {{-- Descripción corta --}}
                <div class="detail-description">
                    <p>{{ $producto->descripcion ?? 'Sin descripción disponible.' }}</p>
                </div>

                {{-- Acciones de compra --}}
                <div class="detail-actions">
                    <div class="quantity-control">
                        <button type="button" class="qty-btn minus" onclick="updateQty(-1)">
                            <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" id="productQty" value="1" min="1" 
                               max="{{ $producto->stock ?? 99 }}" readonly>
                        <button type="button" class="qty-btn plus" onclick="updateQty(1)">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                    
                    <button class="btn-add-cart" {{ (!isset($producto->stock) || $producto->stock <= 0) ? 'disabled' : '' }}>
                        <i class="bi bi-bag-plus"></i>
                        <span>Añadir al carrito</span>
                    </button>
                </div>

                {{-- Características según tipo --}}
                <div class="detail-specs">
                    <h3>Características</h3>
                    <dl class="specs-list">
                        @if($producto->tipo === 'manga')
                            @if(isset($producto->autor) && $producto->autor)
                            <div class="spec-item">
                                <dt><i class="bi bi-person"></i> Autor</dt>
                                <dd>{{ $producto->autor }}</dd>
                            </div>
                            @endif
                            @if(isset($producto->editorial) && $producto->editorial)
                            <div class="spec-item">
                                <dt><i class="bi bi-building"></i> Editorial</dt>
                                <dd>{{ $producto->editorial }}</dd>
                            </div>
                            @endif
                            @if(isset($producto->numero_tomo))
                            <div class="spec-item">
                                <dt><i class="bi bi-hash"></i> Tomo</dt>
                                <dd>{{ $producto->numero_tomo }}</dd>
                            </div>
                            @endif
                            @if(isset($producto->numero_paginas) && $producto->numero_paginas)
                            <div class="spec-item">
                                <dt><i class="bi bi-file-text"></i> Páginas</dt>
                                <dd>{{ $producto->numero_paginas }}</dd>
                            </div>
                            @endif
                            @if(isset($producto->isbn) && $producto->isbn)
                            <div class="spec-item">
                                <dt><i class="bi bi-upc"></i> ISBN</dt>
                                <dd>{{ $producto->isbn }}</dd>
                            </div>
                            @endif
                        @elseif($producto->tipo === 'figura')
                            <div class="spec-item">
                                <dt><i class="bi bi-box"></i> Tipo</dt>
                                <dd>Figura coleccionable</dd>
                            </div>
                            <div class="spec-item">
                                <dt><i class="bi bi-palette"></i> Material</dt>
                                <dd>PVC de alta calidad</dd>
                            </div>
                        @elseif($producto->tipo === 'merch')
                            <div class="spec-item">
                                <dt><i class="bi bi-bag"></i> Tipo</dt>
                                <dd>Merchandising oficial</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Garantías --}}
                <div class="detail-guarantees">
                    <div class="guarantee-item">
                        <i class="bi bi-truck"></i>
                        <span>Envío gratis +50€</span>
                    </div>
                    <div class="guarantee-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Pago 100% seguro</span>
                    </div>
                    <div class="guarantee-item">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>30 días devolución</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Productos relacionados --}}
        @if(isset($productosRelacionados) && $productosRelacionados->count() > 0)
        <section class="related-section">
            <h2 class="related-title">También te puede interesar</h2>
            <div class="products-showcase">
                @foreach($productosRelacionados as $relacionado)
                    @php $relacionado->tipo = $producto->tipo; @endphp
                    @include('partials.card-producto', ['producto' => $relacionado])
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>

@else
{{-- Producto no encontrado --}}
<div class="not-found-page">
    <div class="container">
        <div class="not-found-content">
            <i class="bi bi-emoji-frown"></i>
            <h1>Producto no encontrado</h1>
            <p>El producto que buscas no existe o ha sido eliminado.</p>
            <a href="{{ route('productos.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Volver a productos
            </a>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
// Cambiar imagen principal
function changeImage(src, btn) {
    document.getElementById('mainProductImage').src = src;
    document.querySelectorAll('.thumbnail-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Actualizar el onclick del contenedor para que abra el lightbox con la nueva imagen
    document.querySelector('.product-main-image').onclick = function() {
        openLightbox(src);
    };
}

// Control de cantidad
function updateQty(delta) {
    const input = document.getElementById('productQty');
    const max = parseInt(input.max) || 99;
    let val = parseInt(input.value) + delta;
    if (val >= 1 && val <= max) {
        input.value = val;
    }
}
</script>
@endpush
