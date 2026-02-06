@extends('layouts.app')

@section('title', 'Mis Pedidos - MangUP')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-person me-2"></i>Mi Cuenta
            </h1>
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
            <h4 class="mb-4"><i class="bi bi-box-seam me-2"></i>Mis Pedidos</h4>

            @if($pedidos->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-box-seam display-1 text-muted mb-3"></i>
                        <h3 class="text-muted mb-3">Aún no tienes pedidos</h3>
                        <p class="text-muted mb-4">
                            Cuando realices tu primer pedido, aparecerá aquí con el estado y detalles de entrega.
                        </p>
                        <a href="{{ route('productos.index') }}" class="btn btn-primary">
                            <i class="bi bi-bag me-2"></i>Continuar comprando
                        </a>
                    </div>
                </div>
            @else
                {{-- Lista de Pedidos --}}
                @foreach($pedidos as $pedido)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $pedido->numero_pedido }}</strong>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                            <div class="text-end">
                                @switch($pedido->estado)
                                    @case('procesando')
                                        <span class="badge bg-info">Procesando</span>
                                        @break
                                    @case('enviado')
                                        <span class="badge bg-primary">Enviado</span>
                                        @break
                                    @case('entregado')
                                        <span class="badge bg-success">Entregado</span>
                                        @break
                                    @case('cancelado')
                                        <span class="badge bg-danger">Cancelado</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($pedido->estado) }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Productos del pedido --}}
                            <div class="row">
                                @foreach($pedido->items->take(3) as $item)
                                    <div class="col-auto mb-2">
                                        <div class="d-flex align-items-center">
                                            @if($item->producto && $item->producto->imagenes->first())
                                                <img src="{{ asset($item->producto->imagenes->first()->ruta) }}" 
                                                    alt="{{ $item->nombre_producto }}" 
                                                    class="rounded me-2"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                    style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <small class="d-block text-truncate" style="max-width: 150px;">{{ $item->nombre_producto }}</small>
                                                <small class="text-muted">x{{ $item->cantidad }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($pedido->items->count() > 3)
                                    <div class="col-auto mb-2 d-flex align-items-center">
                                        <span class="text-muted small">+{{ $pedido->items->count() - 3 }} más</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Total: {{ number_format($pedido->total, 2) }}€</strong>
                            </div>
                            <a href="{{ route('cuenta.pedidos.show', $pedido->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Ver detalles
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $pedidos->links() }}
                </div>
            @endif
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
