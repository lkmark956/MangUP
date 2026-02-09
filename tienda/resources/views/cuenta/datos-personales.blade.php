@extends('layouts.app')

@section('title', 'Datos Personales - MangUP')

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
                <a href="{{ route('cuenta.datos-personales') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person me-2"></i>Datos personales
                </a>
                <a href="{{ route('cuenta.pedidos') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-seam me-2"></i>Mis pedidos
                </a>
                <a href="{{ route('cuenta.direcciones') }}" class="list-group-item list-group-item-action">
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

            {{-- Formulario de Datos Personales --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Editar Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cuenta.actualizar-datos') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person me-2"></i>Nombre completo
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-2"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mb-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Guardar cambios
                            </button>
                            <a href="{{ route('cuenta.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Formulario de Cambio de Contraseña --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-key me-2"></i>Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Por seguridad, te pediremos que ingreses tu contraseña actual antes de establecer una nueva.
                    </p>

                    <form action="{{ route('cuenta.actualizar-password') }}" method="POST">
                        @csrf

                        {{-- Contraseña Actual --}}
                        <div class="mb-3">
                            <label for="password_actual" class="form-label">
                                <i class="bi bi-lock me-2"></i>Contraseña Actual
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_actual') is-invalid @enderror" 
                                       id="password_actual" 
                                       name="password_actual">
                                <button class="btn btn-outline-secondary toggle-password-actual" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_actual')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        {{-- Nueva Contraseña --}}
                        <div class="mb-3">
                            <label for="password_nueva" class="form-label">
                                <i class="bi bi-lock me-2"></i>Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_nueva') is-invalid @enderror" 
                                       id="password_nueva" 
                                       name="password_nueva">
                                <button class="btn btn-outline-secondary toggle-password-nueva" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i><strong>Requisitos de la contraseña:</strong>
                                <ul class="mb-0 ps-3">
                                    <li>Mínimo 8 caracteres</li>
                                    <li>Al menos una mayúscula</li>
                                    <li>Al menos 3 números</li>
                                    <li>Debe ser diferente a la contraseña actual</li>
                                </ul>
                            </small>
                            @error('password_nueva')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirmar Nueva Contraseña --}}
                        <div class="mb-4">
                            <label for="password_nueva_confirmation" class="form-label">
                                <i class="bi bi-lock me-2"></i>Confirmar Nueva Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control @error('password_nueva_confirmation') is-invalid @enderror" 
                                       id="password_nueva_confirmation" 
                                       name="password_nueva_confirmation">
                                <button class="btn btn-outline-secondary toggle-password-confirmation" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_nueva_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check me-2"></i>Cambiar Contraseña
                            </button>
                            <a href="{{ route('cuenta.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x me-2"></i>Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Información de Seguridad --}}
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle me-3 fs-5 text-info"></i>
                        <div>
                            <h6 class="fw-bold">Consejos de Seguridad</h6>
                            <ul class="small mb-0 ps-3">
                                <li>Usa una contraseña fuerte con letras mayúsculas, minúsculas, números y símbolos</li>
                                <li>No reutilices contraseñas de otras cuentas</li>
                                <li>Cambia tu contraseña regularmente</li>
                                <li>Si crees que alguien accedió a tu cuenta, cambia la contraseña inmediatamente</li>
                            </ul>
                        </div>
                    </div>
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

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password-actual, .toggle-password-nueva, .toggle-password-confirmation').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
@endpush

@endsection
