@extends('layouts.app')

@section('title', 'Direcciones - MangUP')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h3 mb-0">
                <i class="bi bi-person me-2"></i>Mi Cuenta
            </h1>
        </div>
    </div>

    <div class="row" id="mainContent">
        {{-- Sidebar --}}
        <div class="col-lg-3 col-md-4 mb-4" id="sidebarCuenta">
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

            {{-- Botón Agregar Dirección --}}
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaDireccion">
                    <i class="bi bi-plus-circle me-2"></i>Agregar nueva dirección
                </button>
            </div>

            {{-- Lista de Direcciones --}}
            @if(empty($direcciones) || count($direcciones) === 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-map display-1 text-muted mb-3"></i>
                        <h3 class="text-muted mb-3">No tienes direcciones guardadas</h3>
                        <p class="text-muted mb-4">
                            Agrega una dirección de envío para agilizar tus compras futuras.
                        </p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaDireccion">
                            <i class="bi bi-plus-circle me-2"></i>Agregar dirección
                        </button>
                    </div>
                </div>
            @else
                <div class="row">
                    @foreach($direcciones as $direccion)
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $direccion->nombre }}</h6>
                                        @if($direccion->es_default)
                                            <span class="badge bg-success">Predeterminada</span>
                                        @endif
                                    </div>
                                    <p class="small text-muted mb-2">
                                        {{ $direccion->calle }}, {{ $direccion->numero }}
                                        @if($direccion->piso)
                                            - Piso {{ $direccion->piso }}
                                        @endif
                                    </p>
                                    <p class="small text-muted mb-3">
                                        {{ $direccion->codigo_postal }} {{ $direccion->ciudad }}, {{ $direccion->provincia }}<br>
                                        {{ $direccion->pais }}
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('cuenta.editar-direccion', $direccion->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </a>
                                        <form action="{{ route('cuenta.eliminar-direccion', $direccion->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro?')">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal Nueva Dirección --}}
<div class="modal fade" id="modalNuevaDireccion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-map me-2"></i>Agregar nueva dirección
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('cuenta.agregar-direccion') }}" method="POST">
                <div class="modal-body">
                    @csrf

                    {{-- Nombre de la Dirección --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la dirección (ej: Mi casa)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Mi casa" required>
                    </div>

                    {{-- Calle --}}
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="calle" class="form-label">Calle</label>
                            <input type="text" class="form-control" id="calle" name="calle" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" required>
                        </div>
                    </div>

                    {{-- Piso y Puerta --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="piso" class="form-label">Piso (opcional)</label>
                            <input type="text" class="form-control" id="piso" name="piso" placeholder="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="puerta" class="form-label">Puerta (opcional)</label>
                            <input type="text" class="form-control" id="puerta" name="puerta" placeholder="A">
                        </div>
                    </div>

                    {{-- Localidad --}}
                    <div class="mb-3">
                        <label for="ciudad" class="form-label">Ciudad</label>
                        <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                    </div>

                    {{-- Provincia y CP --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="provincia" class="form-label">Provincia</label>
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                        </div>
                    </div>

                    {{-- País --}}
                    <div class="mb-3">
                        <label for="pais" class="form-label">País</label>
                        <input type="text" class="form-control" id="pais" name="pais" value="España" required>
                    </div>

                    {{-- Dirección Predeterminada --}}
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="es_default" name="es_default">
                        <label class="form-check-label" for="es_default">
                            Establecer como dirección predeterminada
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-2"></i>Guardar dirección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .list-group-item.active {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    #mainContent.dimmed {
        opacity: 0.5;
    }

    #sidebarCuenta.dimmed {
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    const modals = ['#modalNuevaDireccion', '#modalEditarDireccion'];
    
    modals.forEach(modalSelector => {
        const modal = document.querySelector(modalSelector);
        if (modal) {
            modal.addEventListener('show.bs.modal', function() {
                document.getElementById('mainContent').classList.add('dimmed');
                document.getElementById('sidebarCuenta').classList.add('dimmed');
            });
            modal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('mainContent').classList.remove('dimmed');
                document.getElementById('sidebarCuenta').classList.remove('dimmed');
            });
        }
    });
</script>
@endpush

@endsection
