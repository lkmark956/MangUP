<?php

namespace Database\Seeders;

use App\Models\Imagen;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;
use Illuminate\Database\Seeder;

class ImagenSeeder extends Seeder
{
    public function run(): void
    {
        // Imágenes de Mangas
        $imagenesManga = [
            ['id' => 1, 'ruta' => 'productos/mangas/one-piece-vol1.jpg'],
            ['id' => 2, 'ruta' => 'productos/mangas/naruto-vol1.jpg'],
            ['id' => 3, 'ruta' => 'productos/mangas/dragon-ball-vol1.jpg'],
            ['id' => 4, 'ruta' => 'productos/mangas/demon-slayer-vol1.jpg'],
            ['id' => 5, 'ruta' => 'productos/mangas/my-hero-academia-vol1.jpg'],
            ['id' => 6, 'ruta' => 'productos/mangas/attack-on-titan-vol1.jpg'],
            ['id' => 7, 'ruta' => 'productos/mangas/death-note-vol1.jpg'],
            ['id' => 8, 'ruta' => 'productos/mangas/fullmetal-alchemist-vol1.jpg'],
            ['id' => 9, 'ruta' => 'productos/mangas/spy-x-family-vol1.jpg'],
            ['id' => 10, 'ruta' => 'productos/mangas/chainsaw-man-vol1.jpg'],
            ['id' => 11, 'ruta' => 'productos/mangas/jujutsu-kaisen-vol1.jpg'],
            ['id' => 12, 'ruta' => 'productos/mangas/sailor-moon-vol1.jpg'],
            ['id' => 13, 'ruta' => 'productos/mangas/fruits-basket-vol1.jpg'],
            ['id' => 14, 'ruta' => 'productos/mangas/junji-ito-vol1.jpg'],
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

        // Imágenes de Figuras
        $imagenesFigura = [
            ['id' => 1, 'ruta' => 'productos/figuras/figura-luffy.jpg'],
            ['id' => 2, 'ruta' => 'productos/figuras/figura-zoro.jpg'],
            ['id' => 3, 'ruta' => 'productos/figuras/figura-naruto.jpg'],
            ['id' => 4, 'ruta' => 'productos/figuras/figura-sasuke.jpg'],
            ['id' => 5, 'ruta' => 'productos/figuras/figura-goku.jpg'],
            ['id' => 6, 'ruta' => 'productos/figuras/figura-vegeta.jpg'],
            ['id' => 7, 'ruta' => 'productos/figuras/figura-tanjiro.jpg'],
            ['id' => 8, 'ruta' => 'productos/figuras/figura-deku.jpg'],
            ['id' => 9, 'ruta' => 'productos/figuras/figura-eren.jpg'],
            ['id' => 10, 'ruta' => 'productos/figuras/figura-gojo.jpg'],
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

        // Imágenes de Merch
        $imagenesMerch = [
            ['id' => 1, 'ruta' => 'productos/merch/camiseta-naruto.jpg'],
            ['id' => 2, 'ruta' => 'productos/merch/camiseta-luffy.jpg'],
            ['id' => 3, 'ruta' => 'productos/merch/sudadera-goku.jpg'],
            ['id' => 4, 'ruta' => 'productos/merch/taza-aot.jpg'],
            ['id' => 5, 'ruta' => 'productos/merch/poster-demon-slayer.jpg'],
            ['id' => 6, 'ruta' => 'productos/merch/llavero-jjk.jpg'],
            ['id' => 7, 'ruta' => 'productos/merch/mochila-one-piece.jpg'],
            ['id' => 8, 'ruta' => 'productos/merch/camiseta-chainsaw-man.jpg'],
            ['id' => 9, 'ruta' => 'productos/merch/sudadera-mha.jpg'],
            ['id' => 10, 'ruta' => 'productos/merch/taza-sailor-moon.jpg'],
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
