@extends('layouts.app')

@section('title', 'Pedido {{ $pedido->numero_pedido }} - MangUP')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cuenta.pedidos') }}">Mis Pedidos</a></li>
                    <li class="breadcrumb-item active">{{ $pedido->numero_pedido }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="list-group sticky-top" style="top: 100px;">
                <a href="{{ route('cuenta.datos-personales') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Datos personales
                </a>
                <a href="{{ route('cuenta.pedidos') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-box-seam me-2"></i>Mis pedidos
                </a>
                <a href="{{ route('cuenta.direcciones') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-map me-2"></i>Direcciones
                </a>
            </div>
        </div>

        {{-- Contenido Principal --}}
        <div class="col-lg-9 col-md-8">
            {{-- Cabecera del pedido --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div>
                            <h4 class="mb-1">Pedido {{ $pedido->numero_pedido }}</h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="text-end">
                            @switch($pedido->estado)
                                @case('procesando')
                                    <span class="badge bg-info fs-6">Procesando</span>
                                    @break
                                @case('enviado')
                                    <span class="badge bg-primary fs-6">Enviado</span>
                                    @break
                                @case('entregado')
                                    <span class="badge bg-success fs-6">Entregado</span>
                                    @break
                                @case('cancelado')
                                    <span class="badge bg-danger fs-6">Cancelado</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($pedido->estado) }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            {{-- Productos del pedido --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Productos</h5>
                </div>
                <div class="card-body">
                    @foreach($pedido->items as $item)
                        <div class="d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            @if($item->producto && $item->producto->imagenes->first())
                                <img src="{{ asset($item->producto->imagenes->first()->ruta) }}" 
                                     alt="{{ $item->nombre_producto }}" 
                                    class="rounded me-3"
                                    style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                    style="width: 80px; height: 80px;">
                                    <i class="bi bi-image text-muted fs-4"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->nombre_producto }}</h6>
                                <small class="text-muted">
                                    Cantidad: {{ $item->cantidad }} x {{ number_format($item->precio_unitario, 2) }}€
                                </small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($item->subtotal, 2) }}€</strong>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                {{-- Dirección de envío --}}
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-truck me-2"></i>Dirección de envío</h6>
                        </div>
                        <div class="card-body">
                            @if($pedido->direccion_envio)
                                <p class="mb-0">{{ $pedido->direccion_envio }}</p>
                            @else
                                <p class="text-muted mb-0">No disponible</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Dirección de facturación --}}
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Dirección de facturación</h6>
                        </div>
                        <div class="card-body">
                            @if($pedido->direccion_facturacion)
                                <p class="mb-0">{{ $pedido->direccion_facturacion }}</p>
                            @else
                                <p class="text-muted mb-0">No disponible</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resumen del pedido --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ number_format($pedido->subtotal, 2) }}€</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (21%)</span>
                        <span>{{ number_format($pedido->impuesto, 2) }}€</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong class="fs-5">Total</strong>
                        <strong class="fs-5 text-primary">{{ number_format($pedido->total, 2) }}€</strong>
                    </div>
                </div>
            </div>

            {{-- Botón volver --}}
            <div class="mt-4">
                <a href="{{ route('cuenta.pedidos') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Volver a mis pedidos
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item.active {
        background-color: var(--primary-color, #E4572E);
        border-color: var(--primary-color, #E4572E);
    }
</style>
@endpush

@endsection
