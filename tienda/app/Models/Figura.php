<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Figura extends Model
{
    use HasFactory;

    protected $table = 'figuras';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'disponibilidad',
        'categoria_figura_id'
    ];

    protected $casts = [
        'disponibilidad' => 'boolean',
        'precio' => 'decimal:2'
    ];

    /**
     * Relación: Una figura pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaFigura::class, 'categoria_figura_id');
    }

    /**
     * Relación polimórfica: Una figura tiene muchas imágenes
     */
    public function imagenes()
    {
        return $this->morphMany(Imagen::class, 'imageable');
    }

    /**
     * Obtener la imagen principal (relación)
     */
    public function imagenPrincipal()
    {
        return $this->morphOne(Imagen::class, 'imageable')->where('es_principal', true);
    }

    /**
     * Accessor para obtener la URL de la imagen principal
     */
    public function getImagenPrincipalAttribute()
    {
        $imagen = $this->imagenes()->where('es_principal', true)->first();
        if ($imagen) {
            // La ruta ya debería venir con el formato correcto desde la BD
            // Ej: productos/figuras/imagen.jpg
            return asset($imagen->ruta);
        }
        return asset('images/placeholder.svg');
    }
}
