<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchVariante extends Model
{
    use HasFactory;

    protected $table = 'merch_variantes';

    protected $fillable = [
        'merch_id',
        'talla_id',
        'color_id',
        'stock'
    ];

    /**
     * Relación: Una variante pertenece a un merch
     */
    public function merch()
    {
        return $this->belongsTo(Merch::class, 'merch_id');
    }

    /**
     * Relación: Una variante tiene una talla
     */
    public function talla()
    {
        return $this->belongsTo(Talla::class, 'talla_id');
    }

    /**
     * Relación: Una variante tiene un color
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
}
