<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    protected $fillable = [
        'ruta',
        'es_principal',
        'orden',
        'imageable_id',
        'imageable_type'
    ];

    protected $casts = [
        'es_principal' => 'boolean'
    ];

    /**
     * Relación polimórfica: Una imagen pertenece a un manga, figura o merch
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
