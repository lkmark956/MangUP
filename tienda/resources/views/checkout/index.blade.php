@extends('layouts.app')

@section('title', 'Checkout - MangUP')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('carrito.index') }}">Carrito</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h1 class="mb-4">
        <i class="bi bi-credit-card"></i> Finalizar Compra
    </h1>

    <div class="row g-4">
        <!-- Resumen del pedido -->
        <div class="col-lg-7">
            <div class="card checkout-card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bag-check me-2"></i>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    @foreach($productos as $item)
                        <div class="checkout-item d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="checkout-item-image me-3">
                                <img src="{{ $item['producto']->imagen_principal ?? asset('images/placeholder.svg') }}" 
                                    alt="{{ $item['producto']->nombre }}"
                                    class="rounded"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                            <div class="checkout-item-info flex-grow-1">
                                <h6 class="mb-1">{{ $item['producto']->nombre }}</h6>
                                <small class="text-muted">
                                    {{ ucfirst($item['tipo']) }}
                                    @if(isset($item['producto']->categoria))
                                        • {{ $item['producto']->categoria->nombre }}
                                    @endif
                                </small>
                                @if(isset($item['variante']))
                                    <div class="mt-1">
                                        <small class="text-primary">
                                            @if($item['variante']->talla)
                                                <i class="bi bi-rulers"></i> {{ $item['variante']->talla->nombre }}
                                            @endif
                                            @if($item['variante']->talla && $item['variante']->color) • @endif
                                            @if($item['variante']->color)
                                                <i class="bi bi-palette-fill"></i> {{ $item['variante']->color->nombre }}
                                            @endif
                                        </small>
                                    </div>
                                @endif
                                <div class="mt-1">
                                    <span class="text-muted">Cantidad: {{ $item['cantidad'] }}</span>
                                </div>
                            </div>
                            <div class="checkout-item-price text-end">
                                <strong>{{ number_format($item['producto']->precio * $item['cantidad'], 2) }}€</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Información de envío -->
            <div class="card checkout-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Información de Envío</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        La dirección de envío se solicitará en el proceso de pago seguro de Stripe.
                    </div>
                </div>
            </div>

            <!-- Métodos de pago -->
            <div class="card checkout-card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Pago Seguro</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Aceptamos las siguientes tarjetas:</p>
                    <div class="payment-methods d-flex gap-2 align-items-center mb-3">
                        <img src="https://img.icons8.com/color/48/visa.png" alt="Visa" style="height: 32px;">
                        <img src="https://img.icons8.com/color/48/mastercard-logo.png" alt="Mastercard" style="height: 32px;">
                        <img src="https://img.icons8.com/color/48/amex.png" alt="American Express" style="height: 32px;">
                    </div>
                    
                    <!-- Información de tarjetas de prueba -->
                    <div class="alert alert-warning small mb-3">
                        <strong><i class="bi bi-credit-card me-1"></i>Modo de Prueba - Usa estas tarjetas:</strong>
                        <div class="mt-2">
                            <strong>Número:</strong> 4242 4242 4242 4242<br>
                            <strong>Fecha:</strong> Cualquier fecha futura (ej: 12/34)<br>
                            <strong>CVC:</strong> Cualquier 3 dígitos (ej: 123)<br>
                            <strong>Código postal:</strong> Cualquiera (ej: 12345)
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-0">
                        <i class="bi bi-lock me-1"></i>
                        Todos los pagos son procesados de forma segura a través de Stripe.
                    </p>
                </div>
            </div>
        </div>

        <!-- Total y botón de pago -->
        <div class="col-lg-5">
            <div class="card checkout-summary position-sticky" style="top: 20px;">
                <div class="card-header" style="background-color: #E4572E; color: white;">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Total a Pagar</h5>
                </div>
                <div class="card-body">
                    <div class="summary-row d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ number_format($subtotal, 2) }}€</span>
                    </div>
                    <div class="summary-row d-flex justify-content-between mb-2">
                        <span>IVA (21%)</span>
                        <span>{{ number_format($impuesto, 2) }}€</span>
                    </div>
                    <div class="summary-row d-flex justify-content-between mb-2">
                        <span>Envío</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="summary-total d-flex justify-content-between mb-4">
                        <strong class="fs-5">Total</strong>
                        <strong class="fs-4 text-primary">{{ number_format($total, 2) }}€</strong>
                    </div>

                    <button type="button" id="checkout-button" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-lock me-2"></i>Pagar con Stripe
                    </button>
                    
                    <div id="checkout-loading" class="text-center mt-3 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2 text-muted">Redirigiendo al pago seguro...</p>
                    </div>

                    <div id="checkout-error" class="alert alert-danger mt-3 d-none"></div>

                    <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                        <i class="bi bi-arrow-left me-2"></i>Volver al carrito
                    </a>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Compra 100% segura y protegida
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    @php
        $stripeKey = config('stripe.key');
        if (empty($stripeKey)) {
            \Log::error('STRIPE_KEY no está configurada en el archivo .env');
        }
    @endphp
    
    const stripeKey = '{{ config("stripe.key") }}';
    
    if (!stripeKey || stripeKey === '') {
        console.error('ERROR: STRIPE_KEY no está configurada');
        document.getElementById('checkout-error').classList.remove('d-none');
        document.getElementById('checkout-error').textContent = 'Error de configuración: La clave pública de Stripe (STRIPE_KEY) no está configurada. Por favor, contacta al administrador.';
        document.getElementById('checkout-button').disabled = true;
    }
    
    const stripe = Stripe(stripeKey);
    const checkoutButton = document.getElementById('checkout-button');
    const loadingDiv = document.getElementById('checkout-loading');
    const errorDiv = document.getElementById('checkout-error');

    checkoutButton.addEventListener('click', async () => {
        checkoutButton.disabled = true;
        loadingDiv.classList.remove('d-none');
        errorDiv.classList.add('d-none');

        try {
            const response = await fetch('{{ route("checkout.crear-sesion") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Error al procesar el pago');
            }

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            // Redirigir a Stripe Checkout
            if (data.url) {
                window.location.href = data.url;
            } else {
                throw new Error('No se recibió URL de pago');
            }
            
        } catch (error) {
            console.error('Error:', error);
            checkoutButton.disabled = false;
            loadingDiv.classList.add('d-none');
            errorDiv.classList.remove('d-none');
            errorDiv.textContent = error.message || 'Error al procesar el pago. Inténtalo de nuevo.';
        }
    });
</script>
@endpush

@push('styles')
<style>
    .checkout-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .checkout-card .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        padding: 1rem 1.25rem;
    }
    
    .checkout-summary {
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border-radius: 12px;
        overflow: hidden;
    }
    
    .checkout-summary .card-header {
        padding: 1.25rem;
    }
    
    .checkout-summary .card-body {
        padding: 1.5rem;
    }
    
    .summary-total {
        padding-top: 1rem;
    }
    
    .text-primary {
        color: #E4572E !important;
    }
    
    .btn-primary {
        background-color: #E4572E;
        border-color: #E4572E;
    }
    
    .btn-primary:hover {
        background-color: #C94A26;
        border-color: #C94A26;
    }
    
    .payment-methods img {
        filter: grayscale(0);
        transition: all 0.3s ease;
    }
    
    .payment-methods img:hover {
        transform: scale(1.1);
    }
</style>
@endpush
