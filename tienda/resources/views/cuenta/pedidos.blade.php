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
            @if($pedidos->count() === 0)
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
                        <div class="col-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                {{-- Cabecera del pedido --}}
                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h5 class="mb-1">Pedido #{{ $pedido->numero_pedido ?? str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</h5>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>{{ $pedido->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @php
                                            $estadoClases = [
                                                'pendiente' => 'bg-warning text-dark',
                                                'procesando' => 'bg-info',
                                                'enviado' => 'bg-primary',
                                                'entregado' => 'bg-success',
                                                'cancelado' => 'bg-danger',
                                            ];
                                            $estadoTextos = [
                                                'pendiente' => 'Pendiente',
                                                'procesando' => 'Procesando',
                                                'enviado' => 'Enviado',
                                                'entregado' => 'Entregado',
                                                'cancelado' => 'Cancelado',
                                            ];
                                        @endphp
                                        <span class="badge {{ $estadoClases[$pedido->estado] ?? 'bg-secondary' }}">
                                            {{ $estadoTextos[$pedido->estado] ?? ucfirst($pedido->estado) }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Productos del pedido --}}
                                <div class="card-body">
                                    <h6 class="mb-3">Productos:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-end">Precio</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pedido->items as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($item->producto && $item->producto->imagenes && $item->producto->imagenes->first())
                                                                    <img src="{{ asset($item->producto->imagenes->first()->ruta) }}" 
                                                                         alt="{{ $item->nombre_producto }}"
                                                                         class="me-2"
                                                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;"
                                                                         onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                                                @else
                                                                    <div class="me-2 bg-light d-flex align-items-center justify-content-center" 
                                                                         style="width: 40px; height: 40px; border-radius: 4px;">
                                                                        <i class="bi bi-box text-muted"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <div class="fw-medium">{{ $item->nombre_producto }}</div>
                                                                    @if($item->producto)
                                                                        <small class="text-muted">
                                                                            {{ ucfirst(class_basename($item->producto_type)) }}
                                                                        </small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <span class="badge bg-light text-dark">{{ $item->cantidad }}</span>
                                                        </td>
                                                        <td class="text-end align-middle">{{ number_format($item->precio_unitario, 2) }}€</td>
                                                        <td class="text-end align-middle fw-bold">{{ number_format($item->subtotal, 2) }}€</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                    <td class="text-end">{{ number_format($pedido->subtotal, 2) }}€</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>IVA (21%):</strong></td>
                                                    <td class="text-end">{{ number_format($pedido->impuesto, 2) }}€</td>
                                                </tr>
                                                <tr class="table-primary">
                                                    <td colspan="3" class="text-end"><strong>TOTAL:</strong></td>
                                                    <td class="text-end"><strong>{{ number_format($pedido->total, 2) }}€</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    {{-- Información de envío --}}
                                    @if($pedido->direccion_envio)
                                        <div class="mt-3">
                                            <h6 class="mb-2">Dirección de envío:</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $pedido->direccion_envio }}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Páginación si hay muchos pedidos --}}
                @if($pedidos->count() > 0)
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <p class="text-muted mb-0">Total de pedidos: {{ $pedidos->count() }}</p>
                    </div>
                @endif
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
