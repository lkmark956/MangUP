<?php

namespace App\Http\Controllers;

use App\Models\CategoriaManga;
use App\Models\CategoriaFigura;
use App\Models\CategoriaMerch;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías
     */
    public function index()
    {
        // Obtener todas las categorías de cada tipo
        $categoriasManga = CategoriaManga::all();
        $categoriasFigura = CategoriaFigura::all();
        $categoriasMerch = CategoriaMerch::all();

        // Combinar todas las categorías con su tipo
        $categorias = collect()
            ->merge($categoriasManga->map(fn($c) => $c->setAttribute('tipo', 'manga')))
            ->merge($categoriasFigura->map(fn($c) => $c->setAttribute('tipo', 'figura')))
            ->merge($categoriasMerch->map(fn($c) => $c->setAttribute('tipo', 'merch')));

        return view('categorias.index', compact('categorias'));
    }
}
