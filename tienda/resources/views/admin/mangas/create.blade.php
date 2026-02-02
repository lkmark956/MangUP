@extends('admin.layouts.app')

@section('title', 'Crear Manga')
@section('page-title', 'Crear Nuevo Manga')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nuevo Manga</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.mangas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Columna izquierda: Datos principales -->
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="numero_tomo" class="form-label">Nº Tomo</label>
                            <input type="number" class="form-control @error('numero_tomo') is-invalid @enderror" 
                                   id="numero_tomo" name="numero_tomo" value="{{ old('numero_tomo') }}" min="0">
                            @error('numero_tomo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control @error('autor') is-invalid @enderror" 
                                   id="autor" name="autor" value="{{ old('autor') }}">
                            @error('autor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editorial" class="form-label">Editorial</label>
                            <input type="text" class="form-control @error('editorial') is-invalid @enderror" 
                                   id="editorial" name="editorial" value="{{ old('editorial') }}">
                            @error('editorial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                   id="isbn" name="isbn" value="{{ old('isbn') }}">
                            @error('isbn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="numero_paginas" class="form-label">Nº Páginas</label>
                            <input type="number" class="form-control @error('numero_paginas') is-invalid @enderror" 
                                   id="numero_paginas" name="numero_paginas" value="{{ old('numero_paginas') }}" min="1">
                            @error('numero_paginas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fecha_publicacion" class="form-label">Fecha Publicación</label>
                            <input type="date" class="form-control @error('fecha_publicacion') is-invalid @enderror" 
                                   id="fecha_publicacion" name="fecha_publicacion" value="{{ old('fecha_publicacion') }}">
                            @error('fecha_publicacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Imagen y datos de venta -->
                <div class="col-md-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-image me-2"></i>Imagen del Producto</h6>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                       id="imagen" name="imagen" accept="image/*"
                                       onchange="previewImage(this)">
                                @error('imagen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">JPG, PNG o WebP. Máx. 2MB</div>
                            </div>
                            <div id="preview-container" class="text-center" style="display: none;">
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-tag me-2"></i>Datos de Venta</h6>
                            
                            <div class="mb-3">
                                <label for="categoria_manga_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select @error('categoria_manga_id') is-invalid @enderror" 
                                        id="categoria_manga_id" name="categoria_manga_id" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_manga_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_manga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio (€) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" 
                                           id="precio" name="precio" value="{{ old('precio') }}" min="0" required>
                                    <span class="input-group-text">€</span>
                                </div>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between border-top pt-4">
                <a href="{{ route('admin.mangas.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Guardar Manga
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const container = document.getElementById('preview-container');
    const preview = document.getElementById('preview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}
</script>
@endsection
