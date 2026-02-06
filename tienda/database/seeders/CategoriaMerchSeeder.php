<?php

namespace Database\Seeders;

use App\Models\CategoriaMerch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaMerchSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('categorias_merch')->delete();
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
