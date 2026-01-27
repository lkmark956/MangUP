<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            $disponibilidad = $request->disponibilidad;
            
            if (in_array('en_stock', $disponibilidad)) {
                $mangasQuery->where('stock', '>', 10);
                $figurasQuery->where('stock', '>', 10);
                $merchsQuery->where('stock', '>', 10);
            }
            
            if (in_array('ultimas_unidades', $disponibilidad)) {
                $mangasQuery->orWhereBetween('stock', [1, 10]);
                $figurasQuery->orWhereBetween('stock', [1, 10]);
                $merchsQuery->orWhereBetween('stock', [1, 10]);
            }
            
            if (in_array('agotado', $disponibilidad)) {
                $mangasQuery->orWhere('stock', '<=', 0);
                $figurasQuery->orWhere('stock', '<=', 0);
                $merchsQuery->orWhere('stock', '<=', 0);
            }
        }

        // Obtener resultados
        $mangas = $mangasQuery->get()->map(function ($item) {
            $item->tipo = 'manga';
            return $item;
        });

        $figuras = $figurasQuery->get()->map(function ($item) {
            $item->tipo = 'figura';
            return $item;
        });

        $merchs = $merchsQuery->get()->map(function ($item) {
            $item->tipo = 'merch';
            return $item;
        });

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

        return view('productos.index', compact('productos', 'categorias'));
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

        return view('productos.show', compact('producto', 'productosRelacionados'));
    }
}
