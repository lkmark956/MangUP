<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'colores';

    protected $fillable = [
        'nombre',
        'codigo_hex'
    ];

    /**
     * Relación: Un color tiene muchas variantes de merch
     */
    public function merchVariantes()
    {
        return $this->hasMany(MerchVariante::class, 'color_id');
    }
}
