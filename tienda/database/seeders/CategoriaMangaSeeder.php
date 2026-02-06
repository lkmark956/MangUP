<?php

namespace Database\Seeders;

use App\Models\CategoriaManga;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaMangaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de sembrar para evitar duplicados
        DB::table('categorias_manga')->delete();
        $categorias = [
            ['nombre' => 'Acción', 'descripcion' => 'Manga lleno de peleas y emoción'],
            ['nombre' => 'Romance', 'descripcion' => 'Historias de amor y relaciones'],
            ['nombre' => 'Terror', 'descripcion' => 'Manga de horror y suspenso'],
            ['nombre' => 'Fantasía', 'descripcion' => 'Mundos mágicos y sobrenaturales'],
            ['nombre' => 'Comedia', 'descripcion' => 'Historias divertidas y humorísticas'],
            ['nombre' => 'Aventura', 'descripcion' => 'Viajes y grandes hazañas'],
            ['nombre' => 'Drama', 'descripcion' => 'Historias intensas y emocionales'],
            ['nombre' => 'Ciencia Ficción', 'descripcion' => 'Tecnología y futuros alternativos'],
            ['nombre' => 'Misterio', 'descripcion' => 'Enigmas y casos por resolver'],
            ['nombre' => 'Deportes', 'descripcion' => 'Competencias y superación personal'],
        ];

        foreach ($categorias as $categoria) {
            CategoriaManga::create($categoria);
        }
    }
}
