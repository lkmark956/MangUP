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

### 5. Configurar Stripe
**⚠️ IMPORTANTE:** Para que funcione el sistema de pagos, debes configurar tus claves de Stripe.

1. Crea una cuenta en [Stripe](https://stripe.com)
2. Obtén tus claves de prueba desde el [Dashboard de Stripe](https://dashboard.stripe.com/test/apikeys)
3. Edita el archivo `.env` y añade tus claves:
```env
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=
```

> **Nota:** Las claves que empiezan con `pk_test_` y `sk_test_` son para el entorno de pruebas (modo sandbox). En producción deberás usar las claves reales.

### 6. Crear la base de datos
En MySQL, crea la base de datos:
```sql
CREATE DATABASE mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Ejecutar migraciones
```bash
php artisan migrate
```

### 8. Ejecutar seeders (datos de prueba)
```bash
php artisan db:seed
```

### 9. Iniciar el servidor
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

## � Solución de Problemas (Troubleshooting)

### ❌ "Se rompe al rellenar datos de pago con Stripe"

Si el formulario de pago falla o da errores al intentar pagar, sigue estos pasos:

#### 1️⃣ Verificar que las claves de Stripe estén configuradas correctamente

Abre el archivo `.env` y verifica que las claves de Stripe estén presentes:
```env
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxx
```

**Importante:** 
- Las claves **NO** deben tener espacios al inicio ni al final
- La `STRIPE_KEY` debe empezar con `pk_test_` (o `pk_live_` en producción)
- La `STRIPE_SECRET` debe empezar con `sk_test_` (o `sk_live_` en producción)

#### 2️⃣ Limpiar caché de configuración

Laravel puede estar usando configuración antigua en caché:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### 3️⃣ Verificar que APP_KEY esté generada

Si ves errores de encriptación o sesión, necesitas generar la clave de la aplicación:
```bash
php artisan key:generate
```

Luego reinicia el servidor:
```bash
php artisan serve
```

#### 4️⃣ Verificar permisos de storage

En Windows, normalmente no hay problemas, pero en Linux/Mac:
```bash
chmod -R 775 storage bootstrap/cache
```

#### 5️⃣ Verificar que la base de datos esté correctamente configurada

```bash
# Ejecutar las migraciones
php artisan migrate

# Si ya están migradas, verificar
php artisan migrate:status
```

#### 6️⃣ Verificar que las claves de Stripe sean válidas

Puedes probar las claves directamente en el Dashboard de Stripe:
- Ve a https://dashboard.stripe.com/test/apikeys
- Copia las claves de nuevo y reemplázalas en el `.env`
- **Asegúrate de estar en modo "Test" (no "Live")**

#### 7️⃣ Ver los logs de error

Si sigue fallando, revisa los logs de Laravel para ver el error exacto:
```bash
# En Windows
type storage\logs\laravel.log | more

# En Linux/Mac
tail -f storage/logs/laravel.log
```

O abre el archivo: `tienda/storage/logs/laravel.log`

### ❌ "No se cargan los estilos CSS"

Si la página se ve sin estilos:
```bash
npm install
npm run dev
```

### ❌ "Error de conexión a la base de datos"

Verifica que:
1. MySQL esté corriendo
2. La base de datos `mangup_db` exista
3. Las credenciales en `.env` sean correctas:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangup_db
DB_USERNAME=root
DB_PASSWORD=root
```

### 📝 Checklist completo para configurar el proyecto

Si acabas de clonar el proyecto, asegúrate de hacer TODO esto:

- [ ] `composer install`
- [ ] `npm install`
- [ ] `cp .env.example .env`
- [ ] `php artisan key:generate`
- [ ] Configurar `.env` (base de datos + Stripe)
- [ ] Crear base de datos en MySQL
- [ ] `php artisan migrate`
- [ ] `php artisan db:seed`
- [ ] `php artisan config:clear`
- [ ] `npm run dev` (o `npm run build` para producción)
- [ ] `php artisan serve`

---

## �📁 Estructura del Proyecto

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