<?php

namespace Database\Seeders;

use App\Models\Figura;
use Illuminate\Database\Seeder;

class FiguraSeeder extends Seeder
{
    public function run(): void
    {
        $figuras = [
            ['nombre' => 'Figura Luffy', 'descripcion' => 'Luffy en pose de ataque', 'precio' => 29.99, 'stock' => 20, 'disponibilidad' => true, 'categoria_figura_id' => 1],
            ['nombre' => 'Figura Zoro', 'descripcion' => 'Zoro con sus espadas', 'precio' => 34.99, 'stock' => 15, 'disponibilidad' => true, 'categoria_figura_id' => 1],
            ['nombre' => 'Figura Naruto', 'descripcion' => 'Naruto usando Rasengan', 'precio' => 27.99, 'stock' => 18, 'disponibilidad' => true, 'categoria_figura_id' => 2],
            ['nombre' => 'Figura Sasuke', 'descripcion' => 'Sasuke con Sharingan', 'precio' => 32.99, 'stock' => 12, 'disponibilidad' => true, 'categoria_figura_id' => 2],
            ['nombre' => 'Figura Goku', 'descripcion' => 'Goku Super Saiyan', 'precio' => 35.99, 'stock' => 10, 'disponibilidad' => true, 'categoria_figura_id' => 3],
            ['nombre' => 'Figura Vegeta', 'descripcion' => 'Vegeta listo para combatir', 'precio' => 36.99, 'stock' => 9, 'disponibilidad' => true, 'categoria_figura_id' => 3],
            ['nombre' => 'Figura Tanjiro', 'descripcion' => 'Tanjiro con katana', 'precio' => 31.99, 'stock' => 14, 'disponibilidad' => true, 'categoria_figura_id' => 4],
            ['nombre' => 'Figura Deku', 'descripcion' => 'Deku usando One For All', 'precio' => 28.99, 'stock' => 16, 'disponibilidad' => true, 'categoria_figura_id' => 5],
            ['nombre' => 'Figura Eren', 'descripcion' => 'Eren Yeager', 'precio' => 33.99, 'stock' => 11, 'disponibilidad' => true, 'categoria_figura_id' => 6],
            ['nombre' => 'Figura Gojo', 'descripcion' => 'Satoru Gojo', 'precio' => 39.99, 'stock' => 8, 'disponibilidad' => true, 'categoria_figura_id' => 7],
        ];

        foreach ($figuras as $figura) {
            Figura::create($figura);
        }
    }
}
