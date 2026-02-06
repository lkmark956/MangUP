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
                <div class="product-main-image" data-lightbox="{{ $producto->imagen_principal ?? asset('images/placeholder.svg') }}">
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
                            data-image="{{ asset($imagen->ruta) }}">
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

                {{-- Selector de variantes para Merch --}}
                @if($producto->tipo === 'merch' && isset($producto->variantes) && $producto->variantes->count() > 0)
                <div class="detail-variants">
                    <h3>Selecciona tu opción</h3>
                    
                    {{-- Selector de Talla --}}
                    @php
                        $tallas = $producto->variantes->pluck('talla')->unique()->filter();
                    @endphp
                    @if($tallas->count() > 0)
                    <div class="variant-group">
                        <label class="variant-label">Talla:</label>
                        <div class="variant-options" id="tallaOptions">
                            @foreach($tallas as $talla)
                            <button type="button" class="variant-btn" data-talla-id="{{ $talla->id }}" data-talla="{{ $talla->nombre }}">
                                {{ $talla->nombre }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    {{-- Selector de Color --}}
                    @php
                        $colores = $producto->variantes->pluck('color')->unique()->filter();
                    @endphp
                    @if($colores->count() > 0)
                    <div class="variant-group">
                        <label class="variant-label">Color:</label>
                        <div class="variant-options" id="colorOptions">
                            @foreach($colores as $color)
                            <button type="button" class="variant-btn" data-color-id="{{ $color->id }}" data-color="{{ $color->nombre }}">
                                <span class="color-swatch" style="background-color: {{ $color->hex ?? '#ccc' }}"></span>
                                {{ $color->nombre }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div id="variantError" class="variant-error" style="display: none;">
                        <i class="bi bi-exclamation-triangle"></i>
                        Por favor selecciona todas las opciones
                    </div>
                </div>
                @endif

                {{-- Acciones de compra --}}
                <div class="detail-actions">
                    <form action="{{ route('carrito.agregar') }}" method="POST" class="add-to-cart-form" id="addToCartForm">
                        @csrf
                        <input type="hidden" name="id" value="{{ $producto->id }}">
                        <input type="hidden" name="tipo" value="{{ $tipo ?? $producto->tipo ?? 'manga' }}">
                        <input type="hidden" name="variante_id" id="varianteIdInput" value="">
                        
                        <div class="quantity-control">
                            <button type="button" class="qty-btn minus" onclick="updateQty(-1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" id="productQty" name="cantidad" value="1" min="1" 
                                   max="{{ $producto->stock ?? 99 }}" readonly>
                            <button type="button" class="qty-btn plus" onclick="updateQty(1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        
                        <button type="submit" class="btn-add-cart" id="addToCartBtn" {{ (!isset($producto->stock) || $producto->stock <= 0) ? 'disabled' : '' }}>
                            <i class="bi bi-bag-plus"></i>
                            <span>Añadir al carrito</span>
                        </button>
                    </form>
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
// Control de cantidad
function updateQty(delta) {
    const input = document.getElementById('productQty');
    const max = parseInt(input.max) || 99;
    let val = parseInt(input.value) + delta;
    if (val >= 1 && val <= max) {
        input.value = val;
    }
}

// Actualizar carrito al agregar producto
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.add-to-cart-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Dejar que el formulario se envíe normalmente
            // sin preventDefault() ni fetch()
        });
    }

    // Manejar click en imagen principal para abrir lightbox
    const mainImage = document.querySelector('.product-main-image');
    if (mainImage) {
        mainImage.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-lightbox');
            openLightbox(imageSrc);
        });
    }

    // Manejar cambio de imagen en miniaturas
    const thumbnails = document.querySelectorAll('.thumbnail-btn');
    thumbnails.forEach(btn => {
        btn.addEventListener('click', function() {
            const imageSrc = this.getAttribute('data-image');
            const mainImg = document.getElementById('mainProductImage');
            if (mainImg) {
                mainImg.src = imageSrc;
            }
            
            // Actualizar clase active
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Actualizar data-lightbox del contenedor principal
            if (mainImage) {
                mainImage.setAttribute('data-lightbox', imageSrc);
            }
        });
    });

    // Manejar selección de variantes
    const variantes = @json($producto->variantes ?? []);
    let tallaSeleccionada = null;
    let colorSeleccionado = null;

    // Botones de talla
    const tallaBtns = document.querySelectorAll('#tallaOptions .variant-btn');
    tallaBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tallaBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            tallaSeleccionada = this.getAttribute('data-talla-id');
            actualizarVariante();
        });
    });

    // Botones de color
    const colorBtns = document.querySelectorAll('#colorOptions .variant-btn');
    colorBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            colorBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            colorSeleccionado = this.getAttribute('data-color-id');
            actualizarVariante();
        });
    });

    function actualizarVariante() {
        const variantError = document.getElementById('variantError');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const qtyInput = document.getElementById('productQty');
        const stockIndicator = document.querySelector('.detail-stock');
        
        // Verificar si necesitamos talla y color
        const necesitaTalla = tallaBtns.length > 0;
        const necesitaColor = colorBtns.length > 0;
        
        if ((necesitaTalla && !tallaSeleccionada) || (necesitaColor && !colorSeleccionado)) {
            if (variantError) variantError.style.display = 'block';
            if (addToCartBtn) addToCartBtn.disabled = true;
            return;
        }
        
        if (variantError) variantError.style.display = 'none';
        
        // Buscar la variante correspondiente
        const variante = variantes.find(v => {
            const matchTalla = !necesitaTalla || v.talla_id == tallaSeleccionada;
            const matchColor = !necesitaColor || v.color_id == colorSeleccionado;
            return matchTalla && matchColor;
        });
        
        if (variante) {
            // Actualizar campo oculto
            document.getElementById('varianteIdInput').value = variante.id;
            
            // Actualizar stock
            const stock = variante.stock || 0;
            if (qtyInput) {
                qtyInput.max = stock;
                if (parseInt(qtyInput.value) > stock) {
                    qtyInput.value = Math.max(1, stock);
                }
            }
            
            // Actualizar indicador de stock
            if (stockIndicator) {
                let stockHTML = '';
                if (stock > 10) {
                    stockHTML = `<span class="stock-indicator available">
                        <i class="bi bi-check-circle-fill"></i>
                        En stock (${stock} disponibles)
                    </span>`;
                } else if (stock > 0) {
                    stockHTML = `<span class="stock-indicator low">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        ¡Solo quedan ${stock} unidades!
                    </span>`;
                } else {
                    stockHTML = `<span class="stock-indicator out">
                        <i class="bi bi-x-circle-fill"></i>
                        Agotado temporalmente
                    </span>`;
                }
                stockIndicator.innerHTML = stockHTML;
            }
            
            // Habilitar/deshabilitar botón
            if (addToCartBtn) {
                addToCartBtn.disabled = stock <= 0;
            }
        } else {
            // No se encontró la variante
            if (addToCartBtn) addToCartBtn.disabled = true;
            if (stockIndicator) {
                stockIndicator.innerHTML = `<span class="stock-indicator out">
                    <i class="bi bi-x-circle-fill"></i>
                    Combinación no disponible
                </span>`;
            }
        }
    }

    // Validar formulario antes de enviar (solo para merch)
    const addToCartForm = document.getElementById('addToCartForm');
    if (addToCartForm && variantes.length > 0) {
        addToCartForm.addEventListener('submit', function(e) {
            const varianteId = document.getElementById('varianteIdInput').value;
            if (!varianteId) {
                e.preventDefault();
                const variantError = document.getElementById('variantError');
                if (variantError) {
                    variantError.style.display = 'block';
                    variantError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
});
</script>
@endpush
