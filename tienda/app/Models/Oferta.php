<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'tipo_descuento',
        'valor_descuento',
        'aplica_a',
        'producto_id',
        'tipo_producto',
        'fecha_inicio',
        'fecha_fin',
        'activa',
    ];

    protected $casts = [
        'valor_descuento' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'activa' => 'boolean',
    ];

    /**
     * Verificar si la oferta está vigente
     */
    public function estaVigente(): bool
    {
        $hoy = now()->startOfDay();
        return $this->activa 
            && $this->fecha_inicio <= $hoy 
            && $this->fecha_fin >= $hoy;
    }

    /**
     * Obtener todas las ofertas activas y vigentes
     */
    public static function vigentes()
    {
        $hoy = now()->toDateString();
        return static::where('activa', true)
            ->where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy);
    }

    /**
     * Calcular el precio con descuento
     */
    public function calcularPrecioConDescuento(float $precioOriginal): float
    {
        if ($this->tipo_descuento === 'porcentaje') {
            return $precioOriginal - ($precioOriginal * $this->valor_descuento / 100);
        }
        
        // cantidad_fija
        return max(0, $precioOriginal - $this->valor_descuento);
    }

    /**
     * Obtener el producto específico si aplica
     */
    public function producto()
    {
        if ($this->aplica_a !== 'producto_especifico' || !$this->producto_id || !$this->tipo_producto) {
            return null;
        }

        $modelo = match($this->tipo_producto) {
            'manga' => Manga::class,
            'figura' => Figura::class,
            'merch' => Merch::class,
            default => null,
        };

        return $modelo ? $modelo::find($this->producto_id) : null;
    }

    /**
     * Verificar si la oferta aplica a un producto
     */
    public function aplicaAProducto(string $tipoProducto, int $productoId): bool
    {
        if (!$this->estaVigente()) {
            return false;
        }

        // Si aplica a todos
        if ($this->aplica_a === 'todos') {
            return true;
        }

        // Si aplica a una categoría de productos
        if ($this->aplica_a === $tipoProducto) {
            return true;
        }

        // Si aplica a un producto específico
        if ($this->aplica_a === 'producto_especifico') {
            return $this->tipo_producto === $tipoProducto && $this->producto_id === $productoId;
        }

        return false;
    }

    /**
     * Obtener la mejor oferta para un producto
     */
    public static function obtenerMejorOferta(string $tipoProducto, int $productoId, float $precioOriginal): ?array
    {
        $ofertas = static::vigentes()->get();
        
        $mejorOferta = null;
        $mejorPrecio = $precioOriginal;

        foreach ($ofertas as $oferta) {
            if ($oferta->aplicaAProducto($tipoProducto, $productoId)) {
                $nuevoPrecio = $oferta->calcularPrecioConDescuento($precioOriginal);
                if ($nuevoPrecio < $mejorPrecio) {
                    $mejorPrecio = $nuevoPrecio;
                    $mejorOferta = $oferta;
                }
            }
        }

        if ($mejorOferta) {
            return [
                'oferta' => $mejorOferta,
                'precio_original' => $precioOriginal,
                'precio_final' => $mejorPrecio,
                'ahorro' => $precioOriginal - $mejorPrecio,
            ];
        }

        return null;
    }
}
