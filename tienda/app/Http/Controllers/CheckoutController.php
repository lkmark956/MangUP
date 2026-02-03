<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Mostrar página de checkout
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        $subtotal = 0;
        foreach ($productos as $item) {
            $subtotal += $item['producto']->precio * $item['cantidad'];
        }
        
        $impuesto = $subtotal * 0.21;
        $total = $subtotal + $impuesto;

        return view('checkout.index', [
            'productos' => $productos,
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $total
        ]);
    }

    /**
     * Crear sesión de pago con Stripe Checkout
     */
    public function crearSesion(Request $request)
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        $productos = $this->obtenerProductosDelCarrito($carrito);
        $lineItems = [];

        foreach ($productos as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('stripe.currency', 'eur'),
                    'product_data' => [
                        'name' => $item['producto']->nombre,
                        'description' => ucfirst($item['tipo']) . ' - ' . ($item['producto']->categoria->nombre ?? 'Sin categoría'),
                    ],
                    'unit_amount' => (int)($item['producto']->precio * 100),
                ],
                'quantity' => $item['cantidad'],
            ];
        }

        try {
            /** @var \Stripe\Checkout\Session $session */
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.exito') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancelado'),
                'locale' => 'es',
                'metadata' => [
                    'user_id' => auth()->id() ?? 'guest',
                ],
                'shipping_address_collection' => [
                    'allowed_countries' => ['ES', 'PT', 'FR', 'DE', 'IT', 'GB'],
                ],
                'billing_address_collection' => 'required',
            ]);

            return response()->json([
                'id' => $session->id, // @phpstan-ignore-line
                'url' => $session->url // @phpstan-ignore-line
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear la sesión de pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de éxito después del pago
     */
    public function exito(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Sesión de pago inválida');
        }

        try {
            /** @var \Stripe\Checkout\Session $session */
            $session = StripeSession::retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                // Limpiar el carrito después del pago exitoso
                session()->forget('carrito');
                
                return view('checkout.exito', [
                    'session' => $session,
                    'email' => $session->customer_details->email ?? null,
                ]);
            }
            
            return redirect()->route('checkout.index')
                ->with('error', 'El pago no se completó correctamente');
                
        } catch (\Exception $e) {
            return redirect()->route('home')
                ->with('error', 'Error al verificar el pago');
        }
    }

    /**
     * Página de cancelación
     */
    public function cancelado()
    {
        return view('checkout.cancelado');
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
            'manga' => Manga::with('categoria')->find($id),
            'figura' => Figura::with('categoria')->find($id),
            'merch' => Merch::with('categoria')->find($id),
            default => null
        };
    }
}
