<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merch extends Model
{
    use HasFactory;

    protected $table = 'merchs';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'disponibilidad',
        'categoria_merch_id'
    ];

    protected $casts = [
        'disponibilidad' => 'boolean',
        'precio' => 'decimal:2'
    ];

    /**
     * Relación: Un merch pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaMerch::class, 'categoria_merch_id');
    }

    /**
     * Relación: Un merch tiene muchas variantes (talla/color)
     */
    public function variantes()
    {
        return $this->hasMany(MerchVariante::class, 'merch_id');
    }

    /**
     * Relación polimórfica: Un merch tiene muchas imágenes
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

    /**
     * Obtener el stock total sumando todas las variantes
     */
    public function getStockTotalAttribute()
    {
        return $this->variantes->sum('stock');
    }
}
