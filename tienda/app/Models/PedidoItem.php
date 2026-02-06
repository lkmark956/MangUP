<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'producto_type',
        'variante_id',
        'variante_detalle',
        'nombre_producto',
        'precio_unitario',
        'cantidad',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    /**
     * Relación con el pedido
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relación polimórfica con el producto (Manga, Figura o Merch)
     */
    public function producto(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relación con la variante (solo para productos Merch)
     */
    public function variante(): BelongsTo
    {
        return $this->belongsTo(MerchVariante::class, 'variante_id');
    }
}
