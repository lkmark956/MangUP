{{--
    Vista de listado de Ofertas - Panel Admin
--}}
@extends('admin.layouts.app')

@section('title', 'Gestión de Ofertas')
@section('page-title', 'Gestión de Ofertas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-tag me-2"></i>
            Todas las Ofertas
        </h5>
        <a href="{{ route('admin.ofertas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Nueva Oferta
        </a>
    </div>
    
    <div class="card-body">
        {{-- Filtros --}}
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="buscar" class="form-control" 
                           placeholder="Buscar por nombre..." 
                           value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="activa" {{ request('estado') == 'activa' ? 'selected' : '' }}>Activas</option>
                    <option value="inactiva" {{ request('estado') == 'inactiva' ? 'selected' : '' }}>Inactivas</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-filter me-1"></i> Filtrar
                </button>
            </div>
            @if(request()->hasAny(['buscar', 'estado']))
                <div class="col-md-2">
                    <a href="{{ route('admin.ofertas.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg me-1"></i> Limpiar
                    </a>
                </div>
            @endif
        </form>
        
        {{-- Tabla de ofertas --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Descuento</th>
                        <th>Aplica a</th>
                        <th>Vigencia</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ofertas as $oferta)
                        @php
                            $hoy = now()->startOfDay();
                            $vigente = $oferta->activa && $oferta->fecha_inicio <= $hoy && $oferta->fecha_fin >= $hoy;
                            $expirada = $oferta->fecha_fin < $hoy;
                            $proxima = $oferta->fecha_inicio > $hoy;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $oferta->nombre }}</strong>
                                @if($oferta->descripcion)
                                    <br><small class="text-muted">{{ Str::limit($oferta->descripcion, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success fs-6">
                                    @if($oferta->tipo_descuento === 'porcentaje')
                                        {{ $oferta->valor_descuento }}%
                                    @else
                                        {{ number_format($oferta->valor_descuento, 2) }}€
                                    @endif
                                </span>
                            </td>
                            <td>
                                @switch($oferta->aplica_a)
                                    @case('todos')
                                        <span class="badge bg-primary">Todos los productos</span>
                                        @break
                                    @case('manga')
                                        <span class="badge bg-info">Mangas</span>
                                        @break
                                    @case('figura')
                                        <span class="badge bg-warning text-dark">Figuras</span>
                                        @break
                                    @case('merch')
                                        <span class="badge bg-secondary">Merchandising</span>
                                        @break
                                    @case('producto_especifico')
                                        <span class="badge bg-dark">Producto específico</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <small>
                                    {{ $oferta->fecha_inicio->format('d/m/Y') }} - {{ $oferta->fecha_fin->format('d/m/Y') }}
                                </small>
                                @if($vigente)
                                    <br><span class="badge bg-success">Vigente</span>
                                @elseif($expirada)
                                    <br><span class="badge bg-danger">Expirada</span>
                                @elseif($proxima)
                                    <br><span class="badge bg-info">Próxima</span>
                                @endif
                            </td>
                            <td>
                                @if($oferta->activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    <a href="{{ route('admin.ofertas.edit', $oferta) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.ofertas.destroy', $oferta) }}" method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta oferta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-tag display-4 text-muted"></i>
                                <p class="text-muted mt-2">No hay ofertas creadas</p>
                                <a href="{{ route('admin.ofertas.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Crear primera oferta
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Paginación --}}
        <div class="d-flex justify-content-center">
            {{ $ofertas->links() }}
        </div>
    </div>
</div>
@endsection
