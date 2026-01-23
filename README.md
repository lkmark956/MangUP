# 🎌 MangUP - Tienda Online de Manga y Anime

**MangUP** es un ecommerce desarrollado con Laravel para la venta de manga, figuras coleccionables y merchandising anime.

---

## 📋 Requisitos del Sistema

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18 (para compilar assets)

---

## 🚀 Instalación

### 1. Clonar el repositorio
```bash
git clone https://github.com/lkmark956/MangUP.git
cd MangUP/tienda
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos
Edita el archivo `.env` con tus credenciales de MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangup_db
DB_USERNAME=root
DB_PASSWORD=root
```

### 5. Crear la base de datos
En MySQL, crea la base de datos:
```sql
CREATE DATABASE mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar migraciones
```bash
php artisan migrate
```

### 7. Ejecutar seeders (datos de prueba)
```bash
php artisan db:seed
```

### 8. Iniciar el servidor
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

## 📁 Estructura del Proyecto

```
tienda/
├── app/
│   ├── Http/Controllers/     # Controladores
│   └── Models/               # Modelos Eloquent
│       ├── CategoriaManga.php
│       ├── CategoriaFigura.php
│       ├── CategoriaMerch.php
│       ├── Manga.php
│       ├── Figura.php
│       ├── Merch.php
│       ├── MerchVariante.php
│       ├── Talla.php
│       ├── Color.php
│       └── Imagen.php
├── database/
│   ├── migrations/           # Migraciones de BD
│   └── seeders/              # Datos de prueba
├── resources/views/          # Vistas Blade
│   ├── layouts/app.blade.php # Layout principal
│   ├── partials/
│   │   ├── header.blade.php
│   │   └── footer.blade.php
│   └── welcome.blade.php     # Página principal
└── routes/web.php            # Rutas web
```

---

## 🗃️ Estructura de la Base de Datos

### Tablas principales:

| Tabla | Descripción |
|-------|-------------|
| `categorias_manga` | Géneros de manga (Acción, Romance, Terror, etc.) |
| `categorias_figura` | Series de figuras (One Piece, Naruto, etc.) |
| `categorias_merch` | Tipos de merch (Camisetas, Tazas, etc.) |
| `mangas` | Productos de tipo manga |
| `figuras` | Productos de tipo figura |
| `merchs` | Productos de merchandising |
| `merch_variantes` | Variantes de talla/color para merch |
| `tallas` | Tallas disponibles (XS, S, M, L, XL, XXL) |
| `colores` | Colores disponibles |
| `imagenes` | Galería de imágenes (polimórfica) |

### Diagrama de relaciones:

```
categorias_manga (1) ──────< (N) mangas
categorias_figura (1) ─────< (N) figuras
categorias_merch (1) ──────< (N) merchs

merchs (1) ────────────────< (N) merch_variantes
tallas (1) ────────────────< (N) merch_variantes
colores (1) ───────────────< (N) merch_variantes

mangas/figuras/merchs (1) ─< (N) imagenes (polimórfica)
```

---

## 👥 Asignación de Tareas por Compañero

### 🔵 LORENZO - Seeders y Datos de Categorías

**Archivos a crear/modificar:**
- `database/seeders/CategoriaMangaSeeder.php`
- `database/seeders/CategoriaFiguraSeeder.php`
- `database/seeders/CategoriaMerchSeeder.php`
- `database/seeders/TallaSeeder.php`
- `database/seeders/ColorSeeder.php`

**Tareas:**
1. Crear seeder para **categorías de manga** con estos géneros:
   - Acción, Romance, Terror, Fantasía, Comedia, Aventura, Drama, Ciencia Ficción, Misterio, Deportes

2. Crear seeder para **categorías de figuras** con estas series:
   - One Piece, Naruto, Dragon Ball, Demon Slayer, My Hero Academia, Attack on Titan, Jujutsu Kaisen

3. Crear seeder para **categorías de merch**:
   - Camisetas, Sudaderas, Tazas, Posters, Llaveros, Mochilas

4. Crear seeder para **tallas**:
   - XS, S, M, L, XL, XXL

5. Crear seeder para **colores**:
   - Negro (#000000), Blanco (#FFFFFF), Gris (#808080), Rojo (#FF0000), Azul (#0000FF), Verde (#008000), Amarillo (#FFFF00), Rosa (#FFC0CB), Naranja (#FFA500), Morado (#800080)

**Ejemplo de seeder:**
```php
<?php
namespace Database\Seeders;

use App\Models\CategoriaManga;
use Illuminate\Database\Seeder;

class CategoriaMangaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Acción', 'descripcion' => 'Manga lleno de peleas y emoción'],
            ['nombre' => 'Romance', 'descripcion' => 'Historias de amor y relaciones'],
            // ... añadir más
        ];

        foreach ($categorias as $categoria) {
            CategoriaManga::create($categoria);
        }
    }
}
```

---

### 🟢 MARIO - Seeders de Productos (Mangas y Figuras)

**Archivos a crear/modificar:**
- `database/seeders/MangaSeeder.php`
- `database/seeders/FiguraSeeder.php`

**Tareas:**
1. Crear seeder con **mínimo 15 mangas** de prueba con todos los campos:
   - nombre, descripcion, precio, stock, disponibilidad, autor, editorial, fecha_publicacion, numero_paginas, isbn, numero_tomo, categoria_manga_id

2. Crear seeder con **mínimo 10 figuras** de prueba:
   - nombre, descripcion, precio, stock, disponibilidad, categoria_figura_id

**Ejemplo de manga:**
```php
[
    'nombre' => 'One Piece Vol. 1',
    'descripcion' => 'El comienzo de la aventura de Monkey D. Luffy...',
    'precio' => 8.95,
    'stock' => 50,
    'disponibilidad' => true,
    'autor' => 'Eiichiro Oda',
    'editorial' => 'Planeta Cómic',
    'fecha_publicacion' => '1997-07-22',
    'numero_paginas' => 200,
    'isbn' => '978-84-XXXXX-XX-X',
    'numero_tomo' => 1,
    'categoria_manga_id' => 1 // Acción
]
```

**Recomendación de mangas a incluir:**
- One Piece, Naruto, Dragon Ball, Demon Slayer, My Hero Academia
- Attack on Titan, Death Note, Fullmetal Alchemist
- Spy x Family, Chainsaw Man, Jujutsu Kaisen
- Sailor Moon, Fruits Basket (romance)
- Junji Ito Collection (terror)

---

### 🟣 LUIS - Seeders de Merch y Sistema de Imágenes

**Archivos a crear/modificar:**
- `database/seeders/MerchSeeder.php`
- `database/seeders/MerchVarianteSeeder.php`
- `database/seeders/ImagenSeeder.php` (opcional)

**Tareas:**
1. Crear seeder con **mínimo 10 productos merch**:
   - Camisetas, sudaderas, tazas, posters, etc.

2. Crear seeder para **variantes de merch** (combinaciones talla/color/stock):
   - Cada camiseta debe tener variantes con diferentes tallas y colores

3. Buscar y descargar **imágenes de ejemplo** para los productos:
   - Guardarlas en `storage/app/public/productos/`
   - Crear el enlace simbólico: `php artisan storage:link`

**Ejemplo de merch con variantes:**
```php
// Crear el merch
$camiseta = Merch::create([
    'nombre' => 'Camiseta Naruto Uzumaki',
    'descripcion' => 'Camiseta 100% algodón con diseño de Naruto',
    'precio' => 24.95,
    'disponibilidad' => true,
    'categoria_merch_id' => 1 // Camisetas
]);

// Crear variantes (talla M, color Negro, stock 20)
MerchVariante::create([
    'merch_id' => $camiseta->id,
    'talla_id' => 3, // M
    'color_id' => 1, // Negro
    'stock' => 20
]);
```

---

## ✅ Checklist de Tareas Generales

### Nivel Básico (Actual)
- [x] Configurar base de datos MySQL
- [x] Crear migraciones para todas las tablas
- [x] Crear modelos Eloquent con relaciones
- [x] Crear layout principal con Bootstrap
- [x] Crear header y footer
- [ ] **PENDIENTE:** Seeders de datos (Lorenzo, Mario, Luis)
- [ ] Crear vistas de catálogo
- [ ] Crear sistema de filtros por categoría

### Nivel Intermedio (Próximo)
- [ ] Sistema de autenticación de usuarios
- [ ] Carrito de compras con sesiones
- [ ] Añadir/eliminar productos del carrito
- [ ] Cálculo de totales
- [ ] Gestión de imágenes con Storage

### Nivel Experto (Futuro)
- [ ] Sistema de pedidos
- [ ] Formulario de pago
- [ ] Panel de administración
- [ ] Relaciones avanzadas Eloquent

---

## 🛠️ Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir y volver a ejecutar migraciones
php artisan migrate:fresh

# Ejecutar todos los seeders
php artisan db:seed

# Ejecutar un seeder específico
php artisan db:seed --class=CategoriaMangaSeeder

# Crear enlace simbólico para storage
php artisan storage:link

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Iniciar servidor de desarrollo
php artisan serve
```

---

## 📞 Contacto del Equipo

- **Coordinador:** [Tu nombre]
- **Lorenzo:** Seeders de categorías
- **Mario:** Seeders de productos
- **Luis:** Seeders de merch e imágenes

---

## 📝 Notas Importantes

1. **Antes de empezar**, asegúrate de tener la base de datos creada
2. **Ejecuta las migraciones** antes de crear los seeders
3. **Comunica los cambios** al resto del equipo
4. **Haz commits frecuentes** con mensajes descriptivos
5. **Revisa el código** de tus compañeros antes de hacer merge

---

¡Buena suerte con el proyecto! 🚀