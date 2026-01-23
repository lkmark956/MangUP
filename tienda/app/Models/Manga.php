<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    protected $table = 'mangas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'disponibilidad',
        'autor',
        'editorial',
        'fecha_publicacion',
        'numero_paginas',
        'isbn',
        'numero_tomo',
        'categoria_manga_id'
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'disponibilidad' => 'boolean',
        'precio' => 'decimal:2'
    ];

    /**
     * Relación: Un manga pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaManga::class, 'categoria_manga_id');
    }

    /**
     * Relación polimórfica: Un manga tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->morphMany(Imagen::class, 'imageable');
    }

    /**
     * Obtener la imagen principal
     */
    public function imagenPrincipal()
    {
        return $this->morphOne(Imagen::class, 'imageable')->where('es_principal', true);
    }
}
