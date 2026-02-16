{{--
    Vista de listado de Pedidos - Panel Admin
--}}
@extends('admin.layouts.app')

@section('title', 'Gestión de Pedidos')
@section('page-title', 'Gestión de Pedidos')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-receipt me-2"></i>
            Todos los Pedidos
        </h5>
    </div>
    
    <div class="card-body">
        {{-- Filtros --}}
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" class="form-control" 
                           placeholder="Buscar por nº pedido, email o cliente..." 
                           value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach($estados as $estado)
                        <option value="{{ $estado }}" {{ request('estado') == $estado ? 'selected' : '' }}>
                            {{ ucfirst($estado) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Filtrar
                </button>
            </div>
            @if(request()->hasAny(['buscar', 'estado']))
                <div class="col-md-2">
                    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Limpiar
                    </a>
                </div>
            @endif
        </form>
        
        {{-- Tabla de pedidos --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pedidos as $pedido)
                        <tr>
                            <td>
                                <strong>{{ $pedido->numero_pedido }}</strong>
                            </td>
                            <td>
                                <div>{{ $pedido->nombre_cliente ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $pedido->email_cliente }}</small>
                            </td>
                            <td>
                                <strong>{{ number_format($pedido->total, 2) }}€</strong>
                            </td>
                            <td>
                                @php
                                    $estadoClases = [
                                        'pendiente' => 'bg-warning text-dark',
                                        'procesando' => 'bg-info text-white',
                                        'enviado' => 'bg-primary',
                                        'entregado' => 'bg-success',
                                        'cancelado' => 'bg-danger'
                                    ];
                                @endphp
                                <span class="badge {{ $estadoClases[$pedido->estado] ?? 'bg-secondary' }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $pedido->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.pedidos.show', $pedido) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver detalles
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2">No se encontraron pedidos</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $pedidos->links() }}
        </div>
    </div>
</div>

{{-- Estadísticas rápidas --}}
<div class="row mt-4">
    @php
        $totalPedidos = \App\Models\Pedido::count();
        $pendientes = \App\Models\Pedido::where('estado', 'pendiente')->count();
        $procesando = \App\Models\Pedido::where('estado', 'procesando')->count();
        $entregados = \App\Models\Pedido::where('estado', 'entregado')->count();
    @endphp
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $totalPedidos }}</h3>
                <small class="text-muted">Total Pedidos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning bg-opacity-25">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $pendientes }}</h3>
                <small class="text-muted">Pendientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info bg-opacity-25">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $procesando }}</h3>
                <small class="text-muted">Procesando</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success bg-opacity-25">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $entregados }}</h3>
                <small class="text-muted">Entregados</small>
            </div>
        </div>
    </div>
</div>
@endsection
