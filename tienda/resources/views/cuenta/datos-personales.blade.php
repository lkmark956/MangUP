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
            {{-- Formulario de Datos Personales --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Editar Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cuenta.actualizar-datos') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Foto de Perfil --}}
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-image me-2"></i>Foto de Perfil
                            </label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="profile-photo-preview" onclick="openPhotoModal()" style="cursor: pointer;">
                                    @if($user->foto_perfil)
                                        <img src="{{ asset($user->foto_perfil) }}" alt="Foto de perfil" id="preview-image">
                                    @else
                                        <div class="profile-placeholder" id="preview-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <img src="" alt="Vista previa" id="preview-image" style="display: none;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" 
                                           class="form-control @error('foto_perfil') is-invalid @enderror" 
                                           id="foto_perfil" 
                                           name="foto_perfil"
                                           accept="image/*"
                                           onchange="previewProfileImage(event)">
                                    @error('foto_perfil')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted d-block mt-1">
                                        Formatos: JPG, PNG, GIF. Tamaño máximo: 2MB
                                    </small>
                                </div>
                            </div>
                        </div>

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

            {{-- Modal para ver foto de perfil en grande --}}
            <div class="modal fade" id="photoModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" style="top: 10px; right: 10px; z-index: 1;"></button>
                        <div class="modal-body p-4 text-center">
                            <img id="photoModalImage" src="" alt="Foto de perfil" class="img-fluid rounded" style="max-width: 100%; max-height: 600px; object-fit: contain;">
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
    
    .profile-photo-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid #ddd;
        flex-shrink: 0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .profile-photo-preview:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .profile-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .profile-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #E76F00 0%, #FFB800 100%);
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
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
    
    // Vista previa de la foto de perfil
    function previewProfileImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImage = document.getElementById('preview-image');
                const placeholder = document.getElementById('preview-placeholder');
                
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }
            reader.readAsDataURL(file);
        }
    }
    
    // Abrir modal para ver foto en grande
    function openPhotoModal() {
        const previewImage = document.getElementById('preview-image');
        const imageSrc = previewImage.src;
        
        // Solo abre el modal si hay una imagen
        if (imageSrc && imageSrc !== '') {
            document.getElementById('photoModalImage').src = imageSrc;
            const modal = new bootstrap.Modal(document.getElementById('photoModal'));
            modal.show();
        }
    }
</script>
@endpush

@endsection
