<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Mostrar todos los pedidos del usuario
     */
    public function index()
    {
        $pedidos = Pedido::where('user_id', Auth::id())
            ->with('items.producto')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Mostrar detalle de un pedido específico
     */
    public function show($id)
    {
        $pedido = Pedido::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('items.producto')
            ->firstOrFail();

        return view('pedidos.show', compact('pedido'));
    }
}
