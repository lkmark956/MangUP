<?php

namespace Database\Seeders;

use App\Models\Figura;
use Illuminate\Database\Seeder;

class FiguraSeeder extends Seeder
{
    public function run(): void
    {
        $figuras = [
            // Figuras con imágenes reales
            [
                'nombre' => 'Figura Deku - My Hero Academia',
                'descripcion' => 'Figura de acción de Izuku Midoriya (Deku) de My Hero Academia. Pose dinámica con efectos de One For All. Material PVC de alta calidad, altura aproximada 25cm.',
                'precio' => 45.99,
                'stock' => 12,
                'disponibilidad' => true,
                'categoria_figura_id' => 5 // My Hero Academia
            ],
            [
                'nombre' => 'Figura Chainsaw Man - Denji',
                'descripcion' => 'Figura de Denji en su forma de Chainsaw Man. Diseño detallado con las motosierras características. Material PVC premium, altura 22cm.',
                'precio' => 52.99,
                'stock' => 8,
                'disponibilidad' => true,
                'categoria_figura_id' => 8 // Chainsaw Man
            ],
            [
                'nombre' => 'Figura Suguru Geto - Jujutsu Kaisen',
                'descripcion' => 'Figura de Suguru Geto, el usuario de técnicas de manipulación de maldiciones. Pose elegante con efectos de maldición. Altura 24cm.',
                'precio' => 48.99,
                'stock' => 10,
                'disponibilidad' => true,
                'categoria_figura_id' => 7 // Jujutsu Kaisen
            ],
            [
                'nombre' => 'Figura Satoru Gojo - Jujutsu Kaisen',
                'descripcion' => 'El hechicero más poderoso de Jujutsu Kaisen. Figura con venda removible mostrando sus ojos del Infinito. Material premium, 26cm de altura.',
                'precio' => 59.99,
                'stock' => 15,
                'disponibilidad' => true,
                'categoria_figura_id' => 7
            ],
            [
                'nombre' => 'Figura Hollow - Bleach',
                'descripcion' => 'Impresionante figura de un Hollow de la serie Bleach. Diseño terrorífico con detalles en la máscara. Base incluida, altura 28cm.',
                'precio' => 64.99,
                'stock' => 6,
                'disponibilidad' => true,
                'categoria_figura_id' => 9 // Bleach
            ],
        ];

        foreach ($figuras as $figura) {
            Figura::create($figura);
        }
    }
}
