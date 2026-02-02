@extends('admin.layouts.app')

@section('title', 'Gestión de Merch')
@section('page-title', 'Merchandising')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-bag me-2"></i>Lista de Merchandising</h5>
        </div>
        <a href="{{ route('admin.merch.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Merch
        </a>
    </div>
    <div class="card-body">
        <!-- Barra de búsqueda -->
        <form action="{{ route('admin.merch.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="buscar" class="form-control" 
                       placeholder="Buscar por nombre..." 
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary">Buscar</button>
                @if(request('buscar'))
                    <a href="{{ route('admin.merch.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>

        <!-- Tabla de merch -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 70px;">Imagen</th>
                        <th>
                            <a href="{{ route('admin.merch.index', ['orden' => 'nombre', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Nombre
                                @if(request('orden') == 'nombre')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Categoría</th>
                        <th class="text-center">Variantes</th>
                        <th class="text-center">
                            <a href="{{ route('admin.merch.index', ['orden' => 'precio', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Precio
                                @if(request('orden') == 'precio')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">Stock Total</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchs as $merch)
                        <tr>
                            <td>
                                @if($merch->imagenes->first())
                                    <img src="{{ asset($merch->imagenes->first()->ruta) }}" 
                                         alt="{{ $merch->nombre }}" 
                                         class="img-thumbnail" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="width: 60px; height: 60px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $merch->nombre }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $merch->categoria->nombre ?? 'Sin categoría' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $merch->variantes->count() }} variantes</span>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($merch->precio, 2) }} €</td>
                            <td class="text-center">
                                @php $stockTotal = $merch->variantes->sum('stock'); @endphp
                                @if($stockTotal > 10)
                                    <span class="badge bg-success">{{ $stockTotal }}</span>
                                @elseif($stockTotal > 0)
                                    <span class="badge bg-warning text-dark">{{ $stockTotal }}</span>
                                @else
                                    <span class="badge bg-danger">Sin stock</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.merch.edit', $merch) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.merch.destroy', $merch) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este merch y todas sus variantes?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay merchandising registrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Mostrando {{ $merchs->firstItem() ?? 0 }} - {{ $merchs->lastItem() ?? 0 }} 
                de {{ $merchs->total() }} productos
            </div>
            {{ $merchs->links() }}
        </div>
    </div>
</div>
@endsection
