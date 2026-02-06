<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Validar que las claves de Stripe estén configuradas
        $stripeSecret = config('stripe.secret');
        
        if (empty($stripeSecret)) {
            \Log::error('STRIPE_SECRET no está configurada en el archivo .env');
            abort(500, 'Error de configuración: Las claves de Stripe no están configuradas. Por favor, configura STRIPE_KEY y STRIPE_SECRET en tu archivo .env');
        }
        
        Stripe::setApiKey($stripeSecret);
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
        } catch (\Stripe\Exception\AuthenticationException $e) {
            \Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error de autenticación con Stripe. Verifica que las claves STRIPE_KEY y STRIPE_SECRET estén correctamente configuradas en el archivo .env'
            ], 500);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error de Stripe API: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Error al crear sesión de pago: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
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
            $session = StripeSession::retrieve([
                'id' => $sessionId,
                'expand' => ['customer', 'line_items', 'payment_intent']
            ]);
            
            if ($session->payment_status === 'paid') {
                // Verificar si ya existe un pedido con este session_id
                $pedidoExistente = Pedido::where('stripe_session_id', $sessionId)->first();
                
                if (!$pedidoExistente) {
                    // Guardar el pedido
                    $carrito = session()->get('carrito', []);
                    if (!empty($carrito)) {
                        $this->guardarPedido($session, $carrito);
                    }
                }
                
                // Limpiar el carrito después del pago exitoso
                session()->forget('carrito');
                
                return view('checkout.exito', [
                    'session' => $session,
                    'email' => $session->customer_details->email ?? null,
                    'pedido' => $pedidoExistente ?? Pedido::where('stripe_session_id', $sessionId)->first(),
                ]);
            }
            
            return redirect()->route('checkout.index')
                ->with('error', 'El pago no se completó correctamente');
                
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Error al verificar el pago con Stripe: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error general en checkout: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Error al verificar el pago: ' . $e->getMessage());
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

    /**
     * Guardar el pedido en la base de datos
     */
    private function guardarPedido($session, $carrito)
    {
        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        $subtotal = 0;
        foreach ($productos as $item) {
            $subtotal += $item['producto']->precio * $item['cantidad'];
        }
        
        $impuesto = $subtotal * 0.21;
        $total = $subtotal + $impuesto;

        // Formatear dirección de envío
        $direccionEnvio = null;
        if (isset($session->shipping_details->address)) {
            $addr = $session->shipping_details->address;
            $direccionEnvio = implode(', ', array_filter([
                $addr->line1 ?? '',
                $addr->line2 ?? '',
                $addr->city ?? '',
                $addr->state ?? '',
                $addr->postal_code ?? '',
                $addr->country ?? ''
            ]));
        }

        // Formatear dirección de facturación
        $direccionFacturacion = null;
        if (isset($session->customer_details->address)) {
            $addr = $session->customer_details->address;
            $direccionFacturacion = implode(', ', array_filter([
                $addr->line1 ?? '',
                $addr->line2 ?? '',
                $addr->city ?? '',
                $addr->state ?? '',
                $addr->postal_code ?? '',
                $addr->country ?? ''
            ]));
        }

        // Obtener el ID del payment intent
        $paymentIntentId = null;
        if ($session->payment_intent) {
            // Si es un objeto, obtener el ID, si es un string, usarlo directamente
            $paymentIntentId = is_object($session->payment_intent) 
                ? $session->payment_intent->id 
                : $session->payment_intent;
        }

        // Crear el pedido
        $pedido = Pedido::create([
            'user_id' => auth()->id(),
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $paymentIntentId,
            'numero_pedido' => Pedido::generarNumeroPedido(),
            'estado' => 'procesando',
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $total,
            'email_cliente' => $session->customer_details->email ?? '',
            'nombre_cliente' => $session->customer_details->name ?? null,
            'direccion_envio' => $direccionEnvio,
            'direccion_facturacion' => $direccionFacturacion,
        ]);

        // Guardar los items del pedido
        foreach ($productos as $item) {
            $productoType = match($item['tipo']) {
                'manga' => Manga::class,
                'figura' => Figura::class,
                'merch' => Merch::class,
                default => null
            };

            if ($productoType) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto']->id,
                    'producto_type' => $productoType,
                    'nombre_producto' => $item['producto']->nombre,
                    'precio_unitario' => $item['producto']->precio,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['producto']->precio * $item['cantidad'],
                ]);
            }
        }

        return $pedido;
    }
}
