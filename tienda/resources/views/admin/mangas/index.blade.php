@extends('admin.layouts.app')

@section('title', 'Gestión de Mangas')
@section('page-title', 'Mangas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-book me-2"></i>Lista de Mangas</h5>
        </div>
        <a href="{{ route('admin.mangas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Manga
        </a>
    </div>
    <div class="card-body">
        <!-- Barra de búsqueda -->
        <form action="{{ route('admin.mangas.index') }}" method="GET" class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="buscar" class="form-control" 
                       placeholder="Buscar por nombre..." 
                       value="{{ request('buscar') }}">
                <button type="submit" class="btn btn-outline-primary">Buscar</button>
                @if(request('buscar'))
                    <a href="{{ route('admin.mangas.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>

        <!-- Tabla de mangas -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">Imagen</th>
                        <th>
                            <a href="{{ route('admin.mangas.index', ['orden' => 'nombre', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Nombre
                                @if(request('orden') == 'nombre')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th>Categoría</th>
                        <th>Autor</th>
                        <th class="text-center">
                            <a href="{{ route('admin.mangas.index', ['orden' => 'precio', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
                               class="text-white text-decoration-none">
                                Precio
                                @if(request('orden') == 'precio')
                                    <i class="bi bi-chevron-{{ request('dir') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-center">
                            <a href="{{ route('admin.mangas.index', ['orden' => 'stock', 'dir' => request('dir') == 'asc' ? 'desc' : 'asc', 'buscar' => request('buscar')]) }}" 
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
                    @forelse($mangas as $manga)
                        <tr>
                            <td>
                                @if($manga->imagenes->first())
                                    <img src="{{ asset($manga->imagenes->first()->ruta) }}" 
                                         alt="{{ $manga->nombre }}" 
                                         class="img-thumbnail" 
                                         style="width: 50px; height: 65px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 65px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $manga->nombre }}</strong>
                                @if($manga->numero_tomo)
                                    <span class="badge bg-secondary ms-1">Tomo {{ $manga->numero_tomo }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $manga->categoria->nombre ?? 'Sin categoría' }}</span>
                            </td>
                            <td>{{ $manga->autor ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ number_format($manga->precio, 2) }} €</td>
                            <td class="text-center">
                                @if($manga->stock > 10)
                                    <span class="badge bg-success">{{ $manga->stock }}</span>
                                @elseif($manga->stock > 0)
                                    <span class="badge bg-warning text-dark">{{ $manga->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Sin stock</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.mangas.edit', $manga) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.mangas.destroy', $manga) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este manga?')">
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
                                No hay mangas registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Mostrando {{ $mangas->firstItem() ?? 0 }} - {{ $mangas->lastItem() ?? 0 }} 
                de {{ $mangas->total() }} mangas
            </div>
            {{ $mangas->links() }}
        </div>
    </div>
</div>
@endsection
