<?php

namespace Database\Seeders;

use App\Models\Merch;
use Illuminate\Database\Seeder;

class MerchSeeder extends Seeder
{
    public function run(): void
    {
        $merchs = [
            [
                'nombre' => 'Camiseta Naruto Uzumaki',
                'descripcion' => 'Camiseta 100% algodón con diseño de Naruto',
                'precio' => 24.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 1 // Camisetas
            ],
            [
                'nombre' => 'Camiseta One Piece Luffy',
                'descripcion' => 'Camiseta oficial de Luffy',
                'precio' => 24.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 1
            ],
            [
                'nombre' => 'Sudadera Dragon Ball',
                'descripcion' => 'Sudadera con Goku Super Saiyan',
                'precio' => 39.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 2 // Sudaderas
            ],
            [
                'nombre' => 'Taza Attack on Titan',
                'descripcion' => 'Taza cerámica de Attack on Titan',
                'precio' => 12.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 3 // Tazas
            ],
            [
                'nombre' => 'Poster Demon Slayer',
                'descripcion' => 'Poster tamaño A2',
                'precio' => 9.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 4 // Posters
            ],
            [
                'nombre' => 'Llavero Jujutsu Kaisen',
                'descripcion' => 'Llavero acrílico',
                'precio' => 6.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 5 // Llaveros
            ],
            [
                'nombre' => 'Mochila One Piece',
                'descripcion' => 'Mochila escolar de One Piece',
                'precio' => 49.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 6 // Mochilas
            ],
            [
                'nombre' => 'Camiseta Chainsaw Man',
                'descripcion' => 'Camiseta con diseño de Chainsaw Man',
                'precio' => 25.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 1
            ],
            [
                'nombre' => 'Sudadera My Hero Academia',
                'descripcion' => 'Sudadera con Deku',
                'precio' => 42.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 2
            ],
            [
                'nombre' => 'Taza Sailor Moon',
                'descripcion' => 'Taza mágica Sailor Moon',
                'precio' => 13.95,
                'disponibilidad' => true,
                'categoria_merch_id' => 3
            ],
        ];

        foreach ($merchs as $merch) {
            Merch::create($merch);
        }
    }
}
