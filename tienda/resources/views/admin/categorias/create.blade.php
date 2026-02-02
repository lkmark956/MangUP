@extends('admin.layouts.app')

@section('title', 'Crear Categoría de ' . ucfirst($tipo))
@section('page-title', 'Nueva Categoría')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Nueva Categoría de {{ ucfirst($tipo) }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categorias.store', $tipo) }}" method="POST">
            @csrf
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" required
                               placeholder="Ej: Shonen, Figura de Acción, Camisetas...">
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="3" 
                                  placeholder="Descripción opcional de la categoría...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('admin.categorias.index', $tipo) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Guardar Categoría
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
