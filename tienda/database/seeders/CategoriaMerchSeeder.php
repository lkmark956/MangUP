<?php

namespace Database\Seeders;

use App\Models\CategoriaMerch;
use Illuminate\Database\Seeder;

class CategoriaMerchSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Camisetas'],
            ['nombre' => 'Sudaderas'],
            ['nombre' => 'Tazas'],
            ['nombre' => 'Posters'],
            ['nombre' => 'Llaveros'],
            ['nombre' => 'Mochilas'],
        ];

        foreach ($categorias as $categoria) {
            CategoriaMerch::create($categoria);
        }
    }
}
