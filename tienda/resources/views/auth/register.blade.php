@extends('layouts.app')

@section('title', 'Crear Cuenta - MangUP')

@section('content')
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-lg-5 col-md-7 col-sm-10">
                
                {{-- Card de Registro --}}
                <div class="auth-card">
                    {{-- Header --}}
                    <div class="auth-header text-center">
                        <a href="{{ route('home') }}" class="auth-logo">
                            Mang<span>UP</span>
                        </a>
                        <h1 class="auth-title">Crear cuenta</h1>
                        <p class="auth-subtitle">Únete a la comunidad otaku más grande</p>
                    </div>

                    {{-- Alertas --}}
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

                    {{-- Formulario --}}
                    <form method="POST" action="{{ route('register') }}" class="auth-form">
                        @csrf
                        
                        {{-- Nombre --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person me-2"></i>Nombre completo
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Tu nombre"
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-2"></i>Email
                            </label>
                            <input type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="tu@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock me-2"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Mínimo 8 caracteres"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted d-block mt-2">
                                <i class="bi bi-info-circle me-1"></i><strong>Requisitos de la contraseña:</strong>
                                <ul class="mb-0 ps-3">
                                    <li>Mínimo 8 caracteres</li>
                                    <li>Al menos una mayúscula</li>
                                    <li>Al menos 3 números</li>
                                </ul>
                            </small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">
                                <i class="bi bi-lock-fill me-2"></i>Confirmar contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Repite tu contraseña"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                            <label class="form-check-label" for="terms">
                                Acepto los <a href="#" class="auth-link">Términos y Condiciones</a> 
                                y la <a href="#" class="auth-link">Política de Privacidad</a>
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="bi bi-person-plus me-2"></i>Crear cuenta
                        </button>

                        {{-- Divider --}}
                        <div class="auth-divider">
                            <span>o regístrate con</span>
                        </div>

                        {{-- Social Buttons --}}
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-google me-2"></i>Google
                            </button>
                        </div>
                    </form>

                    {{-- Footer --}}
                    <div class="auth-footer text-center">
                        <p class="mb-0">
                            ¿Ya tienes cuenta? 
                            <a href="{{ route('login') }}" class="auth-link fw-bold">Inicia sesión</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
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
