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
}
