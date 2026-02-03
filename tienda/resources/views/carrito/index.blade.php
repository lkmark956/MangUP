@extends('layouts.app')

@section('title', 'Carrito de Compras - MangUP')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Carrito</li>
        </ol>
    </nav>

    <h1 class="mb-4">
        <i class="bi bi-bag"></i> Carrito de Compras
    </h1>

    @if(isset($productos) && count($productos) > 0)
        <div class="row g-4">
            <!-- Lista de productos -->
            <div class="col-lg-8">
                <div class="cart-items">
                    @foreach($productos as $item)
                        <div class="cart-item" data-tipo="{{ $item['tipo'] }}" data-id="{{ $item['producto']->id }}">
                            <!-- Imagen del producto -->
                            <div class="cart-item-image">
                                <a href="{{ route('productos.show', ['id' => $item['producto']->id, 'tipo' => $item['tipo']]) }}">
                                    <img src="{{ $item['producto']->imagen_principal ?? asset('images/placeholder.svg') }}" 
                                         alt="{{ $item['producto']->nombre }}">
                                </a>
                            </div>

                            <!-- Información del producto (Centro) -->
                            <div class="cart-item-info">
                                <h5 class="cart-item-name">
                                    <a href="{{ route('productos.show', ['id' => $item['producto']->id, 'tipo' => $item['tipo']]) }}">
                                        {{ $item['producto']->nombre }}
                                    </a>
                                </h5>
                                <p class="cart-item-type">
                                    {{ ucfirst($item['tipo']) }}
                                    @if(isset($item['producto']->categoria))
                                        • {{ $item['producto']->categoria->nombre }}
                                    @endif
                                </p>
                                <p class="cart-item-stock">
                                    Stock disponible: <strong>{{ $item['producto']->stock }}</strong>
                                </p>
                            </div>

                            <!-- Precio unitario -->
                            <div class="cart-item-unit-price">
                                <span class="price-label">Precio</span>
                                <span class="price-value">{{ number_format($item['producto']->precio, 2) }}€</span>
                            </div>

                            <!-- Control de cantidad -->
                            <div class="cart-item-qty-section">
                                <span class="qty-label">Cantidad</span>
                                <form action="{{ route('carrito.actualizar', ['tipo' => $item['tipo'], 'id' => $item['producto']->id]) }}" 
                                      method="POST" class="qty-form">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <button type="button" class="qty-btn minus" 
                                                onclick="decrementarQty(this)">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                        <input type="number" name="cantidad" class="qty-input" 
                                               value="{{ $item['cantidad'] }}" min="1" max="{{ $item['producto']->stock }}"
                                               onchange="submitQtyForm(this)">
                                        <button type="button" class="qty-btn plus" 
                                                onclick="incrementarQty(this)">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Total del item -->
                            <div class="cart-item-subtotal">
                                <span class="subtotal-label">Subtotal</span>
                                <span class="subtotal-value">{{ number_format($item['producto']->precio * $item['cantidad'], 2) }}€</span>
                            </div>

                            <!-- Botón eliminar -->
                            <div class="cart-item-remove">
                                <form action="{{ route('carrito.eliminar', ['tipo' => $item['tipo'], 'id' => $item['producto']->id]) }}" 
                                      method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-remove" title="Eliminar del carrito">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botón para volver a comprar -->
                <div class="mt-4">
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Seguir comprando
                    </a>
                    <form action="{{ route('carrito.vaciar') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas vaciar el carrito?')">
                            <i class="bi bi-trash me-2"></i>Vaciar carrito
                        </button>
                    </form>
                </div>
            </div>

            <!-- Resumen de compra -->
            <div class="col-lg-4">
                <div class="cart-summary">
                    <h4 class="cart-summary-title">
                        <i class="bi bi-receipt"></i> Resumen
                    </h4>

                    <div class="summary-item">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">{{ number_format($subtotal, 2) }}€</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Impuesto (IVA 21%)</span>
                        <span class="summary-value">{{ number_format($impuesto, 2) }}€</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Envío</span>
                        <span class="summary-value shipping-cost">Gratis</span>
                    </div>

                    <div class="summary-item total">
                        <span class="summary-label">Total</span>
                        <span class="summary-value">{{ number_format($total, 2) }}€</span>
                    </div>

                    <button class="btn-checkout">
                        <i class="bi bi-credit-card me-2"></i>Proceder al pago
                    </button>

                    <div class="summary-info">
                        <i class="bi bi-shield-check"></i>
                        <span>Pago seguro y encriptado</span>
                    </div>

                    <!-- Resumen de artículos -->
                    <div class="summary-items mt-4 pt-4 border-top">
                        <p class="text-muted small mb-3">
                            <strong>{{ $cant_items }} artículo{{ $cant_items !== 1 ? 's' : '' }}</strong> en el carrito
                        </p>
                        
                        <div class="items-list">
                            @foreach($productos as $item)
                                <div class="item-summary">
                                    <span class="item-summary-name">{{ $item['producto']->nombre }}</span>
                                    <span class="item-summary-qty text-muted">x{{ $item['cantidad'] }}</span>
                                    <span class="item-summary-price">{{ number_format($item['producto']->precio * $item['cantidad'], 2) }}€</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Información de envío -->
                    <div class="shipping-info mt-4 pt-4 border-top">
                        <p class="text-muted small mb-2">
                            <i class="bi bi-truck"></i> <strong>Envío</strong>
                        </p>
                        <p class="text-muted small">
                            Envío gratuito en pedidos superiores a 30€. Envíos en 24/48 horas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Carrito vacío -->
        <div class="empty-cart text-center py-5">
            <i class="bi bi-bag-x display-1 text-muted"></i>
            <h3 class="mt-4 text-muted">Tu carrito está vacío</h3>
            <p class="text-muted mb-4">¡Comienza a agregar productos para tu colección!</p>
            <a href="{{ route('productos.index') }}" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Ir a tienda
            </a>
        </div>
    @endif
</div>

@if(isset($productos) && count($productos) > 0)
    <!-- Modal para confirmar cantidad -->
    <div class="modal fade" id="qtyModal" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cantidad no disponible</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>La cantidad que solicitaste no está disponible. El stock máximo es: <strong id="maxStock"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    function incrementarQty(btn) {
        const form = btn.closest('.qty-form');
        const input = form.querySelector('.qty-input');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        
        if (current < max) {
            input.value = current + 1;
            submitQtyForm(input);
        }
    }

    function decrementarQty(btn) {
        const form = btn.closest('.qty-form');
        const input = form.querySelector('.qty-input');
        const current = parseInt(input.value);
        
        if (current > 1) {
            input.value = current - 1;
            submitQtyForm(input);
        }
    }

    function submitQtyForm(input) {
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);
        
        if (current > max) {
            input.value = max;
            // Mostrar modal
            document.getElementById('maxStock').textContent = max;
            new bootstrap.Modal(document.getElementById('qtyModal')).show();
            return;
        }
        
        if (current < 1) {
            input.value = 1;
            return;
        }
        
        input.closest('.qty-form').submit();
    }
</script>
@endpush
