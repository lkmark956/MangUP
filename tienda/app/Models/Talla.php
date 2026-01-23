<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;

    protected $table = 'tallas';

    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación: Una talla tiene muchas variantes de merch
     */
    public function merchVariantes()
    {
        return $this->hasMany(MerchVariante::class, 'talla_id');
    }
}
