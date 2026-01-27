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

### ⚫ Marco - Panel de Administración + Carrito y Gestión de Stock

**Archivos a crear/modificar:**
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/CarritoController.php`
- `resources/views/admin/` - Vistas del panel
- `resources/views/carrito/` - Vistas del carrito
- `routes/admin.php` - Rutas protegidas del admin
- `app/Models/Carrito.php` (si es necesario)

**Tareas:**

1. **Sistema de Autenticación Admin:**
   - Verificar roles de usuario (admin vs cliente)
   - Middleware para proteger rutas de admin

2. **Panel de Administración:**
   - Dashboard con estadísticas (productos totales, stock bajo, etc.)
   - Gestión de productos (CRUD)
   - Gestión de categorías (CRUD)
   - Gestión de variantes de merch
   - Ver pedidos (estructura lista)

3. **Gestión de Stock:**
   - Vista de productos con stock bajo (< 5 unidades)
   - Actualizar stock de productos
   - Alertas de stock agotado
   - Restar stock automáticamente al comprar

4. **Sistema de Carrito:**
   - Almacenar carrito en sesión/base de datos
   - Añadir productos al carrito
   - Eliminar productos del carrito
   - Actualizar cantidades
   - Calcular totales (subtotal, impuestos, total)
   - Vista del carrito
   - Validar stock disponible antes de añadir

**Rutas Admin (ejemplos):**
```php
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::resource('productos', AdminProductoController::class);
    Route::resource('categorias', AdminCategoriaController::class);
    Route::get('/stock-bajo', [AdminController::class, 'stockBajo'])->name('admin.stock-bajo');
});
```

**Rutas Carrito:**
```php
Route::post('/carrito/agregar/{id}', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::patch('/carrito/actualizar/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::get('/carrito', [CarritoController::class, 'ver'])->name('carrito.ver');
```

---

### 🔵 LORENZO - TODOS LOS SEEDERS (Base de Datos)

**Archivos a crear/modificar:**
- `database/seeders/CategoriaMangaSeeder.php`
- `database/seeders/CategoriaFiguraSeeder.php`
- `database/seeders/CategoriaMerchSeeder.php`
- `database/seeders/TallaSeeder.php`
- `database/seeders/ColorSeeder.php`
- `database/seeders/MangaSeeder.php`
- `database/seeders/FiguraSeeder.php`
- `database/seeders/MerchSeeder.php`
- `database/seeders/MerchVarianteSeeder.php`

**Tareas:**

1. **Categorías de Manga** (10 géneros):
   - Acción, Romance, Terror, Fantasía, Comedia, Aventura, Drama, Ciencia Ficción, Misterio, Deportes

2. **Categorías de Figuras** (7 series):
   - One Piece, Naruto, Dragon Ball, Demon Slayer, My Hero Academia, Attack on Titan, Jujutsu Kaisen

3. **Categorías de Merch** (6 tipos):
   - Camisetas, Sudaderas, Tazas, Posters, Llaveros, Mochilas

4. **Tallas** (6):
   - XS, S, M, L, XL, XXL

5. **Colores** (10):
   - Negro (#000000), Blanco (#FFFFFF), Gris (#808080), Rojo (#FF0000), Azul (#0000FF), Verde (#008000), Amarillo (#FFFF00), Rosa (#FFC0CB), Naranja (#FFA500), Morado (#800080)

6. **Mangas** (mínimo 15 con todos los campos: nombre, descripcion, precio, stock, autor, editorial, fecha_publicacion, numero_paginas, isbn, numero_tomo):
   - One Piece, Naruto, Dragon Ball, Demon Slayer, My Hero Academia, Attack on Titan, Death Note, Fullmetal Alchemist, Spy x Family, Chainsaw Man, Jujutsu Kaisen, Sailor Moon, Fruits Basket, Junji Ito Collection, etc.

7. **Figuras** (mínimo 10 con: nombre, descripcion, precio, stock):
   - Figuras de personajes principales de las series

8. **Merch** (mínimo 10 productos: camisetas, sudaderas, tazas, posters, etc.)

9. **Variantes de Merch** (combinaciones de talla/color/stock para cada merch)

**Modificar `DatabaseSeeder.php` para ejecutar todos los seeders en orden:**
```php
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
    ]);
}
```

---

### 🟢 MARIO - Controladores y Rutas

**Archivos a crear/modificar:**
- `app/Http/Controllers/ProductoController.php`
- `app/Http/Controllers/CategoriaController.php`
- `routes/web.php`

**Tareas:**
1. Crear controlador **ProductoController** con métodos:
   - `index()` - Listar todos los productos
   - `show($id)` - Mostrar detalle de un producto
   - `porCategoria($id)` - Filtrar productos por categoría

2. Crear controlador **CategoriaController** con métodos:
   - `index()` - Listar todas las categorías

3. Definir rutas en `routes/web.php`:
   - `GET /` → Página principal con todos los productos
   - `GET /productos` → Listado de productos
   - `GET /productos/{id}` → Detalle del producto
   - `GET /categorias/{id}/productos` → Productos por categoría
   - `GET /categorias` → Listado de categorías

**Ejemplo de ruta:**
```php
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');
Route::get('/categorias/{id}/productos', [ProductoController::class, 'porCategoria'])->name('productos.categoria');
```

---

### 🟣 LUIS - Vistas y Sistema de Filtros

**Archivos a crear/modificar:**
- `resources/views/productos/index.blade.php` - Listado de productos
- `resources/views/productos/show.blade.php` - Detalle del producto
- `resources/views/partials/filtros.blade.php` - Panel de filtros
- `resources/views/partials/card-producto.blade.php` - Tarjeta reutilizable
- `resources/css/custom.css` - Estilos personalizados

**Tareas:**
1. Crear vista **index.blade.php** con:
   - Grid de productos con tarjetas
   - Barra de filtros (por categoría, precio, disponibilidad)
   - Búsqueda por nombre

2. Crear vista **show.blade.php** con:
   - Imagen principal y galería
   - Nombre, descripción, precio
   - Stock y disponibilidad
   - Botón "Añadir al carrito" (solo estructura HTML)

3. Crear componente **card-producto.blade.php**:
   - Tarjeta reutilizable con imagen, nombre, precio
   - Botón de detalles

4. Crear panel de **filtros.blade.php**:
   - Filtro por categoría (checkboxes)
   - Filtro por rango de precio (slider)
   - Botón "Filtrar"

5. Añadir estilos CSS personalizados para:
   - Grid responsivo
   - Cards atractivas
   - Filtros funcionales

---

## ✅ Checklist de Tareas Generales

### Nivel Básico (Actual)
- [x] Configurar base de datos MySQL
- [x] Crear migraciones para todas las tablas
- [x] Crear modelos Eloquent con relaciones
- [x] Crear layout principal con Bootstrap
- [x] Crear header y footer
- [ ] **PENDIENTE (Lorenzo):** Seeders de datos
- [ ] **PENDIENTE (Mario):** Controladores y rutas básicas
- [ ] **PENDIENTE (Luis):** Vistas y sistema de filtros
- [ ] **PENDIENTE (Tú):** Panel de administración
- [ ] **PENDIENTE (Tú):** Sistema de carrito
- [ ] **PENDIENTE (Tú):** Gestión de stock

### Nivel Intermedio (Próximo)
- [ ] Autenticación completa de usuarios
- [ ] Formulario de checkout
- [ ] Sistema de pedidos
- [ ] Validaciones avanzadas
- [ ] Gestión de imágenes con Storage

### Nivel Experto (Futuro)
- [ ] Formulario de pago (integración Stripe/PayPal)
- [ ] Panel de usuarios con historial de compras
- [ ] Sistema de reseñas y calificaciones
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

- **Coordinador / Panel Admin / Carrito:** Tú (Marco)
- **Lorenzo:** Seeders de Base de Datos
- **Mario:** Controladores y Rutas
- **Luis:** Vistas y Filtros

---

## 📝 Notas Importantes

1. **Antes de empezar**, asegúrate de tener la base de datos creada
2. **Ejecuta las migraciones** antes de crear los seeders
3. **Comunica los cambios** al resto del equipo
4. **Haz commits frecuentes** con mensajes descriptivos
5. **Revisa el código** de tus compañeros antes de hacer merge

---

¡Buena suerte con el proyecto! 🚀