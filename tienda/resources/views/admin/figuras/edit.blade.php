@extends('admin.layouts.app')

@section('title', 'Editar Figura')
@section('page-title', 'Editar Figura')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar: {{ $figura->nombre }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.figuras.update', $figura) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Columna izquierda -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $figura->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="5" required>{{ old('descripcion', $figura->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-md-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-image me-2"></i>Imagen</h6>
                            
                            @if($figura->imagenes->first())
                                <div class="text-center mb-3">
                                    <img src="{{ asset($figura->imagenes->first()->ruta) }}" 
                                         alt="{{ $figura->nombre }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 120px;"
                                         id="current-image">
                                    <p class="text-muted small mt-2">Imagen actual</p>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="imagen" class="form-label">{{ $figura->imagenes->first() ? 'Cambiar imagen' : 'Subir imagen' }}</label>
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                       id="imagen" name="imagen" accept="image/*"
                                       onchange="previewImage(this)">
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">JPG, PNG o WebP. Máx. 2MB</div>
                            </div>
                            <div id="preview-container" class="text-center" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 120px;">
                                <p class="text-success small mt-2">Nueva imagen</p>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-tag me-2"></i>Datos de Venta</h6>
                            
                            <div class="mb-3">
                                <label for="categoria_figura_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select @error('categoria_figura_id') is-invalid @enderror" 
                                        id="categoria_figura_id" name="categoria_figura_id" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" 
                                                {{ old('categoria_figura_id', $figura->categoria_figura_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_figura_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                           id="precio" name="precio" value="{{ old('precio', $figura->precio) }}" min="0" required>
                                    <span class="input-group-text">€</span>
                                </div>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', $figura->stock) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Creado: {{ $figura->created_at->format('d/m/Y H:i') }}<br>
                        <i class="bi bi-arrow-repeat me-1"></i>
                        Actualizado: {{ $figura->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between border-top pt-4 mt-4">
                <a href="{{ route('admin.figuras.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Actualizar Figura
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const container = document.getElementById('preview-container');
    const preview = document.getElementById('preview');
    const currentImage = document.getElementById('current-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
            if (currentImage) currentImage.style.opacity = '0.5';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
        if (currentImage) currentImage.style.opacity = '1';
    }
}
</script>
@endsection
