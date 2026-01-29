<?php

namespace Database\Seeders;

use App\Models\CategoriaFigura;
use Illuminate\Database\Seeder;

class CategoriaFiguraSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'One Piece'],        // ID 1
            ['nombre' => 'Naruto'],           // ID 2
            ['nombre' => 'Dragon Ball'],      // ID 3
            ['nombre' => 'Demon Slayer'],     // ID 4
            ['nombre' => 'My Hero Academia'], // ID 5
            ['nombre' => 'Attack on Titan'],  // ID 6
            ['nombre' => 'Jujutsu Kaisen'],   // ID 7
            ['nombre' => 'Chainsaw Man'],     // ID 8
            ['nombre' => 'Bleach'],           // ID 9
        ];

        foreach ($categorias as $categoria) {
            CategoriaFigura::create($categoria);
        }
    }
}
