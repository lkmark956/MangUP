<?php

namespace Database\Seeders;

use App\Models\Talla;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TallaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('tallas')->delete();
        $tallas = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        foreach ($tallas as $talla) {
            Talla::create(['nombre' => $talla]);
        }
    }
}
