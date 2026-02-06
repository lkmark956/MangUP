<?php

namespace Database\Seeders;

use App\Models\Merch;
use App\Models\MerchVariante;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MerchVarianteSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('merch_variantes')->delete();
        // Solo camisetas y sudaderas tendrán variantes
        $merchs = Merch::whereIn('categoria_merch_id', [1, 2])->get();

        foreach ($merchs as $merch) {
            foreach ([1, 2, 3, 4, 5] as $talla_id) { // XS a XL
                foreach ([1, 2, 4] as $color_id) { // Negro, Blanco, Rojo
                    MerchVariante::create([
                        'merch_id' => $merch->id,
                        'talla_id' => $talla_id,
                        'color_id' => $color_id,
                        'stock' => rand(5, 25),
                    ]);
                }
            }
        }
    }
}
