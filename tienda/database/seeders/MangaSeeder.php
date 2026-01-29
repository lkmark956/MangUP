<?php

namespace Database\Seeders;

use App\Models\Manga;
use Illuminate\Database\Seeder;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        $mangas = [
            // Jujutsu Kaisen - Tomos disponibles con imágenes reales
            [
                'nombre' => 'Jujutsu Kaisen Vol. 0',
                'descripcion' => 'La precuela de Jujutsu Kaisen. Yuta Okkotsu es un estudiante de secundaria perseguido por el espíritu de su amiga de la infancia Rika, quien murió en un accidente de tráfico. Su vida cambiará cuando conozca a Satoru Gojo.',
                'precio' => 8.95,
                'stock' => 25,
                'disponibilidad' => true,
                'autor' => 'Gege Akutami',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2018-12-04',
                'numero_paginas' => 200,
                'isbn' => '978-84-679-4521-1',
                'numero_tomo' => 0,
                'categoria_manga_id' => 1 // Acción
            ],
            [
                'nombre' => 'Jujutsu Kaisen Vol. 4',
                'descripcion' => 'Yuji Itadori continúa su entrenamiento en la Escuela de Hechicería de Tokio. Las maldiciones se vuelven más poderosas y los hechiceros deben prepararse para lo peor.',
                'precio' => 8.95,
                'stock' => 18,
                'disponibilidad' => true,
                'autor' => 'Gege Akutami',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2019-03-04',
                'numero_paginas' => 192,
                'isbn' => '978-84-679-4524-2',
                'numero_tomo' => 4,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'Jujutsu Kaisen Vol. 22',
                'descripcion' => 'El arco de Shibuya llega a su clímax. Los hechiceros luchan contra las maldiciones más poderosas mientras el destino del mundo pende de un hilo.',
                'precio' => 8.95,
                'stock' => 30,
                'disponibilidad' => true,
                'autor' => 'Gege Akutami',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2023-06-02',
                'numero_paginas' => 192,
                'isbn' => '978-84-679-4546-4',
                'numero_tomo' => 22,
                'categoria_manga_id' => 1
            ],
            // My Hero Academia - Tomos disponibles con imágenes reales
            [
                'nombre' => 'My Hero Academia Vol. 29',
                'descripcion' => 'La guerra entre héroes y villanos continúa. Deku debe enfrentarse a su destino como portador del One For All mientras sus amigos luchan por proteger la sociedad.',
                'precio' => 8.50,
                'stock' => 22,
                'disponibilidad' => true,
                'autor' => 'Kohei Horikoshi',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '2022-01-10',
                'numero_paginas' => 192,
                'isbn' => '978-84-001-2929-5',
                'numero_tomo' => 29,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'My Hero Academia Vol. 42',
                'descripcion' => 'El enfrentamiento final se acerca. Los héroes deben unirse para detener a All For One y salvar el futuro de la sociedad de héroes.',
                'precio' => 8.50,
                'stock' => 35,
                'disponibilidad' => true,
                'autor' => 'Kohei Horikoshi',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '2024-08-05',
                'numero_paginas' => 200,
                'isbn' => '978-84-001-2942-4',
                'numero_tomo' => 42,
                'categoria_manga_id' => 1
            ],
        ];

        foreach ($mangas as $manga) {
            Manga::create($manga);
        }
    }
}
