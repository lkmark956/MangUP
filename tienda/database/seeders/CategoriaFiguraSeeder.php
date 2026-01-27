<?php

namespace Database\Seeders;

use App\Models\CategoriaFigura;
use Illuminate\Database\Seeder;

class CategoriaFiguraSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'One Piece'],
            ['nombre' => 'Naruto'],
            ['nombre' => 'Dragon Ball'],
            ['nombre' => 'Demon Slayer'],
            ['nombre' => 'My Hero Academia'],
            ['nombre' => 'Attack on Titan'],
            ['nombre' => 'Jujutsu Kaisen'],
        ];

        foreach ($categorias as $categoria) {
            CategoriaFigura::create($categoria);
        }
    }
}
