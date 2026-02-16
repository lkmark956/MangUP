{{--
    Vista de detalle de Pedido - Panel Admin
--}}
@extends('admin.layouts.app')

@section('title', 'Pedido ' . $pedido->numero_pedido)
@section('page-title', 'Detalle del Pedido')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver a pedidos
    </a>
</div>

<div class="row">
    {{-- Información Principal --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Pedido #{{ $pedido->numero_pedido }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Información del Cliente</h6>
                        <p class="mb-1"><strong>Nombre:</strong> {{ $pedido->nombre_cliente ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $pedido->email_cliente }}</p>
                        @if($pedido->user)
                            <p class="mb-1"><strong>Usuario:</strong> {{ $pedido->user->name }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Información del Pedido</h6>
                        <p class="mb-1"><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-1"><strong>Stripe Session:</strong> 
                            <small class="text-muted">{{ Str::limit($pedido->stripe_session_id, 30) }}</small>
                        </p>
                    </div>
                </div>
                
                @if($pedido->direccion_envio)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Dirección de Envío</h6>
                        <p class="mb-0">{{ $pedido->direccion_envio }}</p>
                    </div>
                @endif
                
                {{-- Productos del pedido --}}
                <h6 class="text-muted mb-3">Productos</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedido->items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->nombre_producto }}</strong>
                                        <br>
                                        <small class="text-muted">{{ ucfirst($item->tipo_producto) }}</small>
                                        @if($item->variante_info)
                                            <br><small class="text-info">{{ $item->variante_info }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->cantidad }}</td>
                                    <td class="text-end">{{ number_format($item->precio_unitario, 2) }}€</td>
                                    <td class="text-end">{{ number_format($item->precio_unitario * $item->cantidad, 2) }}€</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">{{ number_format($pedido->subtotal, 2) }}€</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>IVA (21%):</strong></td>
                                <td class="text-end">{{ number_format($pedido->impuesto, 2) }}€</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong class="text-primary">Total:</strong></td>
                                <td class="text-end"><strong class="text-primary">{{ number_format($pedido->total, 2) }}€</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Panel de Estado --}}
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Estado del Pedido
                </h5>
            </div>
            <div class="card-body">
                @php
                    $estadoClases = [
                        'pendiente' => 'bg-warning text-dark',
                        'procesando' => 'bg-info text-white',
                        'enviado' => 'bg-primary',
                        'entregado' => 'bg-success',
                        'cancelado' => 'bg-danger'
                    ];
                @endphp
                
                <div class="text-center mb-4">
                    <span class="badge {{ $estadoClases[$pedido->estado] ?? 'bg-secondary' }} fs-5 px-4 py-2">
                        {{ ucfirst($pedido->estado) }}
                    </span>
                </div>
                
                <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label">Cambiar estado a:</label>
                        <select name="estado" class="form-select" required>
                            @foreach($estados as $estado)
                                <option value="{{ $estado }}" {{ $pedido->estado == $estado ? 'selected' : '' }}>
                                    {{ ucfirst($estado) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Actualizar Estado
                    </button>
                </form>
                
                <hr>
                
                <div class="small text-muted">
                    <p class="mb-1"><i class="bi bi-info-circle me-1"></i> <strong>Estados:</strong></p>
                    <ul class="mb-0 ps-3">
                        <li><strong>Pendiente:</strong> Pago recibido, pendiente de procesar</li>
                        <li><strong>Procesando:</strong> Preparando el envío</li>
                        <li><strong>Enviado:</strong> En camino al cliente</li>
                        <li><strong>Entregado:</strong> Recibido por el cliente</li>
                        <li><strong>Cancelado:</strong> Pedido cancelado</li>
                    </ul>
                </div>
            </div>
        </div>
        
        {{-- Acciones rápidas --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($pedido->estado === 'procesando')
                        <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="enviado">
                            <button type="submit" class="btn btn-outline-primary w-100">
                                <i class="bi bi-truck me-1"></i> Marcar como Enviado
                            </button>
                        </form>
                    @endif
                    
                    @if($pedido->estado === 'enviado')
                        <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="entregado">
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Marcar como Entregado
                            </button>
                        </form>
                    @endif
                    
                    @if(!in_array($pedido->estado, ['cancelado', 'entregado']))
                        <form action="{{ route('admin.pedidos.updateEstado', $pedido) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de cancelar este pedido? El stock será restaurado.')">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="cancelado">
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Cancelar Pedido
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
