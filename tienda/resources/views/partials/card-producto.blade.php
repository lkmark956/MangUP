{{-- Tarjeta de Producto - Diseño Minimalista --}}
<article class="product-card">
    <a href="{{ route('productos.show', ['id' => $producto->id, 'tipo' => $producto->tipo ?? 'manga']) }}" class="product-card-link">
        {{-- Imagen --}}
        <div class="product-card-image">
            <img src="{{ $producto->imagen_principal ?? asset('images/placeholder.svg') }}" 
                alt="{{ $producto->nombre }}"
                loading="lazy">
            
            {{-- Badge tipo --}}
            <span class="product-type-badge {{ $producto->tipo ?? 'manga' }}">
                {{ ucfirst($producto->tipo ?? 'manga') }}
            </span>
            
            {{-- Badge de oferta --}}
            @if(isset($producto->oferta_info) && $producto->oferta_info)
                <span class="offer-tag">
                    @if($producto->oferta_info['oferta']->tipo_descuento === 'porcentaje')
                        -{{ $producto->oferta_info['oferta']->valor_descuento }}%
                    @else
                        -{{ number_format($producto->oferta_info['oferta']->valor_descuento, 2) }}€
                    @endif
                </span>
            @endif
            
            {{-- Stock badge --}}
            @if(isset($producto->stock) && $producto->stock <= 5 && $producto->stock > 0)
                <span class="stock-badge low">¡Últimas {{ $producto->stock }}!</span>
            @elseif(isset($producto->stock) && $producto->stock <= 0)
                <span class="stock-badge out">Agotado</span>
            @endif
        </div>
        
        {{-- Info --}}
        <div class="product-card-info">
            @if(isset($producto->categoria))
                <span class="product-category">{{ $producto->categoria->nombre }}</span>
            @endif
            
            <h3 class="product-name">{{ $producto->nombre }}</h3>
            
            <div class="product-price-row">
                @if(isset($producto->oferta_info) && $producto->oferta_info)
                    <div class="price-with-offer">
                        <span class="old-price">{{ number_format($producto->oferta_info['precio_original'], 2) }}€</span>
                        <span class="new-price">{{ number_format($producto->oferta_info['precio_final'], 2) }}€</span>
                    </div>
                @else
                    <span class="product-price">{{ number_format($producto->precio, 2) }}€</span>
                @endif
                
                @if(isset($producto->stock) && $producto->stock > 0)
                    <span class="product-stock available">
                        <i class="bi bi-circle-fill"></i> Disponible
                    </span>
                @endif
            </div>
        </div>
    </a>
</article>
