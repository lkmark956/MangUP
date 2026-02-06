<?php

namespace Database\Seeders;

use App\Models\Merch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('merchs')->delete();
        $merchs = [
            // Solo los 5 productos que tienen imágenes reales
            [
                'nombre' => 'Camiseta Naruto Uzumaki',
                'descripcion' => 'Camiseta 100% algodón con diseño exclusivo de Naruto Uzumaki en modo Sabio de los Seis Caminos. Estampado de alta calidad resistente a múltiples lavados. Disponible en varias tallas.',
                'precio' => 24.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 1 // Camisetas
            ],
            [
                'nombre' => 'Camiseta One Piece Luffy',
                'descripcion' => 'Camiseta oficial de Monkey D. Luffy con el diseño del Gear 5. Material 100% algodón premium. Perfecta para fans de la serie.',
                'precio' => 24.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 1
            ],
            [
                'nombre' => 'Sudadera Dragon Ball - Goku',
                'descripcion' => 'Sudadera con capucha y diseño de Goku Super Saiyan Blue. Interior afelpado para máximo confort. Bolsillo canguro frontal.',
                'precio' => 39.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 2 // Sudaderas
            ],
            [
                'nombre' => 'Taza Attack on Titan',
                'descripcion' => 'Taza de cerámica de 330ml con diseño de las Alas de la Libertad del Cuerpo de Exploración. Apta para microondas y lavavajillas.',
                'precio' => 12.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 3 // Tazas
            ],
            [
                'nombre' => 'Poster Demon Slayer',
                'descripcion' => 'Poster premium tamaño A2 (42x59.4cm) de Demon Slayer: Kimetsu no Yaiba. Papel de alta calidad 200g/m². Incluye los personajes principales.',
                'precio' => 9.95,
                'disponibilidad' => false,
                'categoria_merch_id' => 4 // Posters
            ],
        ];

        foreach ($merchs as $merch) {
            Merch::create($merch);
        }
    }
}
