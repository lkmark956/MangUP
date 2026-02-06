# 🎌 MangUP - Tienda Online de Manga y Anime

**MangUP** es un ecommerce desarrollado con Laravel para la venta de manga, figuras coleccionables y merchandising anime.

---

## 📋 Requisitos del Sistema

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18 (para compilar assets)
- Extensiones PHP requeridas: `openssl`, `pdo_mysql`, `mysqli`, `mbstring`, `curl`, `fileinfo`

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
DB_PASSWORD=
```

### 5. Crear la base de datos
En MySQL, crea la base de datos:
```sql
CREATE DATABASE mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Ejecutar migraciones y seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Iniciar el servidor
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

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
El proyecto está configurado para usar **Stripe en modo test**. Las claves están en el archivo `.env`:
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
```

Para probar pagos, usa las tarjetas de prueba de Stripe:
- **Tarjeta exitosa:** 4242 4242 4242 4242
- **CVC:** Cualquier 3 dígitos
- **Fecha:** Cualquier fecha futura

---

¡Explora MangUP y disfruta comprando tus mangas y productos anime favoritos! 🎌📚✨
