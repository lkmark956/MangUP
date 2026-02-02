<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = [
        'user_id',
        'nombre',
        'calle',
        'numero',
        'piso',
        'puerta',
        'ciudad',
        'provincia',
        'codigo_postal',
        'pais',
        'es_default',
    ];

    protected $casts = [
        'es_default' => 'boolean',
    ];

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
