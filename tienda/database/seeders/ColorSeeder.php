<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colores = [
            ['nombre' => 'Negro', 'hex' => '#000000'],
            ['nombre' => 'Blanco', 'hex' => '#FFFFFF'],
            ['nombre' => 'Gris', 'hex' => '#808080'],
            ['nombre' => 'Rojo', 'hex' => '#FF0000'],
            ['nombre' => 'Azul', 'hex' => '#0000FF'],
            ['nombre' => 'Verde', 'hex' => '#008000'],
            ['nombre' => 'Amarillo', 'hex' => '#FFFF00'],
            ['nombre' => 'Rosa', 'hex' => '#FFC0CB'],
            ['nombre' => 'Naranja', 'hex' => '#FFA500'],
            ['nombre' => 'Morado', 'hex' => '#800080'],
        ];

        foreach ($colores as $color) {
            Color::create($color);
        }
    }
}
