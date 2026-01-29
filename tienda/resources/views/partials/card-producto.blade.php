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
                <span class="product-price">{{ number_format($producto->precio, 2) }}€</span>
                
                @if(isset($producto->stock) && $producto->stock > 0)
                    <span class="product-stock available">
                        <i class="bi bi-circle-fill"></i> Disponible
                    </span>
                @endif
            </div>
        </div>
    </a>
</article>
