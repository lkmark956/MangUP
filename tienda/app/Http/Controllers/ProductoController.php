<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use App\Models\CategoriaManga;
use App\Models\CategoriaFigura;
use App\Models\CategoriaMerch;

class ProductoController extends Controller
{
    /**
     * Mostrar listado de productos con filtros
     */
    public function index(Request $request)
    {
        // Obtener el filtro de tipo (puede ser string o array)
        $tipoFiltro = $request->input('tipo');
        
        // Normalizar a array si es string
        if (is_string($tipoFiltro)) {
            $tipoFiltro = [$tipoFiltro];
        }
        
        // Si no hay filtro de tipo, mostrar todos
        $mostrarManga = empty($tipoFiltro) || in_array('manga', $tipoFiltro);
        $mostrarFigura = empty($tipoFiltro) || in_array('figura', $tipoFiltro);
        $mostrarMerch = empty($tipoFiltro) || in_array('merch', $tipoFiltro);
        
        // Obtener todos los productos de las diferentes tablas
        $mangasQuery = Manga::query();
        $figurasQuery = Figura::query();
        $merchsQuery = Merch::query();

        // Aplicar filtro de búsqueda
        if ($request->filled('buscar')) {
            $busqueda = $request->buscar;
            $mangasQuery->where('nombre', 'like', "%{$busqueda}%");
            $figurasQuery->where('nombre', 'like', "%{$busqueda}%");
            $merchsQuery->where('nombre', 'like', "%{$busqueda}%");
        }

        // Aplicar filtro de precio mínimo
        if ($request->filled('precio_min')) {
            $mangasQuery->where('precio', '>=', $request->precio_min);
            $figurasQuery->where('precio', '>=', $request->precio_min);
            $merchsQuery->where('precio', '>=', $request->precio_min);
        }

        // Aplicar filtro de precio máximo
        if ($request->filled('precio_max')) {
            $mangasQuery->where('precio', '<=', $request->precio_max);
            $figurasQuery->where('precio', '<=', $request->precio_max);
            $merchsQuery->where('precio', '<=', $request->precio_max);
        }

        // Aplicar filtro de disponibilidad
        if ($request->filled('disponibilidad')) {
            $disponibilidad = (array) $request->disponibilidad;
            
            // Construir condiciones dinámicamente para Mangas y Figuras (tienen stock directo)
            $aplicarFiltroDisponibilidad = function($query) use ($disponibilidad) {
                $query->where(function($q) use ($disponibilidad) {
                    $hasFiltro = false;
                    
                    if (in_array('en_stock', $disponibilidad)) {
                        $q->orWhere(function($subQ) {
                            $subQ->where('stock', '>', 10)->orWhereNull('stock');
                        });
                        $hasFiltro = true;
                    }
                    
                    if (in_array('ultimas_unidades', $disponibilidad)) {
                        $q->orWhereBetween('stock', [1, 10]);
                        $hasFiltro = true;
                    }
                    
                    if (in_array('agotado', $disponibilidad)) {
                        $q->orWhere('stock', '<=', 0);
                        $hasFiltro = true;
                    }
                    
                    // Si no hay filtros, mostrar todo
                    if (!$hasFiltro) {
                        $q->whereRaw('1=1');
                    }
                });
            };
            
            // Aplicar filtro a Mangas y Figuras
            $aplicarFiltroDisponibilidad($mangasQuery);
            $aplicarFiltroDisponibilidad($figurasQuery);
            
            // Para Merch, el stock está en las variantes
            // Necesitamos filtrar de manera diferente
            if ($mostrarMerch) {
                $merchIds = [];
                
                // Obtener IDs de Merch según disponibilidad
                if (in_array('en_stock', $disponibilidad)) {
                    // Merch con stock > 10 en variantes
                    $ids = DB::table('merchs')
                        ->leftJoin('merch_variantes', 'merchs.id', '=', 'merch_variantes.merch_id')
                        ->select('merchs.id')
                        ->groupBy('merchs.id')
                        ->havingRaw('SUM(COALESCE(merch_variantes.stock, 0)) > 10')
                        ->pluck('id')
                        ->toArray();
                    $merchIds = array_merge($merchIds, $ids);
                }
                
                if (in_array('ultimas_unidades', $disponibilidad)) {
                    // Merch con stock entre 1 y 10
                    $ids = DB::table('merchs')
                        ->leftJoin('merch_variantes', 'merchs.id', '=', 'merch_variantes.merch_id')
                        ->select('merchs.id')
                        ->groupBy('merchs.id')
                        ->havingRaw('SUM(COALESCE(merch_variantes.stock, 0)) BETWEEN 1 AND 10')
                        ->pluck('id')
                        ->toArray();
                    $merchIds = array_merge($merchIds, $ids);
                }
                
                if (in_array('agotado', $disponibilidad)) {
                    // Merch con stock 0 o sin variantes
                    $ids = DB::table('merchs')
                        ->leftJoin('merch_variantes', 'merchs.id', '=', 'merch_variantes.merch_id')
                        ->select('merchs.id')
                        ->groupBy('merchs.id')
                        ->havingRaw('SUM(COALESCE(merch_variantes.stock, 0)) <= 0')
                        ->pluck('id')
                        ->toArray();
                    $merchIds = array_merge($merchIds, $ids);
                }
                
                // Filtrar por IDs encontrados
                if (!empty($merchIds)) {
                    $merchsQuery->whereIn('id', array_unique($merchIds));
                } else {
                    // Si no hay IDs, no mostrar nada de merch
                    $merchsQuery->whereRaw('1=0');
                }
            }
        }

        // Obtener resultados según el filtro de tipo
        $mangas = collect();
        $figuras = collect();
        $merchs = collect();
        
        if ($mostrarManga) {
            $mangas = $mangasQuery->with('imagenes')->get()->map(function ($item) {
                $item->tipo = 'manga';
                return $item;
            });
        }

        if ($mostrarFigura) {
            $figuras = $figurasQuery->with('imagenes')->get()->map(function ($item) {
                $item->tipo = 'figura';
                return $item;
            });
        }

        if ($mostrarMerch) {
            $merchs = $merchsQuery->with('imagenes')->get()->map(function ($item) {
                $item->tipo = 'merch';
                return $item;
            });
        }

        // Combinar todos los productos
        $todosProductos = $mangas->concat($figuras)->concat($merchs);

        // Aplicar ordenamiento
        if ($request->filled('ordenar')) {
            switch ($request->ordenar) {
                case 'precio_asc':
                    $todosProductos = $todosProductos->sortBy('precio');
                    break;
                case 'precio_desc':
                    $todosProductos = $todosProductos->sortByDesc('precio');
                    break;
                case 'nombre_asc':
                    $todosProductos = $todosProductos->sortBy('nombre');
                    break;
                case 'nombre_desc':
                    $todosProductos = $todosProductos->sortByDesc('nombre');
                    break;
                case 'recientes':
                    $todosProductos = $todosProductos->sortByDesc('created_at');
                    break;
            }
        }

        // Paginar manualmente la colección
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $productos = new \Illuminate\Pagination\LengthAwarePaginator(
            $todosProductos->forPage($currentPage, $perPage),
            $todosProductos->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Obtener categorías para los filtros
        $categoriasManga = CategoriaManga::withCount('mangas as productos_count')->get();
        $categoriasFigura = CategoriaFigura::withCount('figuras as productos_count')->get();
        $categoriasMerch = CategoriaMerch::withCount('merchs as productos_count')->get();

        $categorias = collect()
            ->concat($categoriasManga)
            ->concat($categoriasFigura)
            ->concat($categoriasMerch);

        // Obtener contadores de productos totales
        $mangasCount = Manga::count();
        $figurasCount = Figura::count();
        $merchsCount = Merch::count();

        return view('productos.index', compact('productos', 'categorias', 'mangasCount', 'figurasCount', 'merchsCount'));
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show(Request $request, $id)
    {
        // Buscar el producto en las diferentes tablas
        $producto = null;
        $tipo = $request->get('tipo', null);

        // Intentar buscar en mangas
        if (!$tipo || $tipo === 'manga') {
            $producto = Manga::with('categoria', 'imagenes')->find($id);
            if ($producto) {
                $producto->tipo = 'manga';
            }
        }

        // Si no se encontró, buscar en figuras
        if (!$producto && (!$tipo || $tipo === 'figura')) {
            $producto = Figura::with('categoria', 'imagenes')->find($id);
            if ($producto) {
                $producto->tipo = 'figura';
            }
        }

        // Si no se encontró, buscar en merch
        if (!$producto && (!$tipo || $tipo === 'merch')) {
            $producto = Merch::with('categoria', 'imagenes')->find($id);
            if ($producto) {
                $producto->tipo = 'merch';
            }
        }

        if (!$producto) {
            return view('productos.show', ['producto' => null]);
        }

        // Obtener productos relacionados de la misma categoría
        $productosRelacionados = collect();
        
        if ($producto->tipo === 'manga' && $producto->categoria_manga_id) {
            $productosRelacionados = Manga::where('categoria_manga_id', $producto->categoria_manga_id)
                ->where('id', '!=', $producto->id)
                ->take(4)
                ->get();
        } elseif ($producto->tipo === 'figura' && $producto->categoria_figura_id) {
            $productosRelacionados = Figura::where('categoria_figura_id', $producto->categoria_figura_id)
                ->where('id', '!=', $producto->id)
                ->take(4)
                ->get();
        } elseif ($producto->tipo === 'merch' && $producto->categoria_merch_id) {
            $productosRelacionados = Merch::where('categoria_merch_id', $producto->categoria_merch_id)
                ->where('id', '!=', $producto->id)
                ->take(4)
                ->get();
        }

        return view('productos.show', compact('producto', 'tipo', 'productosRelacionados'));
    }
}
