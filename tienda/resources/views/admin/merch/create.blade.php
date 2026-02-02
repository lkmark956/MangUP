@extends('admin.layouts.app')

@section('title', 'Crear Merch')
@section('page-title', 'Crear Nuevo Merchandising')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nuevo Merchandising</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.merch.store') }}" method="POST" enctype="multipart/form-data" id="merchForm">
            @csrf
            
            <div class="row">
                <!-- Columna izquierda -->
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sección de Variantes -->
                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-list-ul me-2"></i>Variantes (Talla/Color)</span>
                            <button type="button" class="btn btn-sm btn-light" onclick="agregarVariante()">
                                <i class="bi bi-plus"></i> Añadir Variante
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="variantes-container">
                                <!-- Variante inicial -->
                                <div class="variante-row row mb-2 align-items-end" data-index="0">
                                    <div class="col-md-4">
                                        <label class="form-label">Talla</label>
                                        <select name="variantes[0][talla_id]" class="form-select" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($tallas as $talla)
                                                <option value="{{ $talla->id }}">{{ $talla->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Color</label>
                                        <select name="variantes[0][color_id]" class="form-select" required>
                                            <option value="">Seleccionar...</option>
                                            @foreach($colores as $color)
                                                <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="variantes[0][stock]" class="form-control" 
                                               value="0" min="0" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarVariante(this)" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted">Añade al menos una variante con talla, color y stock.</small>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha -->
                <div class="col-md-4">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-image me-2"></i>Imagen</h6>
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
                                <img id="preview" src="" alt="Preview" class="img-fluid rounded" style="max-height: 180px;">
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title mb-3"><i class="bi bi-tag me-2"></i>Datos de Venta</h6>
                            
                            <div class="mb-3">
                                <label for="categoria_merch_id" class="form-label">Categoría <span class="text-danger">*</span></label>
                                <select class="form-select @error('categoria_merch_id') is-invalid @enderror" 
                                        id="categoria_merch_id" name="categoria_merch_id" required>
                                    <option value="">Seleccionar...</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_merch_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_merch_id')
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
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-between border-top pt-4 mt-4">
                <a href="{{ route('admin.merch.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Guardar Merch
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let varianteIndex = 1;

function agregarVariante() {
    const container = document.getElementById('variantes-container');
    const html = `
        <div class="variante-row row mb-2 align-items-end" data-index="${varianteIndex}">
            <div class="col-md-4">
                <select name="variantes[${varianteIndex}][talla_id]" class="form-select" required>
                    <option value="">Seleccionar talla...</option>
                    @foreach($tallas as $talla)
                        <option value="{{ $talla->id }}">{{ $talla->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="variantes[${varianteIndex}][color_id]" class="form-select" required>
                    <option value="">Seleccionar color...</option>
                    @foreach($colores as $color)
                        <option value="{{ $color->id }}">{{ $color->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="variantes[${varianteIndex}][stock]" class="form-control" 
                       value="0" min="0" required placeholder="Stock">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarVariante(this)" title="Eliminar">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    varianteIndex++;
}

function eliminarVariante(btn) {
    const rows = document.querySelectorAll('.variante-row');
    if (rows.length > 1) {
        btn.closest('.variante-row').remove();
    } else {
        alert('Debe haber al menos una variante');
    }
}

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
