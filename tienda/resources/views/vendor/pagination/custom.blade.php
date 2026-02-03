@if ($paginator->hasPages())
    <nav class="pagination-wrapper" aria-label="Paginación">
        <ul class="pagination">
            {{-- Botón Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">« Anterior</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">« Anterior</a>
                </li>
            @endif

            {{-- Números de página --}}
            @foreach ($elements as $element)
                {{-- Separador "..." --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array de links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Botón Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente »</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Siguiente »</span>
                </li>
            @endif
        </ul>
        
        <div class="pagination-info">
            Mostrando {{ $paginator->firstItem() ?? 0 }} - {{ $paginator->lastItem() ?? 0 }} de {{ $paginator->total() }} resultados
        </div>
    </nav>
@endif
