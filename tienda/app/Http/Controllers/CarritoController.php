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
            'cantidad' => 'required|integer|min:1',
            'variante_id' => 'nullable|integer'
        ]);

        $producto = $this->obtenerProducto($request->id, $request->tipo);
        
        if (!$producto) {
            return back()->with('error', 'Producto no encontrado');
        }

        // Para Merch, obtener el stock de la variante específica
        if ($request->tipo === 'merch' && $request->variante_id) {
            $variante = \App\Models\MerchVariante::find($request->variante_id);
            if (!$variante) {
                return back()->with('error', 'Variante no encontrada');
            }
            $stockDisponible = $variante->stock ?? 0;
        } else {
            $stockDisponible = $producto->stock ?? 0;
        }
        
        if ($stockDisponible <= 0) {
            return back()->with('error', 'Producto agotado');
        }

        $carrito = session()->get('carrito', []);
        // Para Merch con variante, incluir variante_id en la clave
        $clave = $request->tipo === 'merch' && $request->variante_id 
            ? "{$request->tipo}_{$request->id}_{$request->variante_id}"
            : "{$request->tipo}_{$request->id}";
        
        // Calcular la cantidad total que se tendría en el carrito
        $cantidadActual = $carrito[$clave]['cantidad'] ?? 0;
        $cantidadTotal = $cantidadActual + $request->cantidad;
        
        // Validar que no exceda el stock disponible
        if ($cantidadTotal > $stockDisponible) {
            return back()->with('error', "No hay suficiente stock. Solo hay {$stockDisponible} unidades disponibles.");
        }

        if (isset($carrito[$clave])) {
            $carrito[$clave]['cantidad'] = $cantidadTotal;
        } else {
            $carrito[$clave] = [
                'id' => $request->id,
                'tipo' => $request->tipo,
                'cantidad' => $request->cantidad,
                'variante_id' => $request->variante_id ?? null
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
        
        // Buscar la clave correcta (puede incluir variante_id)
        $clave = null;
        foreach ($carrito as $key => $item) {
            if (str_starts_with($key, "{$tipo}_{$id}")) {
                $clave = $key;
                break;
            }
        }

        if (!$clave || !isset($carrito[$clave])) {
            return back()->with('error', 'Producto no encontrado en el carrito');
        }

        $producto = $this->obtenerProducto($id, $tipo);
        $varianteId = $carrito[$clave]['variante_id'] ?? null;
        
        // Obtener el stock correcto
        if ($tipo === 'merch' && $varianteId) {
            $variante = \App\Models\MerchVariante::find($varianteId);
            $stockDisponible = $variante ? $variante->stock : 0;
        } else {
            $stockDisponible = $producto->stock ?? 0;
        }
        
        if ($request->cantidad > $stockDisponible) {
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
        
        // Buscar la clave correcta (puede incluir variante_id)
        $clave = null;
        foreach ($carrito as $key => $item) {
            if (str_starts_with($key, "{$tipo}_{$id}")) {
                $clave = $key;
                break;
            }
        }

        if ($clave && isset($carrito[$clave])) {
            unset($carrito[$clave]);
            session()->put('carrito', $carrito);
            return back()->with('success', 'Producto eliminado del carrito');
        }

        return back()->with('error', 'Producto no encontrado en el carrito');
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
                $itemData = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad'],
                    'tipo' => $item['tipo']
                ];
                
                // Añadir variante si existe
                if ($item['tipo'] === 'merch' && isset($item['variante_id'])) {
                    $variante = \App\Models\MerchVariante::with(['talla', 'color'])->find($item['variante_id']);
                    $itemData['variante'] = $variante;
                }
                
                $productos[] = $itemData;
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
