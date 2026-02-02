@extends('admin.layouts.app')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Usuarios')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Lista de Usuarios</h5>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Usuario
        </a>
    </div>
    <div class="card-body">
        <!-- Barra de búsqueda y filtros -->
        <form action="{{ route('admin.usuarios.index') }}" method="GET" class="mb-4">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="buscar" class="form-control" 
                               placeholder="Buscar por nombre o email..." 
                               value="{{ request('buscar') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="tipo" class="form-select">
                        <option value="">Todos los usuarios</option>
                        <option value="admin" {{ request('tipo') == 'admin' ? 'selected' : '' }}>Administradores</option>
                        <option value="user" {{ request('tipo') == 'user' ? 'selected' : '' }}>Usuarios normales</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary">Filtrar</button>
                    @if(request('buscar') || request('tipo'))
                        <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Tabla de usuarios -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th class="text-center">Rol</th>
                        <th class="text-center">Registrado</th>
                        <th class="text-center" style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>
                                <span class="badge bg-secondary">{{ $usuario->id }}</span>
                            </td>
                            <td>
                                <strong>{{ $usuario->name }}</strong>
                                @if($usuario->id === auth()->id())
                                    <span class="badge bg-info ms-1">Tú</span>
                                @endif
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td class="text-center">
                                @if($usuario->is_admin)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-shield-check me-1"></i>Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Usuario</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <small>{{ $usuario->created_at->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <!-- Toggle Admin -->
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.toggle-admin', $usuario) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn {{ $usuario->is_admin ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                    title="{{ $usuario->is_admin ? 'Quitar admin' : 'Hacer admin' }}">
                                                <i class="bi bi-{{ $usuario->is_admin ? 'person-dash' : 'person-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No hay usuarios que coincidan con la búsqueda
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Mostrando {{ $usuarios->firstItem() ?? 0 }} - {{ $usuarios->lastItem() ?? 0 }} 
                de {{ $usuarios->total() }} usuarios
            </div>
            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection
