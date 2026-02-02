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
            {{-- Pedidos Vacío --}}
            @if(empty($pedidos) || count($pedidos) === 0)
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
                <div class="row">
                    @foreach($pedidos as $pedido)
                        <div class="col-12 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1">Pedido #{{ $pedido->id }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-info">Pendiente</span>
                                            <p class="mb-0 mt-2">{{ number_format($pedido->total, 2) }}€</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Ver detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@endsection
