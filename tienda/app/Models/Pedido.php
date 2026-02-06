<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'numero_pedido',
        'estado',
        'subtotal',
        'impuesto',
        'total',
        'email_cliente',
        'nombre_cliente',
        'direccion_envio',
        'direccion_facturacion',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los items del pedido
     */
    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    /**
     * Generar un número de pedido único
     */
    public static function generarNumeroPedido(): string
    {
        $fecha = now()->format('Ymd');
        $ultimo = static::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $secuencial = $ultimo ? (intval(substr($ultimo->numero_pedido, -4)) + 1) : 1;
        
        return $fecha . '-' . str_pad($secuencial, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Descontar el stock de los productos del pedido
     */
    public function descontarStock(): void
    {
        // Cargar todas las relaciones necesarias de una vez para evitar N+1
        $this->load(['items.producto', 'items.variante']);
        
        foreach ($this->items as $item) {
            // Si el item tiene una variante (producto Merch), descontar del stock de la variante
            if ($item->variante_id) {
                $variante = $item->variante;
                if ($variante) {
                    $nuevoStock = max(0, $variante->stock - $item->cantidad);
                    $variante->update(['stock' => $nuevoStock]);
                    
                    // Actualizar disponibilidad del producto padre basándose en stock total de variantes
                    $producto = $item->producto;
                    if ($producto) {
                        $stockTotal = $producto->variantes()->sum('stock');
                        $producto->update(['disponibilidad' => $stockTotal > 0]);
                    }
                }
            } else {
                // Descuento normal para Manga y Figura
                $producto = $item->producto;
                
                if ($producto && isset($producto->stock)) {
                    $nuevoStock = max(0, $producto->stock - $item->cantidad);
                    $producto->update([
                        'stock' => $nuevoStock,
                        'disponibilidad' => $nuevoStock > 0
                    ]);
                }
            }
        }
    }

    /**
     * Restaurar el stock de los productos del pedido
     */
    public function restaurarStock(): void
    {
        // Cargar todas las relaciones necesarias de una vez para evitar N+1
        $this->load(['items.producto', 'items.variante']);
        
        foreach ($this->items as $item) {
            // Si el item tiene una variante, restaurar stock de la variante
            if ($item->variante_id) {
                $variante = $item->variante;
                if ($variante) {
                    $nuevoStock = $variante->stock + $item->cantidad;
                    $variante->update(['stock' => $nuevoStock]);
                    
                    // Actualizar disponibilidad del producto padre
                    $producto = $item->producto;
                    if ($producto) {
                        $producto->update(['disponibilidad' => true]);
                    }
                }
            } else {
                // Restauración normal para Manga y Figura
                $producto = $item->producto;
                
                if ($producto && isset($producto->stock)) {
                    $nuevoStock = $producto->stock + $item->cantidad;
                    $producto->update([
                        'stock' => $nuevoStock,
                        'disponibilidad' => true
                    ]);
                }
            }
        }
    }

    /**
     * Verificar si hay stock suficiente para todos los items
     */
    public function verificarStock(): bool
    {
        // Cargar todas las relaciones necesarias de una vez
        $this->load(['items.producto', 'items.variante']);
        
        foreach ($this->items as $item) {
            // Verificar stock de variante si existe
            if ($item->variante_id) {
                $variante = $item->variante;
                if (!$variante || $variante->stock < $item->cantidad) {
                    return false;
                }
            } else {
                // Verificación normal para Manga y Figura
                $producto = $item->producto;
                
                if (!$producto || !isset($producto->stock)) {
                    return false;
                }
                
                if ($producto->stock < $item->cantidad) {
                    return false;
                }
            }
        }
        
        return true;
    }
}
