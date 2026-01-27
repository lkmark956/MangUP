<?php

namespace Database\Seeders;

use App\Models\Imagen;
use App\Models\Merch;
use Illuminate\Database\Seeder;

class ImagenSeeder extends Seeder
{
    public function run(): void
    {
        $imagenes = [
            ['merch_id' => 1, 'ruta' => 'productos/camiseta-naruto.jpg', 'es_principal' => true, 'orden' => 1],
            ['merch_id' => 2, 'ruta' => 'productos/camiseta-luffy.jpg', 'es_principal' => true, 'orden' => 1],
            ['merch_id' => 3, 'ruta' => 'productos/sudadera-goku.jpg', 'es_principal' => true, 'orden' => 1],
            ['merch_id' => 4, 'ruta' => 'productos/taza-aot.jpg', 'es_principal' => true, 'orden' => 1],
            ['merch_id' => 5, 'ruta' => 'productos/poster-demon-slayer.jpg', 'es_principal' => true, 'orden' => 1],
        ];

        foreach ($imagenes as $img) {
            Imagen::create([
                'imageable_id' => $img['merch_id'],
                'imageable_type' => Merch::class,
                'ruta' => $img['ruta'],
                'es_principal' => $img['es_principal'],
                'orden' => $img['orden'],
            ]);
        }
    }
}
