{{-- Tarjeta de Producto Reutilizable --}}
<div class="col">
    <div class="card product-card h-100 shadow-sm">
        {{-- Badge de descuento o novedad --}}
        @if(isset($producto->precio_descuento) && $producto->precio_descuento < $producto->precio)
            <span class="badge-discount">
                -{{ round((1 - $producto->precio_descuento / $producto->precio) * 100) }}%
            </span>
        @elseif(isset($producto->created_at) && $producto->created_at->diffInDays(now()) < 7)
            <span class="badge-new">Nuevo</span>
        @endif

        {{-- Imagen del producto --}}
        <div class="card-img-wrapper">
            <a href="{{ route('productos.show', $producto->id ?? $producto->slug ?? 1) }}">
                <img src="{{ $producto->imagen_principal ?? $producto->imagen ?? asset('images/placeholder.jpg') }}" 
                    class="card-img-top" 
                    alt="{{ $producto->nombre }}"
                    loading="lazy">
            </a>
            
            {{-- Overlay con acciones rápidas --}}
            <div class="card-overlay">
                <div class="overlay-actions">
                    <button type="button" class="btn btn-light btn-sm rounded-circle" title="Vista rápida">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button type="button" class="btn btn-light btn-sm rounded-circle" title="Añadir a favoritos">
                        <i class="bi bi-heart"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Cuerpo de la tarjeta --}}
        <div class="card-body d-flex flex-column">
            {{-- Categoría --}}
            @if(isset($producto->categoria))
                <small class="text-muted text-uppercase mb-1">{{ $producto->categoria->nombre }}</small>
            @endif

            {{-- Nombre del producto --}}
            <h5 class="card-title mb-2">
                <a href="{{ route('productos.show', $producto->id ?? $producto->slug ?? 1) }}" 
                   class="text-decoration-none text-dark stretched-link-title">
                    {{ Str::limit($producto->nombre, 50) }}
                </a>
            </h5>

            {{-- Precio --}}
            <div class="product-price mt-auto mb-3">
                @if(isset($producto->precio_descuento) && $producto->precio_descuento < $producto->precio)
                    <span class="precio-original text-decoration-line-through text-muted small">
                        {{ number_format($producto->precio, 2) }}€
                    </span>
                    <span class="precio-actual fw-bold text-danger">
                        {{ number_format($producto->precio_descuento, 2) }}€
                    </span>
                @else
                    <span class="precio-actual fw-bold text-primary">
                        {{ number_format($producto->precio, 2) }}€
                    </span>
                @endif
            </div>

            {{-- Stock indicator --}}
            @if(isset($producto->stock))
                @if($producto->stock > 10)
                    <small class="text-success mb-2"><i class="bi bi-check-circle me-1"></i>En stock</small>
                @elseif($producto->stock > 0)
                    <small class="text-warning mb-2"><i class="bi bi-exclamation-circle me-1"></i>Últimas unidades</small>
                @else
                    <small class="text-danger mb-2"><i class="bi bi-x-circle me-1"></i>Agotado</small>
                @endif
            @endif

            {{-- Botón ver detalles --}}
            <a href="{{ route('productos.show', $producto->id ?? $producto->slug ?? 1) }}" 
               class="btn btn-outline-primary btn-sm w-100">
                <i class="bi bi-eye me-1"></i>Ver detalles
            </a>
        </div>
    </div>
</div>
