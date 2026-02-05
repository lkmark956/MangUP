{{-- Panel de Filtros Mejorado --}}
<div class="filters-panel">
    <div class="filters-header">
        <h5>
            <i class="bi bi-funnel"></i>Filtros
        </h5>
        @if(request()->hasAny(['tipo', 'categorias', 'precio_min', 'precio_max', 'disponibilidad', 'solo_ofertas', 'orden']))
            <a href="{{ route('productos.index') }}" class="filters-clear-btn" title="Limpiar filtros">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </div>
    
    <form action="{{ route('productos.index') }}" method="GET" id="filtrosForm">
        {{-- Mantener búsqueda actual --}}
        @if(request('buscar'))
            <input type="hidden" name="buscar" value="{{ request('buscar') }}">
        @endif

        {{-- Filtros activos --}}
        @if(request()->hasAny(['tipo', 'categorias', 'precio_min', 'precio_max', 'disponibilidad', 'solo_ofertas']))
        <div class="active-filters">
            <span class="active-filters-label">Filtros activos:</span>
            <div class="active-filters-tags">
                @foreach((array)request('tipo', []) as $tipo)
                    <span class="filter-tag">
                        {{ ucfirst($tipo) }}
                        <a href="{{ request()->fullUrlWithQuery(['tipo' => array_diff((array)request('tipo'), [$tipo])]) }}">×</a>
                    </span>
                @endforeach
                @if(request('precio_min') || request('precio_max'))
                    <span class="filter-tag">
                        {{ request('precio_min', 0) }}€ - {{ request('precio_max', '∞') }}€
                        <a href="{{ request()->fullUrlWithQuery(['precio_min' => null, 'precio_max' => null]) }}">×</a>
                    </span>
                @endif
                @foreach((array)request('disponibilidad', []) as $disp)
                    <span class="filter-tag">
                        {{ str_replace('_', ' ', ucfirst($disp)) }}
                        <a href="{{ request()->fullUrlWithQuery(['disponibilidad' => array_diff((array)request('disponibilidad'), [$disp])]) }}">×</a>
                    </span>
                @endforeach
                @if(request('solo_ofertas'))
                    <span class="filter-tag">
                        Solo ofertas
                        <a href="{{ request()->fullUrlWithQuery(['solo_ofertas' => null]) }}">×</a>
                    </span>
                @endif
            </div>
        </div>
        @endif

        {{-- Filtro por Tipo de Producto --}}
        <div class="filter-section">
            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#filtroTipo" aria-expanded="true">
                <span><i class="bi bi-grid-3x3-gap"></i>Tipo de Producto</span>
                <i class="bi bi-chevron-down filter-toggle-icon"></i>
            </h6>
            <div class="collapse show" id="filtroTipo">
                <div class="filter-options">
                    <label class="filter-checkbox {{ in_array('manga', (array)request('tipo', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="tipo[]" 
                            value="manga" 
                            {{ in_array('manga', (array)request('tipo', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="filter-checkbox-content">
                            <span class="filter-checkbox-icon">
                                <i class="bi bi-book"></i>
                            </span>
                            <span class="filter-checkbox-label">Mangas</span>
                            <span class="filter-checkbox-count">{{ $mangasCount ?? 0 }}</span>
                        </span>
                    </label>
                    <label class="filter-checkbox {{ in_array('figura', (array)request('tipo', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="tipo[]" 
                            value="figura" 
                            {{ in_array('figura', (array)request('tipo', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="filter-checkbox-content">
                            <span class="filter-checkbox-icon">
                                <i class="bi bi-person-standing"></i>
                            </span>
                            <span class="filter-checkbox-label">Figuras</span>
                            <span class="filter-checkbox-count">{{ $figurasCount ?? 0 }}</span>
                        </span>
                    </label>
                    <label class="filter-checkbox {{ in_array('merch', (array)request('tipo', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="tipo[]" 
                            value="merch" 
                            {{ in_array('merch', (array)request('tipo', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="filter-checkbox-content">
                            <span class="filter-checkbox-icon">
                                <i class="bi bi-bag"></i>
                            </span>
                            <span class="filter-checkbox-label">Merchandising</span>
                            <span class="filter-checkbox-count">{{ $merchsCount ?? 0 }}</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Filtro por Rango de Precio --}}
        <div class="filter-section">
            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#filtroPrecio" aria-expanded="true">
                <span><i class="bi bi-currency-euro"></i>Precio</span>
                <i class="bi bi-chevron-down filter-toggle-icon"></i>
            </h6>
            <div class="collapse show" id="filtroPrecio">
                {{-- Botones rápidos de precio --}}
                <div class="price-quick-btns">
                    <button type="button" 
                        class="price-quick-btn {{ request('precio_max') == 20 && !request('precio_min') ? 'active' : '' }}" 
                        onclick="setPrecioRango(0, 20)">
                        <span>0-20€</span>
                    </button>
                    <button type="button" 
                        class="price-quick-btn {{ request('precio_min') == 20 && request('precio_max') == 50 ? 'active' : '' }}" 
                        onclick="setPrecioRango(20, 50)">
                        <span>20-50€</span>
                    </button>
                    <button type="button" 
                        class="price-quick-btn {{ request('precio_min') == 50 && request('precio_max') == 100 ? 'active' : '' }}" 
                        onclick="setPrecioRango(50, 100)">
                        <span>50-100€</span>
                    </button>
                    <button type="button" 
                        class="price-quick-btn {{ request('precio_min') == 100 && !request('precio_max') ? 'active' : '' }}" 
                        onclick="setPrecioRango(100, '')">
                        <span>+100€</span>
                    </button>
                </div>
                
                {{-- Inputs de precio personalizados --}}
                <div class="price-inputs price-inputs-vertical">
                    <div class="price-input-group">
                        <label>Mín</label>
                        <div class="price-input-wrapper">
                            <input type="number" 
                                name="precio_min" 
                                id="precioMin"
                                value="{{ request('precio_min') }}" 
                                min="0" 
                                placeholder="0">
                            <span class="price-currency">€</span>
                        </div>
                    </div>
                    <div class="price-input-group">
                        <label>Máx</label>
                        <div class="price-input-wrapper">
                            <input type="number" 
                                name="precio_max" 
                                id="precioMax"
                                value="{{ request('precio_max') }}" 
                                min="0" 
                                placeholder="∞">
                            <span class="price-currency">€</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtro por Disponibilidad --}}
        <div class="filter-section">
            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#filtroDisponibilidad" aria-expanded="true">
                <span><i class="bi bi-box-seam"></i>Disponibilidad</span>
                <i class="bi bi-chevron-down filter-toggle-icon"></i>
            </h6>
            <div class="collapse show" id="filtroDisponibilidad">
                <div class="availability-options">
                    <label class="availability-option {{ in_array('en_stock', (array)request('disponibilidad', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="disponibilidad[]" 
                            value="en_stock"
                            {{ in_array('en_stock', (array)request('disponibilidad', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="availability-icon in-stock">
                            <i class="bi bi-check-lg"></i>
                        </span>
                        <span class="availability-text">En stock</span>
                    </label>
                    <label class="availability-option {{ in_array('ultimas_unidades', (array)request('disponibilidad', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="disponibilidad[]" 
                            value="ultimas_unidades"
                            {{ in_array('ultimas_unidades', (array)request('disponibilidad', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="availability-icon low-stock">
                            <i class="bi bi-exclamation"></i>
                        </span>
                        <span class="availability-text">Últimas unidades</span>
                    </label>
                    <label class="availability-option {{ in_array('agotado', (array)request('disponibilidad', [])) ? 'active' : '' }}">
                        <input type="checkbox" 
                            name="disponibilidad[]" 
                            value="agotado"
                            {{ in_array('agotado', (array)request('disponibilidad', [])) ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        <span class="availability-icon out-of-stock">
                            <i class="bi bi-x-lg"></i>
                        </span>
                        <span class="availability-text">Agotado</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Filtro por Categoría --}}
        @if(isset($categorias) && $categorias->count() > 0)
        <div class="filter-section">
            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#filtroCategoria" aria-expanded="true">
                <span><i class="bi bi-tag"></i>Categoría</span>
                <i class="bi bi-chevron-down filter-toggle-icon"></i>
            </h6>
            <div class="collapse show" id="filtroCategoria">
                <div class="filter-options categories-list">
                    @foreach($categorias as $categoria)
                        <label class="filter-checkbox {{ in_array($categoria->id, (array)request('categorias', [])) ? 'active' : '' }}">
                            <input type="checkbox" 
                                name="categorias[]" 
                                value="{{ $categoria->id }}"
                                {{ in_array($categoria->id, (array)request('categorias', [])) ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <span class="filter-checkbox-content">
                                <span class="filter-checkbox-label">{{ $categoria->nombre }}</span>
                                <span class="filter-checkbox-count">{{ $categoria->productos_count ?? 0 }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Filtro por Ofertas --}}
        <div class="filter-section">
            <h6 class="filter-title" data-bs-toggle="collapse" data-bs-target="#filtroOfertas" aria-expanded="true">
                <span><i class="bi bi-percent"></i>Ofertas</span>
                <i class="bi bi-chevron-down filter-toggle-icon"></i>
            </h6>
            <div class="collapse show" id="filtroOfertas">
                <label class="offer-toggle {{ request('solo_ofertas') ? 'active' : '' }}">
                    <input type="checkbox" 
                        name="solo_ofertas" 
                        value="1"
                        {{ request('solo_ofertas') ? 'checked' : '' }}
                        onchange="this.form.submit()">
                    <span class="offer-toggle-track">
                        <span class="offer-toggle-thumb"></span>
                    </span>
                    <span class="offer-toggle-label">
                        <i class="bi bi-lightning-fill"></i>
                        Solo productos en oferta
                    </span>
                </label>
            </div>
        </div>

        {{-- Botones de Acción --}}
        <div class="filter-actions">
            <button type="submit" class="btn-apply-filters">
                <i class="bi bi-funnel"></i>
                <span>Aplicar filtros</span>
            </button>
            <a href="{{ route('productos.index') }}" class="btn-clear-filters">
                <i class="bi bi-arrow-counterclockwise"></i>
                <span>Limpiar todo</span>
            </a>
        </div>
    </form>
</div>

{{-- Scripts para el panel de filtros --}}
<script>
    function setPrecioRango(min, max) {
        document.getElementById('precioMin').value = min || '';
        document.getElementById('precioMax').value = max || '';
        document.getElementById('filtrosForm').submit();
    }

    // Aplicar filtros de precio al presionar Enter
    document.getElementById('precioMin')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filtrosForm').submit();
        }
    });

    document.getElementById('precioMax')?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filtrosForm').submit();
        }
    });

    // Toggle icon rotation
    document.querySelectorAll('.filter-title[data-bs-toggle="collapse"]').forEach(function(title) {
        title.addEventListener('click', function() {
            this.classList.toggle('collapsed');
        });
    });
</script>
