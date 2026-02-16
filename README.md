# 🎌 MangUP - Tienda Online de Manga y Anime

**MangUP** es un ecommerce desarrollado con Laravel para la venta de manga, figuras coleccionables y merchandising anime.

> **📁 Nota importante:** La aplicación Laravel se encuentra en la carpeta `tienda/`. Todos los comandos deben ejecutarse desde esa carpeta.

---

## 📑 Tabla de Contenidos

- [🚀 Inicio Rápido (Quick Start)](#-inicio-rápido-quick-start)
- [✅ ¿Funcionará en otro PC sin cambios?](#-funcionará-en-otro-pc-sin-cambios)
- [📋 Requisitos del Sistema](#-requisitos-del-sistema)
- [🚀 Instalación Completa](#-instalación)
- [🔧 Solución de Problemas](#-solución-de-problemas-troubleshooting)
- [🛠️ Funcionalidades Principales](#️-funcionalidades-principales)
- [📁 Estructura del Proyecto](#-estructura-del-proyecto)
- [🎯 Casos de Uso del Sistema](#-casos-de-uso-del-sistema)
- [🔧 Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [🔒 Seguridad Implementada](#-seguridad-implementada)
- [📤 Subir cambios a Git](#-subir-cambios-a-git)
- [📝 Notas de Desarrollo](#-notas-de-desarrollo)

---

## 🚀 Inicio Rápido (Quick Start)

Si ya tienes instalado PHP 8.2+, Composer, MySQL y Node.js:

```bash
# 1. Clonar el repositorio
git clone https://github.com/lkmark956/MangUP.git
cd MangUP/tienda

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
copy .env.example .env       # En Windows (cmd)
cp .env.example .env         # En Linux/Mac
php artisan key:generate

# 4. Crear base de datos en MySQL
# Ejecuta: CREATE DATABASE mangup_db;

# 5. Configurar .env
# Edita el archivo .env con tus credenciales de MySQL y Stripe

# 6. Migrar base de datos
php artisan migrate --seed

# 7. Crear enlace de storage
php artisan storage:link

# 8. Compilar assets
npm run build

# 9. Iniciar servidor
php artisan serve
# Accede a: http://localhost:8000
```

⚠️ **No olvides configurar tus claves de Stripe en el archivo `.env`** (ve a la [sección de instalación completa](#-instalación) para más detalles).

---

## ✅ ¿Funcionará en otro PC sin cambios?

**SÍ**, el código está listo para funcionar en cualquier PC. Solo necesitas:

1. ✅ **Código fuente:** Ya está completo en el repositorio
2. ⚙️ **Configuración local:** Cada persona debe configurar:
   - Su archivo `.env` con credenciales de MySQL locales
   - Sus propias claves gratuitas de Stripe (modo test)
3. 📦 **Dependencias:** Se instalan automáticamente con `composer install` y `npm install`

**No necesitas modificar ningún archivo de código.** Solo sigue la [Guía de Instalación](#-instalación) paso a paso.

---

## 📋 Requisitos del Sistema

### Software necesario:
- **PHP** >= 8.2
- **Composer** (gestor de dependencias de PHP)
- **MySQL** >= 8.0 o **MariaDB** >= 10.3
- **Node.js** >= 18 y **npm** (para compilar assets CSS/JS)

### Extensiones PHP requeridas:
- `openssl`
- `pdo_mysql`
- `mysqli`
- `mbstring`
- `curl`
- `fileinfo`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`

### Verificar requisitos:

```bash
# Verificar versión de PHP
php -v

# Verificar extensiones PHP instaladas
php -m

# Verificar Composer
composer -V

# Verificar MySQL
mysql --version

# Verificar Node.js y npm
node -v
npm -v
```

### Instalación de requisitos:

**Windows:**
- PHP: [XAMPP](https://www.apachefriends.org/) o [Laragon](https://laragon.org/)
- Composer: [getcomposer.org](https://getcomposer.org/download/)
- MySQL: Incluido en XAMPP/Laragon
- Node.js: [nodejs.org](https://nodejs.org/)

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install php8.2 php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip
sudo apt install composer
sudo apt install mysql-server
sudo apt install nodejs npm
```

**macOS:**
```bash
brew install php@8.2
brew install composer
brew install mysql
brew install node
```

---

## 🚀 Instalación

Sigue estos pasos **en orden** para clonar y ejecutar el proyecto:

### 1. Clonar el repositorio
```bash
git clone https://github.com/lkmark956/MangUP.git
cd MangUP/tienda
```

### 2. Instalar dependencias de PHP
```bash
composer install
```

> **⚠️ Importante:** Si `composer install` falla o da errores de archivos faltantes, elimina el directorio `vendor` y vuelve a instalar:
> ```bash
> # En Windows:
> rmdir /s /q vendor
> composer install
> 
> # En Linux/Mac:
> rm -rf vendor
> composer install
> ```

> **Nota:** `composer install` instalará automáticamente todas las dependencias necesarias, incluyendo la librería `stripe/stripe-php` para procesamiento de pagos.

### 3. Instalar dependencias de Node.js
```bash
npm install
```

### 4. Configurar el archivo de entorno
```bash
# En Windows (cmd):
copy .env.example .env

# En Windows (PowerShell):
cp .env.example .env

# En Linux/Mac:
cp .env.example .env
```

### 5. Generar la clave de aplicación
```bash
php artisan key:generate
```

### 6. Crear la base de datos
Abre MySQL y ejecuta:
```sql
CREATE DATABASE mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Configurar la base de datos
Edita el archivo `.env` con tus credenciales de MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangup_db
DB_USERNAME=root
DB_PASSWORD=tu_password_mysql
```

### 8. Configurar Stripe (Sistema de Pagos)
**⚠️ OBLIGATORIO:** El sistema de pagos requiere claves de Stripe válidas.

#### Paso 1: Obtener claves de Stripe
1. Crea una cuenta **gratuita** en [Stripe](https://stripe.com)
2. Ve al [Dashboard de Stripe (Modo Test)](https://dashboard.stripe.com/test/apikeys)
3. **Asegúrate de estar en modo "Test"** (interruptor en la esquina superior izquierda)
4. Copia tu **Publishable key** (empieza con `pk_test_...`)
5. Copia tu **Secret key** (empieza con `sk_test_...`) - haz clic en "Reveal test key"

#### Paso 2: Configurar en .env
Edita el archivo `.env` y añade tus claves:
```env
STRIPE_KEY=pk_test_TuClavePublicaAqui
STRIPE_SECRET=sk_test_TuClaveSecretaAqui
STRIPE_WEBHOOK_SECRET=
```

**✅ Checklist importante:**
- [ ] Las claves **NO** tienen espacios al inicio ni al final
- [ ] `STRIPE_KEY` empieza con `pk_test_` (modo prueba)
- [ ] `STRIPE_SECRET` empieza con `sk_test_` (modo prueba)
- [ ] Las claves están copiadas completamente sin errores

**⚠️ Seguridad:**
- ❌ **NUNCA** subas tus claves reales a GitHub
- ✅ El archivo `.env.example` solo contiene placeholders
- ✅ Para producción, usa claves que empiezan con `pk_live_` y `sk_live_`

> **Nota técnica:** El proyecto usa **Laravel 11+** con configuración moderna de Stripe. Las claves se configuran en `config/services.php` y se pasan como parámetro en cada llamada a la API.

### 9. Ejecutar migraciones y seeders
```bash
php artisan migrate --seed
```

Esto creará todas las tablas necesarias y cargará datos de prueba (productos, categorías, etc.).

### 10. Crear enlace simbólico para imágenes
```bash
php artisan storage:link
```

### 11. Limpiar y configurar caché
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 12. Compilar assets (CSS/JS)

**Para desarrollo:**
```bash
npm run dev
```

**Para producción:**
```bash
npm run build
```

### 13. Iniciar el servidor

**Opción 1 - Laravel Artisan (recomendado):**
```bash
php artisan serve
```
✅ Accede a: `http://localhost:8000`

**Opción 2 - Servidor PHP built-in:**
```bash
cd public
php -S 127.0.0.1:9500 router.php
```
✅ Accede a: `http://127.0.0.1:9500`

**Opción 3 - XAMPP/WAMP:**
- Configura el DocumentRoot a la carpeta `public` del proyecto
- Accede a: `http://localhost`

### 14. Verificar instalación (Opcional)

Ejecuta este script para verificar que Stripe está configurado correctamente:

```bash
php test-stripe-api.php
```

**Salida esperada:**
```
✅ Clase Stripe\Stripe encontrada
✅ Clase Stripe\Checkout\Session encontrada
✅ Clase Stripe\PaymentIntent encontrada
🎉 Todas las clases de Stripe están disponibles!
```

### 15. Probar la aplicación

1. Abre el navegador y ve a `http://localhost:8000`
2. Explora el catálogo de productos
3. Añade productos al carrito
4. Procede al checkout

**Para probar pagos con Stripe (modo test):**
- 💳 Tarjeta de prueba: `4242 4242 4242 4242`
- 📅 Fecha: Cualquier fecha futura (ej: `12/34`)
- 🔐 CVV: Cualquier 3 dígitos (ej: `123`)
- 📧 Email: Cualquier email válido

---

## 🔧 Solución de Problemas (Troubleshooting)

### ❌ Error: "Failed to open stream: No such file or directory" (Vendor corrupto)

**Síntoma:** Errores como:
```
include(C:\...\vendor\stripe\stripe-php\lib\Checkout\Session.php): 
Failed to open stream: No such file or directory
```

**Causa:** El directorio `vendor` está corrupto, incompleto o contiene archivos de otra instalación.

**Solución (Windows):**
```bash
# Eliminar completamente el directorio vendor
rmdir /s /q vendor

# Reinstalar todas las dependencias
composer install

# Limpiar caché
php artisan config:clear
php artisan cache:clear

# Reiniciar el servidor
php artisan serve
```

**Solución (Linux/Mac):**
```bash
# Eliminar completamente el directorio vendor
rm -rf vendor

# Reinstalar todas las dependencias
composer install

# Limpiar caché
php artisan config:clear
php artisan cache:clear

# Reiniciar el servidor
php artisan serve
```

---

### ❌ Error: "Class 'Stripe\Checkout\Session' not found"

**Síntoma:** Al intentar hacer un pago aparece el error `Class "Stripe\Checkout\Session" not found` o `Unexpected token '<', "<!DOCTYPE "... is not valid JSON`.

**Causa:** La librería de Stripe no está instalada o el autoloader no está actualizado.

**Solución:**
```bash
# Verificar que Stripe esté instalado
composer show stripe/stripe-php

# Si no está instalado o da error, reinstálalo
composer remove stripe/stripe-php
composer require stripe/stripe-php

# Regenerar autoloader
composer dump-autoload

# Limpiar caché
php artisan config:clear
php artisan cache:clear

# Reiniciar el servidor
php artisan serve
```

---

### ❌ Error: "Stripe API key configuration issue"

**Síntoma:** El formulario de pago se rompe, no carga, o muestra errores de configuración de Stripe.

**Solución paso a paso:**

#### 1️⃣ Verificar que las claves de Stripe estén configuradas correctamente

Abre el archivo `.env` y verifica que las claves de Stripe estén presentes **sin espacios**:
```env
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxx
```

**Validaciones importantes:**
- [ ] Las claves **NO** tienen espacios al inicio ni al final
- [ ] `STRIPE_KEY` empieza con `pk_test_` (modo test) o `pk_live_` (producción)
- [ ] `STRIPE_SECRET` empieza con `sk_test_` (modo test) o `sk_live_` (producción)
- [ ] Las claves están completas (51+ caracteres para la secret key)

#### 2️⃣ Limpiar caché de configuración

Laravel cachea la configuración. Debes limpiarla siempre que modifiques `.env`:
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

#### 4️⃣ Verificar en el navegador

1. Abre las **Herramientas de Desarrollo** (F12)
2. Ve a la pestaña **Console**
3. Busca errores JavaScript relacionados con Stripe
4. Ve a la pestaña **Network** y busca peticiones a `/create-checkout-session` que fallen

#### 5️⃣ Verificar que las claves de Stripe sean válidas

Puedes probar las claves directamente en el Dashboard de Stripe:
- Ve a https://dashboard.stripe.com/test/apikeys
- Copia las claves de nuevo y reemplázalas en el `.env`
- **Asegúrate de estar en modo "Test" (no "Live")**

#### 6️⃣ Reiniciar el servidor completamente

```bash
# Detén el servidor (Ctrl+C)
# Limpia caché nuevamente
php artisan config:clear

# Inicia el servidor de nuevo
php artisan serve
```

---

### ❌ Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Síntoma:** No puede conectarse a la base de datos.

**Solución:**
1. Verifica que MySQL esté ejecutándose
2. Verifica las credenciales en el archivo `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangup_db
DB_USERNAME=root
DB_PASSWORD=tu_password_real
```
3. Asegúrate de que la base de datos `mangup_db` existe:
```sql
CREATE DATABASE mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
4. Limpia la configuración:
```bash
php artisan config:clear
```

---

### ❌ Error: "Storage not linked" (Imágenes no se ven)

**Síntoma:** Las imágenes de productos no se muestran o aparecen rotas.

**Solución:**
```bash
php artisan storage:link
```

Esto crea un enlace simbólico desde `public/storage` a `storage/app/public`.

---

### ❌ Error: "npm install" falla

**Síntoma:** Errores al instalar dependencias de Node.js.

**Solución:**
```bash
# Eliminar node_modules y package-lock.json
rm -rf node_modules package-lock.json  # Linux/Mac
rmdir /s /q node_modules && del package-lock.json  # Windows

# Reinstalar
npm install

# Si persiste, actualiza npm
npm install -g npm@latest
```

---

### ❌ Permisos de storage (Linux/Mac)

**Síntoma:** Errores de permisos al escribir en `storage` o `bootstrap/cache`.

**Solución:**
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

---

### 🧪 Comandos útiles de diagnóstico

```bash
# Ver versión de PHP
php -v

# Ver versión de Composer
composer -V

# Ver versión de Node.js
node -v

# Ver extensiones PHP instaladas
php -m

# Verificar configuración de Laravel
php artisan about

# Ver rutas disponibles
php artisan route:list

# Verificar integridad de Stripe
php test-stripe-api.php

# Limpiar todo el caché
php artisan optimize:clear

# Ver logs en tiempo real
tail -f storage/logs/laravel.log  # Linux/Mac
type storage\logs\laravel.log     # Windows
```

---

### 💡 Mejores prácticas

1. **Siempre limpia el caché** después de modificar el archivo `.env`:
   ```bash
   php artisan config:clear
   ```

2. **No modifiques archivos en `vendor`:** Si necesitas cambiar algo en una dependencia, usa Composer para ello.

3. **Usa git para rastrear cambios:** Antes de hacer cambios importantes, haz commit de tu trabajo:
   ```bash
   git add .
   git commit -m "Descripción de cambios"
   ```

4. **Mantén actualizadas las dependencias:**
   ```bash
   composer update
   npm update
   ```

5. **Usa variables de entorno:** Nunca hardcodees claves API o passwords en el código.

6. **Revisa los logs:** Los logs de Laravel (`storage/logs/laravel.log`) contienen información valiosa sobre errores.

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

**Si acabas de clonar el proyecto en otro PC**, asegúrate de hacer TODO esto en orden:

- [ ] **1.** `composer install` → Instala dependencias PHP (incluye Stripe)
- [ ] **2.** `npm install` → Instala dependencias Node.js
- [ ] **3.** `cp .env.example .env` → Copia el archivo de configuración
- [ ] **4.** `php artisan key:generate` → Genera clave de aplicación
- [ ] **5.** Editar `.env` → Configurar base de datos MySQL
- [ ] **6.** Editar `.env` → Añadir tus claves de Stripe (obtenerlas de https://dashboard.stripe.com/test/apikeys)
- [ ] **7.** Crear base de datos `mangup_db` en MySQL
- [ ] **8.** `php artisan migrate` → Crear tablas
- [ ] **9.** `php artisan db:seed` → Insertar datos de prueba
- [ ] **10.** `php artisan config:clear && php artisan cache:clear` → Limpiar caché
- [ ] **11.** `npm run dev` → Compilar assets (opcional)
- [ ] **12.** `php artisan serve` → Iniciar servidor
- [ ] **13.** Acceder a http://localhost:8000 y probar

**⚠️ IMPORTANTE:** Cada persona debe configurar su propio archivo `.env` con:
- Sus credenciales de MySQL locales
- Sus propias claves de Stripe (gratuitas en modo test)

El resto del código **SÍ funcionará automáticamente** sin cambios.

---

## �📁 Estructura del Proyecto
## 🔐 Credenciales de Acceso

### Usuario Administrador
- **Email:** admin@mangup.com
- **Contraseña:** admin123
- **Panel de administración:** http://localhost:8000/admin

---

## 📁 Estructura del Proyecto

```
tienda/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Controladores del panel admin
│   │   │   ├── Auth/               # Autenticación
│   │   │   ├── ProductoController.php
│   │   │   ├── CarritoController.php
│   │   │   ├── CheckoutController.php
│   │   │   └── CuentaController.php
│   │   └── Middleware/
│   │       └── IsAdmin.php         # Middleware de autorización admin
│   └── Models/                     # Modelos Eloquent
│       ├── User.php
│       ├── Manga.php
│       ├── Figura.php
│       ├── Merch.php
│       ├── MerchVariante.php
│       ├── Pedido.php
│       └── ...
├── database/
│   ├── migrations/                 # Esquema de base de datos
│   └── seeders/                    # Datos de prueba
├── resources/
│   ├── views/                      # Vistas Blade
│   │   ├── admin/                  # Panel de administración
│   │   ├── productos/              # Catálogo de productos
│   │   ├── carrito/                # Carrito de compras
│   │   ├── cuenta/                 # Mi cuenta
│   │   └── auth/                   # Login/Registro
│   └── css/                        # Estilos CSS
├── public/
│   ├── css/                        # CSS compilado
│   ├── productos/                  # Imágenes de productos
│   └── images/                     # Recursos visuales
└── routes/
    └── web.php                     # Definición de rutas
```

---

## 🗃️ Estructura de la Base de Datos

### Tablas principales:

| Tabla | Descripción |
|-------|-------------|
| `users` | Usuarios del sistema (clientes y administradores) |
| `categorias_manga` | Géneros de manga (Acción, Romance, Terror, etc.) |
| `categorias_figura` | Series de figuras (One Piece, Naruto, etc.) |
| `categorias_merch` | Tipos de merch (Camisetas, Tazas, etc.) |
| `mangas` | Productos de tipo manga |
| `figuras` | Productos de tipo figura |
| `merchs` | Productos de merchandising |
| `merch_variantes` | Variantes de talla/color para merch |
| `tallas` | Tallas disponibles (XS, S, M, L, XL, XXL) |
| `colores` | Colores disponibles con código hexadecimal |
| `imagenes` | Galería de imágenes (relación polimórfica) |
| `pedidos` | Órdenes de compra de los clientes |
| `pedido_items` | Detalles de productos en cada pedido |
| `direcciones` | Direcciones de envío de los usuarios |

### Diagrama de relaciones:

```
users (1) ─────────────────< (N) pedidos
users (1) ─────────────────< (N) direcciones

categorias_manga (1) ──────< (N) mangas
categorias_figura (1) ─────< (N) figuras
categorias_merch (1) ──────< (N) merchs

merchs (1) ────────────────< (N) merch_variantes
tallas (1) ────────────────< (N) merch_variantes
colores (1) ───────────────< (N) merch_variantes

pedidos (1) ───────────────< (N) pedido_items
mangas/figuras/merchs ─────< (N) pedido_items (polimórfica)

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

## 🎯 Casos de Uso del Sistema

### 👤 Usuario Cliente (No autenticado)

1. **Explorar Catálogo de Productos**
   - Ver todos los productos disponibles (mangas, figuras, merch)
   - Filtrar productos por tipo, categoría y precio
   - Ordenar productos por nombre, precio o fecha
   - Buscar productos por nombre

2. **Ver Detalles de Producto**
   - Visualizar información completa del producto
   - Ver galería de imágenes
   - Consultar stock disponible
   - Ver productos relacionados

3. **Gestionar Carrito de Compras**
   - Añadir productos al carrito
   - Modificar cantidades
   - Eliminar productos
   - Ver total y resumen del carrito

4. **Registro y Autenticación**
   - Crear nueva cuenta de cliente
   - Iniciar sesión
   - Cerrar sesión

### 👤 Usuario Cliente (Autenticado)

Todos los casos del usuario no autenticado, más:

5. **Realizar Compras**
   - Proceder al checkout
   - Completar información de envío
   - Realizar pago mediante Stripe
   - Recibir confirmación de pedido

6. **Gestionar Mi Cuenta**
   - Ver y editar datos personales
   - Cambiar contraseña
   - Ver historial de pedidos
   - Gestionar direcciones de envío

### 🔒 Usuario Administrador

Todos los casos del usuario autenticado, más:

7. **Dashboard Administrativo**
   - Ver estadísticas generales del negocio
   - Consultar totales de productos por tipo
   - Identificar productos con stock bajo
   - Visualizar pedidos pendientes

8. **Gestión de Productos (CRUD)**
   - Crear nuevos mangas, figuras y merchandising
   - Editar información de productos existentes
   - Actualizar stock y precios
   - Eliminar productos
   - Subir y gestionar imágenes múltiples por producto

9. **Gestión de Variantes de Merch**
   - Crear variantes de talla y color
   - Asignar stock específico a cada variante
   - Editar y eliminar variantes

10. **Gestión de Categorías**
    - Crear categorías de manga, figuras y merch
    - Editar información de categorías
    - Eliminar categorías (si no tienen productos)

11. **Gestión de Usuarios**
    - Ver lista de todos los usuarios
    - Buscar usuarios por nombre o email
    - Otorgar o revocar permisos de administrador
    - Editar información de usuarios
    - Eliminar cuentas de usuario

12. **Control de Pedidos**
    - Ver todos los pedidos realizados
    - Filtrar pedidos por estado
    - Ver detalles completos de cada pedido
    - Actualizar estado de pedidos

---

## 📊 Diagrama de Casos de Uso

```
                    Sistema MangUP
  ┌─────────────────────────────────────────────┐
  │                                             │
  │  [Explorar Catálogo]                        │◄───── Usuario Visitante
  │  [Ver Detalles Producto]                    │
  │  [Gestionar Carrito]                        │
  │  [Registrarse / Iniciar Sesión]             │
  │                                             │
  │  ─────────────────────────────────          │
  │                                             │
  │  [Realizar Compra]                          │◄───── Usuario Registrado
  │  [Ver Mis Pedidos]                          │       (extends: Visitante)
  │  [Gestionar Mi Cuenta]                      │
  │  [Gestionar Direcciones]                    │
  │                                             │
  │  ─────────────────────────────────          │
  │                                             │
  │  [Dashboard Admin]                          │◄───── Administrador
  │  [CRUD Productos]                           │       (extends: Registrado)
  │  [CRUD Categorías]                          │
  │  [Gestión de Usuarios]                      │
  │  [Control de Pedidos]                       │
  │  [Gestión de Stock]                         │
  │                                             │
  │  ─────────────────────────────────          │
  │                                             │
  │  [Procesar Pago]                            │◄───── Sistema Stripe
  │  [Enviar Confirmación]                      │       (procesamiento externo)
  │                                             │
  └─────────────────────────────────────────────┘
```

---

## 🔄 Flujo de Navegación del Usuario

### Cliente realizando una compra

```
1. Inicio → Ver Productos → [Catálogo]
                                  ↓
2. Seleccionar Producto → [Detalle Producto]
                                  ↓
3. Añadir al Carrito → [Carrito de Compras]
                                  ↓
4. ¿Usuario registrado?
   NO → [Registro/Login] → Continuar
   SÍ → Continuar
                                  ↓
5. Checkout → [Formulario de Envío]
                                  ↓
6. Procesar Pago → [Stripe Payment]
                                  ↓
7. Confirmación → [Pedido Exitoso]
                                  ↓
8. Mi Cuenta → [Ver Mis Pedidos]
```

### Administrador gestionando inventario

```
1. Login Admin → [/admin]
                     ↓
2. Dashboard → [Estadísticas y Resumen]
                     ↓
3. Gestión de Productos → [Lista de Productos]
                     ↓
4. Crear/Editar → [Formulario CRUD]
                     ↓
5. Guardar → [Confirmar Cambios]
                     ↓
6. Ver Stock Bajo → [Alertas de Inventario]
```

---

## 🛠️ Funcionalidades Principales

### 🛍️ Catálogo de Productos
- **Grid responsivo** con diseño moderno tipo tarjeta
- **Filtros avanzados** por tipo, categoría, precio
- **Ordenamiento** por precio, nombre, fecha de ingreso
- **Barra de búsqueda** en tiempo real
- **Paginación** de resultados

### 🛒 Sistema de Carrito
- Carrito persistente en **sesión de usuario**
- Actualización dinámica de cantidades
- Validación de stock disponible
- Cálculo automático de totales
- Resumen visual del carrito

### 💳 Checkout y Pagos
- Integración con **Stripe Payment Gateway**
- Formulario de dirección de envío
- Resumen detallado del pedido
- Confirmación por email (estructura lista)
- Historial de pedidos en Mi Cuenta

### 🔐 Autenticación y Usuarios
- Registro de nuevos clientes
- Login/Logout seguro
- Gestión de perfil personal
- Cambio de contraseña
- Direcciones de envío múltiples

### 👨‍💼 Panel de Administración
- **Dashboard** con estadísticas en tiempo real
- CRUD completo de productos (Mangas, Figuras, Merch)
- CRUD de categorías por tipo de producto
- Gestión de usuarios con asignación de roles
- Control de pedidos y estados
- Alertas de stock bajo (< 5 unidades)
- Subida múltiple de imágenes
- Interfaz moderna y responsive

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
php artisan db:seed --class=AdminUserSeeder

# Crear enlace simbólico para storage
php artisan storage:link

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Iniciar servidor de desarrollo
php artisan serve

# Compilar assets (CSS/JS)
npm run dev         # Desarrollo
npm run build       # Producción
```

---

## 🔧 Tecnologías Utilizadas

### Backend
- **Laravel 12** - Framework PHP moderno
- **MySQL 8.0** - Base de datos relacional
- **Stripe PHP SDK** - Procesamiento de pagos

### Frontend
- **Blade Templates** - Motor de plantillas de Laravel
- **Bootstrap 5** - Framework CSS responsive
- **Bootstrap Icons** - Iconos vectoriales
- **Vanilla JavaScript** - Interactividad del cliente

### Herramientas de Desarrollo
- **Composer** - Gestor de dependencias PHP
- **NPM** - Gestor de paquetes Node.js
- **Vite** - Compilador de assets moderno
- **Git** - Control de versiones

---

## 📂 Modelos y Relaciones Principales

### User (Usuario)
```php
- Relaciones:
  - hasMany(Pedido)
  - hasMany(Direccion)
- Métodos:
  - isAdmin(): bool
```

### Producto (Manga | Figura | Merch)
```php
- Relaciones:
  - belongsTo(Categoria)
  - morphMany(Imagen)
  - morphMany(PedidoItem)
```

### Pedido
```php
- Relaciones:
  - belongsTo(User)
  - belongsTo(Direccion)
  - hasMany(PedidoItem)
- Estados:
  - pendiente, pagado, enviado, entregado, cancelado
```

### MerchVariante
```php
- Relaciones:
  - belongsTo(Merch)
  - belongsTo(Talla)
  - belongsTo(Color)
- Campos:
  - stock_individual por variante
```

---

## 🔒 Seguridad Implementada

- ✅ **Autenticación** mediante Laravel Breeze
- ✅ **Middleware IsAdmin** para proteger rutas administrativas
- ✅ **CSRF Protection** en todos los formularios
- ✅ **Validación de datos** en formularios
- ✅ **Hashing de contraseñas** con bcrypt
- ✅ **Autorización por roles** (admin/cliente)
- ✅ **Validación de stock** antes de permitir compras
- ✅ **`.env` en .gitignore** - Las claves sensibles NO se suben a Git

---

## 📤 Subir cambios a Git

### ⚠️ ANTES de hacer commit, verifica:

```bash
# Asegúrate de que .env NO esté incluido
git status

# Si ves .env en la lista, añádelo al .gitignore
echo .env >> .gitignore
```

### ✅ Archivos que SÍ se deben subir:
- ✅ Todo el código fuente (`app/`, `resources/`, `routes/`, etc.)
- ✅ `composer.json` y `composer.lock`
- ✅ `package.json` y `package-lock.json`
- ✅ `.env.example` (ejemplo de configuración)
- ✅ `.gitignore`
- ✅ Migraciones y Seeders
- ✅ Archivos de configuración (`config/`)

### ❌ Archivos que NO se deben subir:
- ❌ `.env` (contiene claves secretas)
- ❌ `/vendor/` (se instala con `composer install`)
- ❌ `/node_modules/` (se instala con `npm install`)
- ❌ `/storage/logs/*.log`
- ❌ `/public/build/` (se genera con `npm run dev`)
- ❌ IDE config (`.vscode/`, `.idea/`)

El archivo `.gitignore` ya está configurado correctamente para ignorar estos archivos.

### 🚀 Comandos básicos de Git

```bash
# Ver cambios
git status

# Añadir todos los cambios
git add .

# Hacer commit
git commit -m "Descripción de los cambios"

# Subir a GitHub
git push origin main

# Verificar que .env NO fue incluido
git ls-files | grep .env
# (No debería mostrar nada)
```

---

## 📝 Notas de Desarrollo

### Base de Datos Persistente
La base de datos MySQL es **persistente** y mantiene todos los datos entre reinicios del servidor. Los usuarios creados, productos añadidos y pedidos realizados se conservan permanentemente.

### Usuario Administrador
El seeder `AdminUserSeeder` crea automáticamente un usuario administrador al ejecutar `php artisan db:seed`. Este usuario puede:
- Acceder al panel de administración en `/admin`
- Gestionar productos, categorías y usuarios
- Ver y controlar pedidos
- Otorgar permisos de administrador a otros usuarios

### Extensiones PHP Necesarias
Asegúrate de tener habilitadas las siguientes extensiones en tu `php.ini`:
- `extension=openssl` - Para cifrado y Stripe
- `extension=pdo_mysql` - Para conexión a MySQL
- `extension=mysqli` - Driver MySQL adicional
- `extension=mbstring` - Para cadenas multibyte
- `extension=curl` - Para peticiones HTTP (Stripe)
- `extension=fileinfo` - Para gestión de archivos
- `extension_dir = "ext"` - Directorio de extensiones (Windows)

### Stripe en Modo Test
El proyecto está configurado para usar **Stripe en modo test**. Las claves deben configurarse en el archivo `.env`:
```env
STRIPE_KEY=pk_test_TuClavePublicaDeStripe
STRIPE_SECRET=sk_test_TuClaveSecretaDeStripe
STRIPE_WEBHOOK_SECRET=
```

**Arquitectura de Stripe (Laravel 12+):**
- Las claves se cargan desde `config/services.php`
- Se pasan como parámetro `['api_key' => config('services.stripe.secret')]` en cada llamada
- Evita problemas de estado global con middlewares
- Sigue las mejores prácticas de Laravel moderno

**Tarjetas de prueba para testing:**
- **✅ Pago exitoso:** 4242 4242 4242 4242
- **❌ Pago rechazado:** 4000 0000 0000 0002
- **⏳ Requiere autenticación:** 4000 0025 0000 3155
- **CVC:** Cualquier 3 dígitos
- **Fecha:** Cualquier fecha futura
- **Código postal:** Cualquier número

[Ver más tarjetas de prueba](https://docs.stripe.com/testing#cards)

---

¡Explora MangUP y disfruta comprando tus mangas y productos anime favoritos! 🎌📚✨
