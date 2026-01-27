<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        CategoriaMangaSeeder::class,
        CategoriaFiguraSeeder::class,
        CategoriaMerchSeeder::class,
        TallaSeeder::class,
        ColorSeeder::class,
        MangaSeeder::class,
        FiguraSeeder::class,
        MerchSeeder::class,
        MerchVarianteSeeder::class,
        ImagenSeeder::class,
    ]);
}

}
