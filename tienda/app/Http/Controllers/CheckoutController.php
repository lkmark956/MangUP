<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Oferta;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    // No necesitamos configurar Stripe en el constructor para Laravel 12+
    // La clave se pasa directamente en cada llamada a la API

    /**
     * Mostrar página de checkout
     * 
     * NOTA: Los precios de productos YA incluyen IVA (21%)
     * El desglose muestra la base imponible y el IVA contenido
     */
    public function index()
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')
                ->with('error', 'Tu carrito está vacío');
        }

        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        // Calcular total aplicando ofertas si existen
        // Los precios YA incluyen IVA
        $total = 0;
        foreach ($productos as &$item) {
            // Verificar si hay oferta para este producto
            $ofertaInfo = Oferta::obtenerMejorOferta($item['tipo'], $item['producto']->id, $item['producto']->precio);
            
            if ($ofertaInfo) {
                $item['precio_final'] = $ofertaInfo['precio_final'];
                $item['oferta_info'] = $ofertaInfo;
            } else {
                $item['precio_final'] = $item['producto']->precio;
                $item['oferta_info'] = null;
            }
            
            $total += $item['precio_final'] * $item['cantidad'];
        }
        unset($item);
        
        // Calcular desglose de IVA (el precio ya lo incluye)
        // Fórmula: Base = Total / 1.21, IVA = Total - Base
        $subtotal = round($total / 1.21, 2); // Base imponible (sin IVA)
        $impuesto = round($total - $subtotal, 2); // IVA incluido en los precios

        return view('checkout.index', [
            'productos' => $productos,
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $total
        ]);
    }

    /**
     * Crear sesión de pago con Stripe Checkout
     * 
     * Los precios enviados a Stripe YA incluyen IVA
     */
    public function crearSesion(Request $request)
    {
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return response()->json(['error' => 'Carrito vacío'], 400);
        }

        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        // Validar stock y calcular precios con ofertas
        foreach ($productos as &$item) {
            // Para Merch con variante, usar el stock de la variante
            if ($item['tipo'] === 'merch' && isset($item['variante'])) {
                $stockDisponible = $item['variante']->stock ?? 0;
                $nombreProducto = $item['producto']->nombre . ' (' . 
                    ($item['variante']->talla->nombre ?? '') . ' - ' . 
                    ($item['variante']->color->nombre ?? '') . ')';
            } else {
                $stockDisponible = $item['producto']->stock ?? 0;
                $nombreProducto = $item['producto']->nombre;
            }
            
            if ($stockDisponible < $item['cantidad']) {
                return response()->json([
                    'error' => "No hay suficiente stock de '{$nombreProducto}'. Solo quedan {$stockDisponible} unidades."
                ], 400);
            }
            
            // Aplicar oferta si existe
            $ofertaInfo = Oferta::obtenerMejorOferta($item['tipo'], $item['producto']->id, $item['producto']->precio);
            if ($ofertaInfo) {
                $item['precio_final'] = $ofertaInfo['precio_final'];
                $item['oferta_info'] = $ofertaInfo;
            } else {
                $item['precio_final'] = $item['producto']->precio;
                $item['oferta_info'] = null;
            }
        }
        unset($item);
        
        $lineItems = [];

        foreach ($productos as $item) {
            $nombreProducto = $item['producto']->nombre;
            $descripcionProducto = ucfirst($item['tipo']) . ' - ' . ($item['producto']->categoria->nombre ?? 'Sin categoría');
            
            // Añadir información de oferta a la descripción si existe
            if (isset($item['oferta_info']) && $item['oferta_info']) {
                $descripcionProducto .= ' | ' . $item['oferta_info']['oferta']->nombre;
            }
            
            // Añadir información de variante a la descripción si existe
            if ($item['tipo'] === 'merch' && isset($item['variante'])) {
                $descripcionProducto .= ' | ' . 
                    ($item['variante']->talla->nombre ?? '') . ' - ' . 
                    ($item['variante']->color->nombre ?? '');
            }
            
            // Usar precio_final (con oferta aplicada si existe)
            // El precio ya incluye IVA
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('stripe.currency', 'eur'),
                    'product_data' => [
                        'name' => $nombreProducto,
                        'description' => $descripcionProducto,
                    ],
                    'unit_amount' => (int)($item['precio_final'] * 100),
                ],
                'quantity' => $item['cantidad'],
            ];
        }

        // Validar que las claves de Stripe estén configuradas
        $stripeSecret = config('services.stripe.secret');
        
        if (empty($stripeSecret)) {
            \Log::error('STRIPE_SECRET no está configurada en el archivo .env');
            return response()->json([
                'error' => 'Error de configuración: Las claves de Stripe no están configuradas. Por favor, configura STRIPE_KEY y STRIPE_SECRET en tu archivo .env'
            ], 500);
        }

        try {
            /** @var \Stripe\Checkout\Session $session */
            $session = StripeSession::create(
                [
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
                ],
                ['api_key' => $stripeSecret] // 👈 Clave aquí, evita estado global
            );

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
            $stripeSecret = config('services.stripe.secret');
            
            /** @var \Stripe\Checkout\Session $session */
            $session = StripeSession::retrieve(
                $sessionId,
                ['api_key' => $stripeSecret]
            );
            
            // Expandir datos relacionados
            $session = StripeSession::retrieve(
                $sessionId,
                [
                    'api_key' => $stripeSecret,
                    'expand' => ['customer', 'line_items', 'payment_intent']
                ]
            );
            
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
                $itemData = [
                    'producto' => $producto,
                    'cantidad' => $item['cantidad'],
                    'tipo' => $item['tipo']
                ];
                
                // Añadir variante si existe
                if ($item['tipo'] === 'merch' && isset($item['variante_id'])) {
                    $variante = \App\Models\MerchVariante::with(['talla', 'color'])->find($item['variante_id']);
                    $itemData['variante'] = $variante;
                    $itemData['variante_id'] = $item['variante_id'];
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
            'manga' => Manga::with('categoria')->find($id),
            'figura' => Figura::with('categoria')->find($id),
            'merch' => Merch::with('categoria')->find($id),
            default => null
        };
    }

    /**
     * Guardar el pedido en la base de datos
     * 
     * Los precios guardados incluyen ofertas aplicadas
     * El IVA ya está incluido en los precios (se calcula el desglose)
     */
    private function guardarPedido($session, $carrito)
    {
        $productos = $this->obtenerProductosDelCarrito($carrito);
        
        // Calcular total con ofertas aplicadas
        $total = 0;
        foreach ($productos as &$item) {
            // Aplicar oferta si existe
            $ofertaInfo = Oferta::obtenerMejorOferta($item['tipo'], $item['producto']->id, $item['producto']->precio);
            if ($ofertaInfo) {
                $item['precio_final'] = $ofertaInfo['precio_final'];
                $item['oferta_info'] = $ofertaInfo;
            } else {
                $item['precio_final'] = $item['producto']->precio;
                $item['oferta_info'] = null;
            }
            
            $total += $item['precio_final'] * $item['cantidad'];
        }
        unset($item);
        
        // Calcular desglose de IVA (el precio ya lo incluye)
        // Fórmula: Base = Total / 1.21, IVA = Total - Base
        $subtotal = round($total / 1.21, 2); // Base imponible
        $impuesto = round($total - $subtotal, 2); // IVA incluido

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
                $varianteDetalle = null;
                if ($item['tipo'] === 'merch' && isset($item['variante'])) {
                    $varianteDetalle = ($item['variante']->talla->nombre ?? '') . ' - ' . 
                                      ($item['variante']->color->nombre ?? '');
                }
                
                // Usar precio_final (incluye oferta si aplica)
                $precioFinal = $item['precio_final'] ?? $item['producto']->precio;
                
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto']->id,
                    'producto_type' => $productoType,
                    'variante_id' => $item['variante_id'] ?? null,
                    'variante_detalle' => $varianteDetalle,
                    'nombre_producto' => $item['producto']->nombre,
                    'precio_unitario' => $precioFinal,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $precioFinal * $item['cantidad'],
                ]);
            }
        }

        // Descontar el stock usando el método del modelo
        $pedido->descontarStock();

        return $pedido;
    }
}
