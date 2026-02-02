<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use App\Models\User;

/**
 * DashboardController
 * 
 * Controlador principal del panel de administración.
 * Muestra estadísticas generales y resumen de la tienda.
 */
class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal del admin.
     * 
     * Recopila estadísticas de:
     * - Total de productos por tipo
     * - Productos con stock bajo
     * - Total de usuarios
     * - Últimos productos añadidos
     */
    public function index()
    {
        // Contadores de productos
        $stats = [
            'total_mangas' => Manga::count(),
            'total_figuras' => Figura::count(),
            'total_merch' => Merch::count(),
            'total_usuarios' => User::where('is_admin', false)->count(),
        ];

        // Productos con stock bajo (menos de 10 unidades)
        $stockBajo = [
            'mangas' => Manga::where('stock', '<', 10)->get(),
            'figuras' => Figura::where('stock', '<', 10)->get(),
        ];

        // Últimos productos añadidos
        $ultimosProductos = Manga::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'stockBajo', 'ultimosProductos'));
    }
}
