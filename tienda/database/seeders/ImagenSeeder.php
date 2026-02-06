<?php

namespace Database\Seeders;

use App\Models\Imagen;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImagenSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('imagenes')->delete();
        // Imágenes de Mangas - Coincidiendo con archivos reales en productos/mangas/
        $imagenesManga = [
            ['id' => 1, 'ruta' => 'productos/mangas/JJK-0.png'],        // Jujutsu Kaisen Vol. 0
            ['id' => 2, 'ruta' => 'productos/mangas/JJK-4.png'],        // Jujutsu Kaisen Vol. 4
            ['id' => 3, 'ruta' => 'productos/mangas/JJK-22.png'],       // Jujutsu Kaisen Vol. 22
            ['id' => 4, 'ruta' => 'productos/mangas/Tomo29-mha.jpg'],   // My Hero Academia Vol. 29
            ['id' => 5, 'ruta' => 'productos/mangas/Tomo42-mha.jpg'],   // My Hero Academia Vol. 42
        ];

        foreach ($imagenesManga as $img) {
            Imagen::create([
                'imageable_id' => $img['id'],
                'imageable_type' => Manga::class,
                'ruta' => $img['ruta'],
                'es_principal' => true,
                'orden' => 1,
            ]);
        }

        // Imágenes de Figuras - Coincidiendo con archivos reales en productos/figuras/
        $imagenesFigura = [
            ['id' => 1, 'ruta' => 'productos/figuras/Deku figura.jpg'],      // Figura Deku
            ['id' => 2, 'ruta' => 'productos/figuras/Figura chainsaw.jpg'], // Figura Chainsaw Man
            ['id' => 3, 'ruta' => 'productos/figuras/Figura geto.jpg'],     // Figura Geto
            ['id' => 4, 'ruta' => 'productos/figuras/Figura gojo.jpg'],     // Figura Gojo
            ['id' => 5, 'ruta' => 'productos/figuras/Figura hollow.jpg'],   // Figura Hollow
        ];

        foreach ($imagenesFigura as $img) {
            Imagen::create([
                'imageable_id' => $img['id'],
                'imageable_type' => Figura::class,
                'ruta' => $img['ruta'],
                'es_principal' => true,
                'orden' => 1,
            ]);
        }

        // Imágenes de Merch - Coincidiendo con archivos reales en productos/merch/
        $imagenesMerch = [
            ['id' => 1, 'ruta' => 'productos/merch/camiseta-naruto.jpg'],
            ['id' => 2, 'ruta' => 'productos/merch/camiseta-luffy.jpg'],
            ['id' => 3, 'ruta' => 'productos/merch/sudadera-goku.jpg'],
            ['id' => 4, 'ruta' => 'productos/merch/taza-aot.jpg'],
            ['id' => 5, 'ruta' => 'productos/merch/poster-demon-slayer.jpg'],
        ];

        foreach ($imagenesMerch as $img) {
            Imagen::create([
                'imageable_id' => $img['id'],
                'imageable_type' => Merch::class,
                'ruta' => $img['ruta'],
                'es_principal' => true,
                'orden' => 1,
            ]);
        }
    }
}
