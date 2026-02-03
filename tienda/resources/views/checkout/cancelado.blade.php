@extends('layouts.app')

@section('title', 'Pago Cancelado - MangUP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card cancel-card text-center">
                <div class="card-body p-5">
                    <div class="cancel-icon mb-4">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    
                    <h1 class="mb-3">Pago Cancelado</h1>
                    
                    <p class="lead text-muted mb-4">
                        El proceso de pago ha sido cancelado. No se ha realizado ningún cargo.
                    </p>

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        Tu carrito sigue intacto. Puedes intentar realizar el pago de nuevo cuando quieras.
                    </div>

                    <div class="d-grid gap-3">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-repeat me-2"></i>Reintentar pago
                        </a>
                        <a href="{{ route('carrito.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-cart me-2"></i>Volver al carrito
                        </a>
                        <a href="{{ route('productos.index') }}" class="btn btn-link text-muted">
                            Seguir comprando
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ayuda -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5><i class="bi bi-question-circle me-2"></i>¿Necesitas ayuda?</h5>
                    <p class="text-muted mb-0">
                        Si tuviste algún problema durante el pago, no dudes en contactarnos:
                    </p>
                    <ul class="mt-2 mb-0">
                        <li>Email: <a href="mailto:soporte@mangup.com">soporte@mangup.com</a></li>
                        <li>Teléfono: +34 900 123 456</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .cancel-card {
        border: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .cancel-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #E4572E 0%, #C94A26 100%);
        border-radius: 50%;
    }
    
    .cancel-icon i {
        font-size: 50px;
        color: white;
    }
    
    .btn-primary {
        background-color: #E4572E;
        border-color: #E4572E;
    }
    
    .btn-primary:hover {
        background-color: #C94A26;
        border-color: #C94A26;
    }
</style>
@endpush
