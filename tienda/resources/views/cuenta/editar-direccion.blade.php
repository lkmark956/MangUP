@extends('layouts.app')

@section('title', 'Editar Dirección - MangUP')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-person me-2"></i>Mi Cuenta
            </h1>
        </div>
    </div>

    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="list-group sticky-top" style="top: 100px;">
                <a href="{{ route('cuenta.datos-personales') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i>Datos personales
                </a>
                <a href="{{ route('cuenta.pedidos') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-seam me-2"></i>Mis pedidos
                </a>
                <a href="{{ route('cuenta.direcciones') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-map me-2"></i>Direcciones
                </a>
            </div>
        </div>

        {{-- Contenido Principal --}}
        <div class="col-lg-9 col-md-8">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-pencil-square me-2"></i>Editar dirección
                        </h5>
                        <a href="{{ route('cuenta.direcciones') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('cuenta.actualizar-direccion', $direccion->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nombre de la Dirección --}}
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la dirección (ej: Mi casa)</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $direccion->nombre) }}" placeholder="Mi casa" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Calle --}}
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="calle" class="form-label">Calle</label>
                                <input type="text" class="form-control @error('calle') is-invalid @enderror" id="calle" name="calle" value="{{ old('calle', $direccion->calle) }}" required>
                                @error('calle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $direccion->numero) }}" required>
                                @error('numero')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Piso y Puerta --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="piso" class="form-label">Piso (opcional)</label>
                                <input type="text" class="form-control" id="piso" name="piso" value="{{ old('piso', $direccion->piso) }}" placeholder="1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="puerta" class="form-label">Puerta (opcional)</label>
                                <input type="text" class="form-control" id="puerta" name="puerta" value="{{ old('puerta', $direccion->puerta) }}" placeholder="A">
                            </div>
                        </div>

                        {{-- Localidad --}}
                        <div class="mb-3">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control @error('ciudad') is-invalid @enderror" id="ciudad" name="ciudad" value="{{ old('ciudad', $direccion->ciudad) }}" required>
                            @error('ciudad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Provincia y CP --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="provincia" class="form-label">Provincia</label>
                                <input type="text" class="form-control @error('provincia') is-invalid @enderror" id="provincia" name="provincia" value="{{ old('provincia', $direccion->provincia) }}" required>
                                @error('provincia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="codigo_postal" class="form-label">Código Postal</label>
                                <input type="text" class="form-control @error('codigo_postal') is-invalid @enderror" id="codigo_postal" name="codigo_postal" value="{{ old('codigo_postal', $direccion->codigo_postal) }}" required>
                                @error('codigo_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- País --}}
                        <div class="mb-3">
                            <label for="pais" class="form-label">País</label>
                            <input type="text" class="form-control @error('pais') is-invalid @enderror" id="pais" name="pais" value="{{ old('pais', $direccion->pais) }}" required>
                            @error('pais')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Dirección Predeterminada --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="es_default" name="es_default" @if($direccion->es_default) checked @endif>
                            <label class="form-check-label" for="es_default">
                                Establecer como dirección predeterminada
                            </label>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Guardar cambios
                            </button>
                            <a href="{{ route('cuenta.direcciones') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
</style>
@endpush

@endsection
