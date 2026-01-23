<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaManga extends Model
{
    use HasFactory;

    protected $table = 'categorias_manga';

    protected $fillable = [
        'nombre',
        'descripcion',
        'imagen'
    ];

    /**
     * Relación: Una categoría tiene muchos mangas
     */
    public function mangas()
    {
        return $this->hasMany(Manga::class, 'categoria_manga_id');
    }
}
