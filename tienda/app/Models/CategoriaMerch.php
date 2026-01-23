<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaMerch extends Model
{
    use HasFactory;

    protected $table = 'categorias_merch';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen'
    ];

    /**
     * Relación: Una categoría tiene muchos merchs
     */
    public function merchs()
    {
        return $this->hasMany(Merch::class, 'categoria_merch_id');
    }
}
