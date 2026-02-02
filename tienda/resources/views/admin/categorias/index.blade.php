@extends('admin.layouts.app')

@section('title', 'Categorías de ' . ucfirst($tipo))
@section('page-title', 'Categorías de ' . ucfirst($tipo))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">
                @if($tipo == 'manga')
                    <i class="bi bi-book me-2"></i>
                @elseif($tipo == 'figura')
                    <i class="bi bi-trophy me-2"></i>
                @else
                    <i class="bi bi-bag me-2"></i>
                @endif
                Categorías de {{ ucfirst($tipo) }}
            </h5>
        </div>
        <div>
            <!-- Selector de tipo -->
            <div class="btn-group me-2">
                <a href="{{ route('admin.categorias.index', 'manga') }}" 
                   class="btn btn-sm {{ $tipo == 'manga' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Manga
                </a>
                <a href="{{ route('admin.categorias.index', 'figura') }}" 
                   class="btn btn-sm {{ $tipo == 'figura' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Figura
                </a>
                <a href="{{ route('admin.categorias.index', 'merch') }}" 
                   class="btn btn-sm {{ $tipo == 'merch' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Merch
                </a>
            </div>
            <a href="{{ route('admin.categorias.create', $tipo) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Nueva Categoría
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-center">Productos</th>
                        <th class="text-center" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $categoria->id }}</span>
                            </td>
                            <td>
                                <strong>{{ $categoria->nombre }}</strong>
                            </td>
                            <td class="text-muted">
                                {{ Str::limit($categoria->descripcion ?? 'Sin descripción', 60) }}
                            </td>
                            <td class="text-center">
                                @php
                                    $countAttr = $tipo == 'manga' ? 'mangas_count' : ($tipo == 'figura' ? 'figuras_count' : 'merchs_count');
                                @endphp
                                <span class="badge bg-info">{{ $categoria->$countAttr ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.categorias.edit', [$tipo, $categoria->id]) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categorias.destroy', [$tipo, $categoria->id]) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
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
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay categorías de {{ $tipo }} registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
