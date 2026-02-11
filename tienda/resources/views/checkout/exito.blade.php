@extends('layouts.app')

@section('title', '¡Compra Exitosa! - MangUP')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card success-card text-center">
                <div class="card-body p-5">
                    <div class="success-icon mb-4">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    
                    <h1 class="mb-3">¡Gracias por tu compra!</h1>
                    
                    <p class="lead text-muted mb-4">
                        Tu pedido ha sido procesado correctamente.
                    </p>

                    @if(isset($email) && $email)
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-envelope me-2"></i>
                            Hemos enviado la confirmación a: <strong>{{ $email }}</strong>
                        </div>
                    @endif

                    <div class="order-details bg-light rounded p-4 mb-4">
                        <h5 class="mb-3"><i class="bi bi-receipt me-2"></i>Detalles del pedido</h5>
                        @if(isset($pedido) && $pedido)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Número de pedido:</span>
                                <strong>{{ $pedido->numero_pedido }}</strong>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between mb-2">
                            <span>ID de transacción:</span>
                            <code>{{ substr($session->id, 0, 20) }}...</code>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Estado:</span>
                            <span class="badge bg-success">Pagado</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total pagado:</span>
                            <strong>{{ number_format($session->amount_total / 100, 2) }}€</strong>
                        </div>
                    </div>

                    <div class="d-grid gap-3">
                        @if(auth()->check() && isset($pedido) && $pedido)
                            <a href="{{ route('cuenta.pedidos.show', $pedido->id) }}" class="btn btn-success btn-lg">
                                <i class="bi bi-eye me-2"></i>Ver mi pedido
                            </a>
                        @endif
                        <a href="{{ route('productos.index') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-bag me-2"></i>Seguir comprando
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Volver al inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .success-card {
        border: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border-radius: 16px;
        overflow: hidden;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }
    
    .success-icon i {
        font-size: 50px;
        color: white;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .order-details {
        text-align: left;
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
