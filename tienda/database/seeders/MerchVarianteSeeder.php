<?php

namespace Database\Seeders;

use App\Models\Merch;
use App\Models\MerchVariante;
use Illuminate\Database\Seeder;

class MerchVarianteSeeder extends Seeder
{
    public function run(): void
    {
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
