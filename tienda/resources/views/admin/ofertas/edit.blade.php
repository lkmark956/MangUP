{{--
    Vista de edición de Oferta - Panel Admin
--}}
@extends('admin.layouts.app')

@section('title', 'Editar Oferta')
@section('page-title', 'Editar Oferta')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver a ofertas
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-pencil me-2"></i>
            Editar Oferta: {{ $oferta->nombre }}
        </h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.ofertas.update', $oferta) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    {{-- Información básica --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Información de la Oferta</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Oferta <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre', $oferta->nombre) }}" required placeholder="Ej: Descuento de Verano">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="2" placeholder="Descripción opcional de la oferta">{{ old('descripcion', $oferta->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- Configuración del descuento --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Configuración del Descuento</h6>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Descuento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="Porcentaje (%)" disabled>
                                    <input type="hidden" name="tipo_descuento" value="porcentaje">
                                    <small class="text-muted">Solo se permiten descuentos por porcentaje</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Porcentaje de Descuento (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="valor_descuento" class="form-control @error('valor_descuento') is-invalid @enderror" 
                                           value="{{ old('valor_descuento', $oferta->valor_descuento) }}" required step="0.01" min="0.01" max="85" placeholder="Ej: 10">
                                    <small class="text-muted">Máximo: 85%</small>
                                    @error('valor_descuento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Aplicación de la oferta --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Aplicar Oferta a</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Tipo de Aplicación <span class="text-danger">*</span></label>
                            <select name="aplica_a" id="aplica_a" class="form-select @error('aplica_a') is-invalid @enderror" required>
                                <option value="todos" {{ old('aplica_a', $oferta->aplica_a) == 'todos' ? 'selected' : '' }}>Todos los productos</option>
                                <option value="manga" {{ old('aplica_a', $oferta->aplica_a) == 'manga' ? 'selected' : '' }}>Solo Mangas</option>
                                <option value="figura" {{ old('aplica_a', $oferta->aplica_a) == 'figura' ? 'selected' : '' }}>Solo Figuras</option>
                                <option value="merch" {{ old('aplica_a', $oferta->aplica_a) == 'merch' ? 'selected' : '' }}>Solo Merchandising</option>
                                <option value="producto_especifico" {{ old('aplica_a', $oferta->aplica_a) == 'producto_especifico' ? 'selected' : '' }}>Producto específico</option>
                            </select>
                            @error('aplica_a')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Selector de producto específico --}}
                        <div id="producto_especifico_container" style="{{ $oferta->aplica_a === 'producto_especifico' ? '' : 'display: none;' }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Producto</label>
                                        <select name="tipo_producto" id="tipo_producto" class="form-select">
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="manga" {{ old('tipo_producto', $oferta->tipo_producto) == 'manga' ? 'selected' : '' }}>Manga</option>
                                            <option value="figura" {{ old('tipo_producto', $oferta->tipo_producto) == 'figura' ? 'selected' : '' }}>Figura</option>
                                            <option value="merch" {{ old('tipo_producto', $oferta->tipo_producto) == 'merch' ? 'selected' : '' }}>Merchandising</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Producto</label>
                                        <select name="producto_id" id="producto_id" class="form-select">
                                            <option value="">Seleccionar producto...</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    {{-- Vigencia --}}
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Vigencia de la Oferta</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                       value="{{ old('fecha_inicio', $oferta->fecha_inicio->format('Y-m-d')) }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       value="{{ old('fecha_fin', $oferta->fecha_fin->format('Y-m-d')) }}" required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activa" id="activa" value="1" 
                                       {{ old('activa', $oferta->activa) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activa">Oferta activa</label>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Botones --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                        </button>
                        <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>
                    
                    {{-- Eliminar --}}
                    <div class="mt-4">
                        <form action="{{ route('admin.ofertas.destroy', $oferta) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar esta oferta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash me-1"></i> Eliminar Oferta
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Datos de productos para el selector dinámico
    const productos = {
        manga: @json($mangas),
        figura: @json($figuras),
        merch: @json($merchs)
    };
    
    const productoIdActual = {{ $oferta->producto_id ?? 'null' }};
    const tipoProductoActual = '{{ $oferta->tipo_producto ?? '' }}';
    
    // Mostrar/ocultar selector de producto específico
    document.getElementById('aplica_a').addEventListener('change', function() {
        const container = document.getElementById('producto_especifico_container');
        container.style.display = this.value === 'producto_especifico' ? 'block' : 'none';
    });
    
    // Actualizar productos según tipo seleccionado
    function actualizarProductos() {
        const tipoSelect = document.getElementById('tipo_producto');
        const productoSelect = document.getElementById('producto_id');
        const tipo = tipoSelect.value;
        
        productoSelect.innerHTML = '<option value="">Seleccionar producto...</option>';
        
        if (tipo && productos[tipo]) {
            productos[tipo].forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.nombre;
                if (tipo === tipoProductoActual && producto.id === productoIdActual) {
                    option.selected = true;
                }
                productoSelect.appendChild(option);
            });
        }
    }
    
    document.getElementById('tipo_producto').addEventListener('change', actualizarProductos);
    
    // Cargar productos al inicio si hay un tipo seleccionado
    if (tipoProductoActual) {
        actualizarProductos();
    }
</script>
@endpush
@endsection
