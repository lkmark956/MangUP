@extends('admin.layouts.app')

@section('title', 'Editar Categoría')
@section('page-title', 'Editar Categoría')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar: {{ $categoria->nombre }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.categorias.update', [$tipo, $categoria->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Creada: {{ $categoria->created_at->format('d/m/Y H:i') }}
                            @if($categoria->updated_at != $categoria->created_at)
                                | <i class="bi bi-arrow-repeat me-1"></i>
                                Actualizada: {{ $categoria->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('admin.categorias.index', $tipo) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Actualizar Categoría
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
