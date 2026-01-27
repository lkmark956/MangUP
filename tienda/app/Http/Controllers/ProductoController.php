<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use App\Models\CategoriaManga;
use App\Models\CategoriaFigura;
use App\Models\CategoriaMerch;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Listar todos los productos (mangas, figuras y merch)
     */
    public function index()
    {
        // Obtener todos los productos
        $mangas = Manga::all();
        $figuras = Figura::all();
        $merchs = Merch::all();

        // Combinar todos los productos
        $productos = collect()
            ->merge($mangas->map(fn($p) => $p->setAttribute('tipo', 'manga')))
            ->merge($figuras->map(fn($p) => $p->setAttribute('tipo', 'figura')))
            ->merge($merchs->map(fn($p) => $p->setAttribute('tipo', 'merch')));

        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar detalle de un producto
     */
    public function show($id)
    {
        // Intentar obtener el producto de cada tabla
        $producto = Manga::find($id);
        if ($producto) {
            $producto->setAttribute('tipo', 'manga');
            return view('productos.show', compact('producto'));
        }

        $producto = Figura::find($id);
        if ($producto) {
            $producto->setAttribute('tipo', 'figura');
            return view('productos.show', compact('producto'));
        }

        $producto = Merch::find($id);
        if ($producto) {
            $producto->setAttribute('tipo', 'merch');
            return view('productos.show', compact('producto'));
        }

        // Si no se encuentra el producto
        abort(404, 'Producto no encontrado');
    }

    /**
     * Filtrar productos por categoría
     */
    public function porCategoria($id)
    {
        // Intentar obtener productos de la categoría de manga
        $categoriaManga = CategoriaManga::find($id);
        if ($categoriaManga) {
            $productos = $categoriaManga->mangas;
            $categoria = $categoriaManga;
            $tipo = 'manga';
            return view('productos.categoria', compact('productos', 'categoria', 'tipo'));
        }

        // Intentar obtener productos de la categoría de figura
        $categoriaFigura = CategoriaFigura::find($id);
        if ($categoriaFigura) {
            $productos = $categoriaFigura->figuras;
            $categoria = $categoriaFigura;
            $tipo = 'figura';
            return view('productos.categoria', compact('productos', 'categoria', 'tipo'));
        }

        // Intentar obtener productos de la categoría de merch
        $categoriaMerch = CategoriaMerch::find($id);
        if ($categoriaMerch) {
            $productos = $categoriaMerch->merchs;
            $categoria = $categoriaMerch;
            $tipo = 'merch';
            return view('productos.categoria', compact('productos', 'categoria', 'tipo'));
        }

        // Si no se encuentra la categoría
        abort(404, 'Categoría no encontrada');
    }
}
