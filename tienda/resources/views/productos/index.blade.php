@extends('layouts.app')

@section('title', 'Productos - MangUP')

@section('content')
<div class="container py-5">
    <!-- Barra de búsqueda -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-container">
                <form action="{{ route('productos.index') }}" method="GET" class="d-flex gap-3">
                    <div class="input-group flex-grow-1">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                            name="buscar" 
                            class="form-control border-start-0 ps-0" 
                            placeholder="Buscar productos por nombre..."
                            value="{{ request('buscar') }}">
                    </div>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-2"></i>Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panel de Filtros -->
        <div class="col-lg-3 col-md-4 mb-4">
            @include('partials.filtros')
        </div>

        <!-- Grid de Productos -->
        <div class="col-lg-9 col-md-8">
            <!-- Encabezado de resultados -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Productos</h2>
                    <p class="text-muted mb-0">
                        @if(isset($productos) && $productos->count() > 0)
                            Mostrando {{ $productos->count() }} producto(s)
                        @else
                            No se encontraron productos
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <select name="ordenar" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Ordenar por</option>
                        <option value="precio_asc" {{ request('ordenar') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="precio_desc" {{ request('ordenar') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="nombre_asc" {{ request('ordenar') == 'nombre_asc' ? 'selected' : '' }}>Nombre: A-Z</option>
                        <option value="nombre_desc" {{ request('ordenar') == 'nombre_desc' ? 'selected' : '' }}>Nombre: Z-A</option>
                        <option value="recientes" {{ request('ordenar') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                    </select>
                </div>
            </div>

            <!-- Grid de productos -->
            @if(isset($productos) && $productos->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                    @foreach($productos as $producto)
                        @include('partials.card-producto', ['producto' => $producto])
                    @endforeach
                </div>

                <!-- Paginación -->
                @if($productos->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $productos->withQueryString()->links('vendor.pagination.custom') }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted"></i>
                    <h3 class="mt-4 text-muted">No hay productos disponibles</h3>
                    <p class="text-muted">Intenta cambiar los filtros de búsqueda</p>
                    <a href="{{ route('productos.index') }}" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Limpiar filtros
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cambiar ordenamiento automáticamente
    document.querySelector('select[name="ordenar"]')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('ordenar', this.value);
        window.location.href = url.toString();
    });
</script>
@endpush
