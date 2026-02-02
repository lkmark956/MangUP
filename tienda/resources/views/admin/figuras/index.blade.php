@extends('admin.layouts.app')

@section('title', 'Gestión de Figuras')
@section('page-title', 'Figuras')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-trophy me-2"></i>Lista de Figuras</h5>
        </div>
        <a href="{{ route('admin.figuras.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nueva Figura
        </a>
    </div>
    <div class="card-body">
        <!-- Barra de búsqueda -->
        <form action="{{ route('admin.figuras.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="buscar" class="form-control" 
                       placeholder="Buscar por nombre..." 
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary">Buscar</button>
                @if(request('buscar'))
                    <a href="{{ route('admin.figuras.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>

        <!-- Tabla de figuras -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 70px;">Imagen</th>
                        <th>
                            <a href="{{ route('admin.figuras.index', ['orden' => 'nombre', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Nombre
                                @if(request('orden') == 'nombre')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Categoría</th>
                        <th class="text-center">
                            <a href="{{ route('admin.figuras.index', ['orden' => 'precio', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Precio
                                @if(request('orden') == 'precio')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a href="{{ route('admin.figuras.index', ['orden' => 'stock', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Stock
                                @if(request('orden') == 'stock')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($figuras as $figura)
                        <tr>
                            <td>
                                @if($figura->imagenes->first())
                                    <img src="{{ asset($figura->imagenes->first()->ruta) }}" 
                                         alt="{{ $figura->nombre }}" 
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
                                <strong>{{ $figura->nombre }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-purple" style="background-color: #9b59b6;">{{ $figura->categoria->nombre ?? 'Sin categoría' }}</span>
                            </td>
                            <td class="text-center fw-bold">{{ number_format($figura->precio, 2) }} €</td>
                            <td class="text-center">
                                @if($figura->stock > 10)
                                    <span class="badge bg-success">{{ $figura->stock }}</span>
                                @elseif($figura->stock > 0)
                                    <span class="badge bg-warning text-dark">{{ $figura->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Sin stock</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.figuras.edit', $figura) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.figuras.destroy', $figura) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta figura?')">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay figuras registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Mostrando {{ $figuras->firstItem() ?? 0 }} - {{ $figuras->lastItem() ?? 0 }} 
                de {{ $figuras->total() }} figuras
            </div>
            {{ $figuras->links() }}
        </div>
    </div>
</div>
@endsection
