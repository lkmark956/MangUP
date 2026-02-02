@extends('admin.layouts.app')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-person-gear me-2"></i>Editar: {{ $usuario->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.usuarios.update', $usuario) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $usuario->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title text-muted mb-3">
                                <i class="bi bi-lock me-1"></i>Cambiar Contraseña
                                <small class="text-muted">(dejar vacío para mantener la actual)</small>
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Nueva Contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($usuario->id !== auth()->id())
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_admin" name="is_admin" value="1"
                                       {{ old('is_admin', $usuario->is_admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">
                                    <strong>Administrador</strong>
                                    <small class="text-muted d-block">Tendrá acceso completo al panel de administración</small>
                                </label>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-1"></i>
                            No puedes cambiar tu propio estado de administrador.
                        </div>
                        <input type="hidden" name="is_admin" value="{{ $usuario->is_admin ? '1' : '0' }}">
                    @endif

                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Registrado: {{ $usuario->created_at->format('d/m/Y H:i') }}
                            @if($usuario->updated_at != $usuario->created_at)
                                | <i class="bi bi-arrow-repeat me-1"></i>
                                Última actualización: {{ $usuario->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>

                    <!-- Botones -->
                    <div class="d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Actualizar Usuario
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
