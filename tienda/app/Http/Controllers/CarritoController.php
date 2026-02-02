<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;

class CarritoController extends Controller
{
    /**
     * Mostrar el carrito
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);
        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        $subtotal = 0;
        $impuesto = 0;
        
        foreach ($productos as $item) {
            $subtotal += $item['producto']->precio * $item['cantidad'];
        }
        
        // Calcular impuesto (21% IVA)
        $impuesto = $subtotal * 0.21;
        $total = $subtotal + $impuesto;
        
        return view('carrito.index', [
            'productos' => $productos,
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $total,
            'cant_items' => array_sum(array_column($carrito, 'cantidad'))
        ]);
    }

    /**
     * Agregar producto al carrito
     */
    public function agregar(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'tipo' => 'required|in:manga,figura,merch',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = $this->obtenerProducto($request->id, $request->tipo);
        
        if (!$producto) {
            return back()->with('error', 'Producto no encontrado');
        }

        if ($producto->stock <= 0) {
            return back()->with('error', 'Producto agotado');
        }

        $carrito = session()->get('carrito', []);
        $clave = "{$request->tipo}_{$request->id}";

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$clave] = [
                'id' => $request->id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad
            ];
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto agregado al carrito');
    }

    /**
     * Actualizar cantidad de un producto
     */
    public function actualizar(Request $request, $tipo, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $carrito = session()->get('carrito', []);
        $clave = "{$tipo}_{$id}";

        if (!isset($carrito[$clave])) {
            return back()->with('error', 'Producto no encontrado en el carrito');
        }

        $producto = $this->obtenerProducto($id, $tipo);
        
        if ($request->cantidad > $producto->stock) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        $carrito[$clave]['cantidad'] = $request->cantidad;
        session()->put('carrito', $carrito);

        return back()->with('success', 'Cantidad actualizada');
    }

    /**
     * Eliminar producto del carrito
     */
    public function eliminar($tipo, $id)
    {
        $carrito = session()->get('carrito', []);
        $clave = "{$tipo}_{$id}";

        if (isset($carrito[$clave])) {
            unset($carrito[$clave]);
            session()->put('carrito', $carrito);
        }

        return back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Vaciar carrito
     */
    public function vaciar()
    {
        session()->forget('carrito');
        return back()->with('success', 'Carrito vaciado');
    }

    /**
     * Obtener productos del carrito con detalles
     */
    private function obtenerProductosDelCarrito($carrito)
    {
        $productos = [];

        foreach ($carrito as $item) {
            $producto = $this->obtenerProducto($item['id'], $item['tipo']);
            
            if ($producto) {
                $productos[] = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad'],
                    'tipo' => $item['tipo']
                ];
            }
        }

        return $productos;
    }

    /**
     * Obtener un producto específico
     */
    private function obtenerProducto($id, $tipo)
    {
        return match($tipo) {
            'manga' => Manga::find($id),
            'figura' => Figura::find($id),
            'merch' => Merch::find($id),
            default => null
        };
    }
}
