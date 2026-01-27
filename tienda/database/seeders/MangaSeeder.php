<?php

namespace Database\Seeders;

use App\Models\Manga;
use Illuminate\Database\Seeder;

class MangaSeeder extends Seeder
{
    public function run(): void
    {
        $mangas = [
            [
                'nombre' => 'One Piece Vol. 1',
                'descripcion' => 'El comienzo de la aventura de Monkey D. Luffy.',
                'precio' => 8.95,
                'stock' => 50,
                'disponibilidad' => true,
                'autor' => 'Eiichiro Oda',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '1997-07-22',
                'numero_paginas' => 200,
                'isbn' => '978-84-001',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1 // Acción
            ],
            [
                'nombre' => 'Naruto Vol. 1',
                'descripcion' => 'El ninja que sueña con ser Hokage.',
                'precio' => 7.95,
                'stock' => 45,
                'disponibilidad' => true,
                'autor' => 'Masashi Kishimoto',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '1999-09-21',
                'numero_paginas' => 190,
                'isbn' => '978-84-002',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'Dragon Ball Vol. 1',
                'descripcion' => 'Las primeras aventuras de Goku.',
                'precio' => 7.50,
                'stock' => 40,
                'disponibilidad' => true,
                'autor' => 'Akira Toriyama',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '1984-11-20',
                'numero_paginas' => 192,
                'isbn' => '978-84-003',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'Demon Slayer Vol. 1',
                'descripcion' => 'Tanjiro lucha contra los demonios.',
                'precio' => 8.95,
                'stock' => 35,
                'disponibilidad' => true,
                'autor' => 'Koyoharu Gotouge',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2016-02-15',
                'numero_paginas' => 192,
                'isbn' => '978-84-004',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'My Hero Academia Vol. 1',
                'descripcion' => 'Un mundo donde todos tienen poderes.',
                'precio' => 8.50,
                'stock' => 30,
                'disponibilidad' => true,
                'autor' => 'Kohei Horikoshi',
                'editorial' => 'Planeta Cómic',
                'fecha_publicacion' => '2014-07-07',
                'numero_paginas' => 192,
                'isbn' => '978-84-005',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'Attack on Titan Vol. 1',
                'descripcion' => 'La humanidad lucha contra los titanes.',
                'precio' => 9.50,
                'stock' => 25,
                'disponibilidad' => true,
                'autor' => 'Hajime Isayama',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2009-09-09',
                'numero_paginas' => 192,
                'isbn' => '978-84-006',
                'numero_tomo' => 1,
                'categoria_manga_id' => 7 // Drama
            ],
            [
                'nombre' => 'Death Note Vol. 1',
                'descripcion' => 'Un cuaderno que puede matar.',
                'precio' => 8.95,
                'stock' => 20,
                'disponibilidad' => true,
                'autor' => 'Tsugumi Ohba',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2003-12-01',
                'numero_paginas' => 200,
                'isbn' => '978-84-007',
                'numero_tomo' => 1,
                'categoria_manga_id' => 9 // Misterio
            ],
            [
                'nombre' => 'Fullmetal Alchemist Vol. 1',
                'descripcion' => 'Dos hermanos y la alquimia.',
                'precio' => 8.75,
                'stock' => 22,
                'disponibilidad' => true,
                'autor' => 'Hiromu Arakawa',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2001-07-12',
                'numero_paginas' => 192,
                'isbn' => '978-84-008',
                'numero_tomo' => 1,
                'categoria_manga_id' => 8 // Fantasía
            ],
            [
                'nombre' => 'Spy x Family Vol. 1',
                'descripcion' => 'Una familia nada normal.',
                'precio' => 8.95,
                'stock' => 28,
                'disponibilidad' => true,
                'autor' => 'Tatsuya Endo',
                'editorial' => 'Ivrea',
                'fecha_publicacion' => '2019-03-25',
                'numero_paginas' => 200,
                'isbn' => '978-84-009',
                'numero_tomo' => 1,
                'categoria_manga_id' => 6 // Comedia
            ],
            [
                'nombre' => 'Chainsaw Man Vol. 1',
                'descripcion' => 'Cazadores de demonios.',
                'precio' => 8.95,
                'stock' => 18,
                'disponibilidad' => true,
                'autor' => 'Tatsuki Fujimoto',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2018-12-03',
                'numero_paginas' => 192,
                'isbn' => '978-84-010',
                'numero_tomo' => 1,
                'categoria_manga_id' => 3 // Terror
            ],
            [
                'nombre' => 'Jujutsu Kaisen Vol. 1',
                'descripcion' => 'Hechicería y maldiciones.',
                'precio' => 8.95,
                'stock' => 26,
                'disponibilidad' => true,
                'autor' => 'Gege Akutami',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '2018-07-04',
                'numero_paginas' => 192,
                'isbn' => '978-84-011',
                'numero_tomo' => 1,
                'categoria_manga_id' => 1
            ],
            [
                'nombre' => 'Sailor Moon Vol. 1',
                'descripcion' => 'Guerreras mágicas.',
                'precio' => 7.95,
                'stock' => 15,
                'disponibilidad' => true,
                'autor' => 'Naoko Takeuchi',
                'editorial' => 'Ivrea',
                'fecha_publicacion' => '1991-12-28',
                'numero_paginas' => 180,
                'isbn' => '978-84-012',
                'numero_tomo' => 1,
                'categoria_manga_id' => 2 // Romance
            ],
            [
                'nombre' => 'Fruits Basket Vol. 1',
                'descripcion' => 'Drama y romance sobrenatural.',
                'precio' => 7.95,
                'stock' => 14,
                'disponibilidad' => true,
                'autor' => 'Natsuki Takaya',
                'editorial' => 'Norma Editorial',
                'fecha_publicacion' => '1998-07-18',
                'numero_paginas' => 192,
                'isbn' => '978-84-013',
                'numero_tomo' => 1,
                'categoria_manga_id' => 2
            ],
            [
                'nombre' => 'Junji Ito Collection Vol. 1',
                'descripcion' => 'Historias de terror psicológico.',
                'precio' => 9.95,
                'stock' => 12,
                'disponibilidad' => true,
                'autor' => 'Junji Ito',
                'editorial' => 'ECC',
                'fecha_publicacion' => '2015-10-01',
                'numero_paginas' => 220,
                'isbn' => '978-84-014',
                'numero_tomo' => 1,
                'categoria_manga_id' => 3
            ],
        ];

        foreach ($mangas as $manga) {
            Manga::create($manga);
        }
    }
}
