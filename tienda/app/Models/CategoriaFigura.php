<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaFigura extends Model
{
    use HasFactory;

    protected $table = 'categorias_figura';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen'
    ];

    /**
     * Relación: Una categoría tiene muchas figuras
     */
    public function figuras()
    {
        return $this->hasMany(Figura::class, 'categoria_figura_id');
    }
}
