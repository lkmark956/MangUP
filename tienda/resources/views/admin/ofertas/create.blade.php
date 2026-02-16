{{--
    Vista de creación de Oferta - Panel Admin
--}}
@extends('admin.layouts.app')

@section('title', 'Nueva Oferta')
@section('page-title', 'Nueva Oferta')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Volver a ofertas
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-tag me-2"></i>
            Crear Nueva Oferta
        </h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.ofertas.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-8">
                    {{-- Información básica --}}
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Información de la Oferta</h6>
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre de la Oferta <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required placeholder="Ej: Descuento de Verano">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="2" placeholder="Descripción opcional de la oferta">{{ old('descripcion') }}</textarea>
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
                                    <select name="tipo_descuento" class="form-select @error('tipo_descuento') is-invalid @enderror" required>
                                        <option value="porcentaje" {{ old('tipo_descuento') == 'porcentaje' ? 'selected' : '' }}>Porcentaje (%)</option>
                                        <option value="cantidad_fija" {{ old('tipo_descuento') == 'cantidad_fija' ? 'selected' : '' }}>Cantidad Fija (€)</option>
                                    </select>
                                    @error('tipo_descuento')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Valor del Descuento <span class="text-danger">*</span></label>
                                    <input type="number" name="valor_descuento" class="form-control @error('valor_descuento') is-invalid @enderror" 
                                           value="{{ old('valor_descuento') }}" required step="0.01" min="0.01" placeholder="Ej: 10">
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
                                <option value="todos" {{ old('aplica_a') == 'todos' ? 'selected' : '' }}>Todos los productos</option>
                                <option value="manga" {{ old('aplica_a') == 'manga' ? 'selected' : '' }}>Solo Mangas</option>
                                <option value="figura" {{ old('aplica_a') == 'figura' ? 'selected' : '' }}>Solo Figuras</option>
                                <option value="merch" {{ old('aplica_a') == 'merch' ? 'selected' : '' }}>Solo Merchandising</option>
                                <option value="producto_especifico" {{ old('aplica_a') == 'producto_especifico' ? 'selected' : '' }}>Producto específico</option>
                            </select>
                            @error('aplica_a')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        {{-- Selector de producto específico --}}
                        <div id="producto_especifico_container" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tipo de Producto</label>
                                        <select name="tipo_producto" id="tipo_producto" class="form-select">
                                            <option value="">Seleccionar tipo...</option>
                                            <option value="manga" {{ old('tipo_producto') == 'manga' ? 'selected' : '' }}>Manga</option>
                                            <option value="figura" {{ old('tipo_producto') == 'figura' ? 'selected' : '' }}>Figura</option>
                                            <option value="merch" {{ old('tipo_producto') == 'merch' ? 'selected' : '' }}>Merchandising</option>
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
                                       value="{{ old('fecha_inicio', date('Y-m-d')) }}" required>
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Fecha de Fin <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_fin" class="form-control @error('fecha_fin') is-invalid @enderror" 
                                       value="{{ old('fecha_fin') }}" required>
                                @error('fecha_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="activa" id="activa" value="1" 
                                       {{ old('activa', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="activa">Oferta activa</label>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Botones --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Crear Oferta
                        </button>
                        <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
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
    
    // Mostrar/ocultar selector de producto específico
    document.getElementById('aplica_a').addEventListener('change', function() {
        const container = document.getElementById('producto_especifico_container');
        container.style.display = this.value === 'producto_especifico' ? 'block' : 'none';
    });
    
    // Actualizar productos según tipo seleccionado
    document.getElementById('tipo_producto').addEventListener('change', function() {
        const productoSelect = document.getElementById('producto_id');
        productoSelect.innerHTML = '<option value="">Seleccionar producto...</option>';
        
        if (this.value && productos[this.value]) {
            productos[this.value].forEach(producto => {
                const option = document.createElement('option');
                option.value = producto.id;
                option.textContent = producto.nombre;
                productoSelect.appendChild(option);
            });
        }
    });
    
    // Mostrar contenedor si hay error de validación
    @if(old('aplica_a') === 'producto_especifico')
        document.getElementById('producto_especifico_container').style.display = 'block';
        document.getElementById('tipo_producto').dispatchEvent(new Event('change'));
    @endif
</script>
@endpush
@endsection
