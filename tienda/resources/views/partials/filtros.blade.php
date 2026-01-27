{{-- Panel de Filtros --}}
<div class="filters-panel card shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-funnel me-2"></i>Filtros
        </h5>
        <a href="{{ route('productos.index') }}" class="btn btn-sm btn-light" title="Limpiar filtros">
            <i class="bi bi-x-lg"></i>
        </a>
    </div>
    
    <div class="card-body">
        <form action="{{ route('productos.index') }}" method="GET" id="filtrosForm">
            {{-- Mantener búsqueda actual --}}
            @if(request('buscar'))
                <input type="hidden" name="buscar" value="{{ request('buscar') }}">
            @endif

            {{-- Filtro por Categoría --}}
            <div class="filter-section mb-4">
                <h6 class="filter-title fw-bold mb-3">
                    <i class="bi bi-tag me-2"></i>Categoría
                </h6>
                <div class="filter-options">
                    @if(isset($categorias) && $categorias->count() > 0)
                        @foreach($categorias as $categoria)
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                    type="checkbox" 
                                    name="categorias[]" 
                                    value="{{ $categoria->id }}" 
                                    id="cat_{{ $categoria->id }}"
                                    {{ in_array($categoria->id, request('categorias', [])) ? 'checked' : '' }}>
                                <label class="form-check-label d-flex justify-content-between" for="cat_{{ $categoria->id }}">
                                    <span>{{ $categoria->nombre }}</span>
                                    <span class="badge bg-light text-dark">{{ $categoria->productos_count ?? 0 }}</span>
                                </label>
                            </div>
                        @endforeach
                    @else
                        {{-- Categorías de ejemplo si no hay datos --}}
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="categorias[]" value="manga" id="cat_manga">
                            <label class="form-check-label" for="cat_manga">Manga</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="categorias[]" value="figura" id="cat_figura">
                            <label class="form-check-label" for="cat_figura">Figuras</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="categorias[]" value="merch" id="cat_merch">
                            <label class="form-check-label" for="cat_merch">Merchandising</label>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Filtro por Rango de Precio --}}
            <div class="filter-section mb-4">
                <h6 class="filter-title fw-bold mb-3">
                    <i class="bi bi-currency-euro me-2"></i>Precio
                </h6>
                <div class="price-range-container">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="price-label" id="precioMinLabel">{{ request('precio_min', 0) }}€</span>
                        <span class="price-label" id="precioMaxLabel">{{ request('precio_max', 500) }}€</span>
                    </div>
                    
                    <div class="price-inputs d-flex gap-2 mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Min</span>
                            <input type="number" 
                                class="form-control" 
                                name="precio_min" 
                                id="precioMin"
                                value="{{ request('precio_min', 0) }}" 
                                min="0" 
                                max="1000"
                                placeholder="0">
                            <span class="input-group-text">€</span>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Max</span>
                            <input type="number" 
                                class="form-control" 
                                name="precio_max" 
                                id="precioMax"
                                value="{{ request('precio_max', 500) }}" 
                                min="0" 
                                max="1000"
                                placeholder="500">
                            <span class="input-group-text">€</span>
                        </div>
                    </div>

                    {{-- Slider de precio --}}
                    <div class="price-slider-container mb-2">
                        <input type="range" 
                            class="form-range price-slider" 
                            id="priceRangeMin"
                            min="0" 
                            max="500" 
                            value="{{ request('precio_min', 0) }}"
                            oninput="actualizarPrecioMin(this.value)">
                        <input type="range" 
                            class="form-range price-slider" 
                            id="priceRangeMax"
                            min="0" 
                            max="500" 
                            value="{{ request('precio_max', 500) }}"
                            oninput="actualizarPrecioMax(this.value)">
                    </div>

                    {{-- Rangos predefinidos --}}
                    <div class="price-presets d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPrecioRango(0, 20)">0-20€</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPrecioRango(20, 50)">20-50€</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPrecioRango(50, 100)">50-100€</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setPrecioRango(100, 500)">+100€</button>
                    </div>
                </div>
            </div>

            {{-- Filtro por Disponibilidad --}}
            <div class="filter-section mb-4">
                <h6 class="filter-title fw-bold mb-3">
                    <i class="bi bi-box-seam me-2"></i>Disponibilidad
                </h6>
                <div class="filter-options">
                    <div class="form-check mb-2">
                        <input class="form-check-input" 
                            type="checkbox" 
                            name="disponibilidad[]" 
                            value="en_stock" 
                            id="disp_stock"
                            {{ in_array('en_stock', request('disponibilidad', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disp_stock">
                            <i class="bi bi-check-circle text-success me-1"></i>En stock
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" 
                            type="checkbox" 
                            name="disponibilidad[]" 
                            value="ultimas_unidades" 
                            id="disp_ultimas"
                            {{ in_array('ultimas_unidades', request('disponibilidad', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disp_ultimas">
                            <i class="bi bi-exclamation-circle text-warning me-1"></i>Últimas unidades
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" 
                            type="checkbox" 
                            name="disponibilidad[]" 
                            value="agotado" 
                            id="disp_agotado"
                            {{ in_array('agotado', request('disponibilidad', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="disp_agotado">
                            <i class="bi bi-x-circle text-danger me-1"></i>Agotado
                        </label>
                    </div>
                </div>
            </div>

            {{-- Filtro por Ofertas --}}
            <div class="filter-section mb-4">
                <h6 class="filter-title fw-bold mb-3">
                    <i class="bi bi-percent me-2"></i>Ofertas
                </h6>
                <div class="form-check form-switch">
                    <input class="form-check-input" 
                        type="checkbox" 
                        name="solo_ofertas" 
                        value="1" 
                        id="soloOfertas"
                        {{ request('solo_ofertas') ? 'checked' : '' }}>
                    <label class="form-check-label" for="soloOfertas">Solo productos en oferta</label>
                </div>
            </div>

            {{-- Botón Filtrar --}}
            <div class="filter-actions d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Aplicar filtros
                </button>
                <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-2"></i>Limpiar todo
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Scripts para el panel de filtros --}}
<script>
    function actualizarPrecioMin(valor) {
        document.getElementById('precioMin').value = valor;
        document.getElementById('precioMinLabel').textContent = valor + '€';
    }

    function actualizarPrecioMax(valor) {
        document.getElementById('precioMax').value = valor;
        document.getElementById('precioMaxLabel').textContent = valor + '€';
    }

    function setPrecioRango(min, max) {
        document.getElementById('precioMin').value = min;
        document.getElementById('precioMax').value = max;
        document.getElementById('priceRangeMin').value = min;
        document.getElementById('priceRangeMax').value = max;
        document.getElementById('precioMinLabel').textContent = min + '€';
        document.getElementById('precioMaxLabel').textContent = max + '€';
    }

    // Sincronizar inputs con sliders
    document.getElementById('precioMin')?.addEventListener('input', function() {
        document.getElementById('priceRangeMin').value = this.value;
        document.getElementById('precioMinLabel').textContent = this.value + '€';
    });

    document.getElementById('precioMax')?.addEventListener('input', function() {
        document.getElementById('priceRangeMax').value = this.value;
        document.getElementById('precioMaxLabel').textContent = this.value + '€';
    });
</script>
