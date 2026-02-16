<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pedido;

/**
 * PedidoController - Panel de Administración
 * 
 * Este controlador maneja la gestión de pedidos:
 * - index(): Lista todos los pedidos con filtros
 * - show(): Muestra los detalles de un pedido
 * - updateEstado(): Actualiza el estado de un pedido
 */
class PedidoController extends Controller
{
    /**
     * Listar todos los pedidos
     */
    public function index(Request $request)
    {
        $query = Pedido::with('user', 'items');
        
        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Búsqueda por número de pedido o email
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_pedido', 'like', '%' . $buscar . '%')
                  ->orWhere('email_cliente', 'like', '%' . $buscar . '%')
                  ->orWhere('nombre_cliente', 'like', '%' . $buscar . '%');
            });
        }
        
        // Ordenamiento por fecha más reciente
        $pedidos = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Estados disponibles para el filtro
        $estados = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
        
        return view('admin.pedidos.index', compact('pedidos', 'estados'));
    }

    /**
     * Mostrar detalles de un pedido
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['user', 'items.producto']);
        
        $estados = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
        
        return view('admin.pedidos.show', compact('pedido', 'estados'));
    }

    /**
     * Actualizar el estado de un pedido
     */
    public function updateEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,entregado,cancelado'
        ]);
        
        $estadoAnterior = $pedido->estado;
        $nuevoEstado = $request->estado;
        
        // Si se cancela el pedido, restaurar stock
        if ($nuevoEstado === 'cancelado' && $estadoAnterior !== 'cancelado') {
            $pedido->restaurarStock();
        }
        
        // Si se reactiva un pedido cancelado, descontar stock
        if ($estadoAnterior === 'cancelado' && $nuevoEstado !== 'cancelado') {
            $pedido->descontarStock();
        }
        
        $pedido->update(['estado' => $nuevoEstado]);
        
        return redirect()->back()->with('success', "Estado del pedido actualizado a: {$nuevoEstado}");
    }
}
