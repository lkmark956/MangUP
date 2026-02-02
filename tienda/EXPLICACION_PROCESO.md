# 📚 MangUP - Documentación del Proceso de Desarrollo

## Índice
1. [Middleware de Administrador](#1-middleware-de-administrador)
2. [Dashboard con Estadísticas](#2-dashboard-con-estadísticas)
3. [Gestión de Productos (CRUD)](#3-gestión-de-productos-crud)
4. [Gestión de Categorías](#4-gestión-de-categorías)
5. [Gestión de Usuarios](#5-gestión-de-usuarios)
6. [Configuración de la Tienda](#6-configuración-de-la-tienda)

---

## 1. Middleware de Administrador

### ¿Qué es un Middleware?
Un **middleware** en Laravel es una capa intermedia que intercepta las peticiones HTTP **antes** de que lleguen al controlador. Actúa como un "guardia de seguridad" que decide si la petición puede continuar o debe ser rechazada/redirigida.

### Flujo de una petición con Middleware:
```
Usuario → Petición HTTP → Middleware 1 → Middleware 2 → ... → Controlador → Respuesta
                              ↓              ↓
                          ¿Pasa?         ¿Pasa?
                          No → Rechaza   No → Rechaza
```

### ¿Por qué necesitamos un Middleware de Admin?
- **Seguridad**: Solo usuarios autorizados pueden acceder al panel
- **Separación de roles**: Diferenciamos entre clientes normales y administradores
- **Reutilización**: Aplicamos la misma lógica a todas las rutas de admin sin repetir código

### Implementación técnica:

#### Paso 1: Migración para añadir campo `is_admin`
Creamos una migración que añade un campo booleano `is_admin` a la tabla `users`.

**Archivo**: `database/migrations/xxxx_add_is_admin_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->boolean('is_admin')->default(false)->after('password');
});
```

**Explicación**:
- `boolean('is_admin')`: Crea un campo de tipo booleano (true/false)
- `default(false)`: Por defecto, los usuarios NO son admin
- `after('password')`: Se coloca después del campo password en la tabla

#### Paso 2: Crear el Middleware `IsAdmin`
**Archivo**: `app/Http/Middleware/IsAdmin.php`

```php
public function handle(Request $request, Closure $next): Response
{
    // Verificar si el usuario está autenticado Y es admin
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Acceso denegado. No tienes permisos de administrador.');
    }
    
    return $next($request); // Continúa con la petición
}
```

**Explicación**:
- `auth()->check()`: Verifica si hay un usuario logueado
- `auth()->user()->is_admin`: Accede al campo is_admin del usuario
- `abort(403)`: Devuelve error HTTP 403 (Forbidden)
- `$next($request)`: Pasa la petición al siguiente middleware o controlador

#### Paso 3: Registrar el Middleware
**Archivo**: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\IsAdmin::class,
    ]);
})
```

**Explicación**:
- Registramos el middleware con el alias `'admin'`
- Esto nos permite usar `middleware('admin')` en las rutas

#### Paso 4: Definir las Rutas de Admin
**Archivo**: `routes/web.php`

```php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // Más rutas...
});
```

**Explicación**:
- `middleware(['auth', 'admin'])`: Aplica ambos middlewares (primero auth, luego admin)
- `prefix('admin')`: Todas las rutas empiezan con `/admin`
- `name('admin.')`: Los nombres de ruta empiezan con `admin.`

### Diagrama de flujo completo:
```
Usuario entra a /admin/productos
         ↓
    Middleware 'auth'
    ¿Está logueado?
         ↓
    No → Redirige a /login
    Sí ↓
    Middleware 'admin'  
    ¿user->is_admin == true?
         ↓
    No → Error 403 "Acceso denegado"
    Sí ↓
    Controlador AdminController
         ↓
    Vista del panel de admin
```

---

## 2. Dashboard con Estadísticas

### ¿Qué es el Dashboard?
El **Dashboard** es la página principal del panel de administración. Muestra un resumen rápido del estado de la tienda con estadísticas y métricas importantes.

### Estadísticas implementadas:
1. **Total de productos**: Suma de mangas + figuras + merchandising
2. **Productos sin stock**: Productos que necesitan reposición
3. **Total de usuarios**: Número de usuarios registrados
4. **Productos de cada tipo**: Cantidad específica por categoría

### Implementación técnica:

**Archivo**: `app/Http/Controllers/Admin/DashboardController.php`

```php
public function index()
{
    // Conteo de productos
    $totalMangas = Manga::count();
    $totalFiguras = Figura::count();
    $totalMerch = Merch::count();
    $totalProductos = $totalMangas + $totalFiguras + $totalMerch;
    
    // Productos sin stock
    $sinStock = Manga::where('stock', 0)->count()
              + Figura::where('stock', 0)->count()
              + Merch::whereDoesntHave('variantes', function($q) {
                    $q->where('stock', '>', 0);
                })->count();
    
    // Total usuarios
    $totalUsuarios = User::count();
    
    return view('admin.dashboard', compact(
        'totalProductos', 'sinStock', 'totalUsuarios',
        'totalMangas', 'totalFiguras', 'totalMerch'
    ));
}
```

**Explicación**:
- `Manga::count()`: Cuenta el total de registros en la tabla
- `compact()`: Crea un array con las variables para pasarlas a la vista
- La vista usa **tarjetas Bootstrap** con iconos para mostrar cada estadística

---

## 3. Gestión de Productos (CRUD)

### ¿Qué es CRUD?
**CRUD** son las operaciones básicas de cualquier sistema:
- **C**reate (Crear)
- **R**ead (Leer/Listar)
- **U**pdate (Actualizar)
- **D**elete (Eliminar)

### Controladores Resource
Laravel proporciona **Resource Controllers** que automatizan el CRUD con 7 métodos:

| Método    | Verbo HTTP | URI                  | Acción                    |
|-----------|------------|----------------------|---------------------------|
| index     | GET        | /admin/mangas        | Listar todos              |
| create    | GET        | /admin/mangas/create | Formulario de crear       |
| store     | POST       | /admin/mangas        | Guardar nuevo             |
| show      | GET        | /admin/mangas/{id}   | Ver detalle               |
| edit      | GET        | /admin/mangas/{id}/edit | Formulario de editar  |
| update    | PUT/PATCH  | /admin/mangas/{id}   | Actualizar existente      |
| destroy   | DELETE     | /admin/mangas/{id}   | Eliminar                  |

### Implementación del MangaController

**Archivo**: `app/Http/Controllers/Admin/MangaController.php`

#### Método index() - Listar
```php
public function index(Request $request)
{
    $query = Manga::with('categoria', 'imagenes');
    
    // Búsqueda por nombre
    if ($request->filled('buscar')) {
        $query->where('nombre', 'like', '%' . $request->buscar . '%');
    }
    
    // Ordenamiento dinámico
    $orden = $request->get('orden', 'created_at');
    $direccion = $request->get('dir', 'desc');
    $query->orderBy($orden, $direccion);
    
    // Paginación (10 por página)
    $mangas = $query->paginate(10)->withQueryString();
    
    return view('admin.mangas.index', compact('mangas'));
}
```

**Explicación**:
- `with('categoria', 'imagenes')`: Eager loading para evitar N+1 queries
- `$request->filled('buscar')`: Verifica si el parámetro existe y no está vacío
- `paginate(10)`: Divide los resultados en páginas de 10
- `withQueryString()`: Mantiene los parámetros de búsqueda al paginar

#### Método store() - Crear
```php
public function store(Request $request)
{
    // 1. Validación
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categoria_manga_id' => 'required|exists:categorias_manga,id',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);
    
    // 2. Crear el manga
    $manga = Manga::create([...]);
    
    // 3. Guardar imagen (si existe)
    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('productos/mangas', 'public');
        Imagen::create([
            'ruta' => 'storage/' . $path,
            'es_principal' => true,
            'imageable_type' => Manga::class,
            'imageable_id' => $manga->id,
        ]);
    }
    
    // 4. Redirigir con mensaje
    return redirect()->route('admin.mangas.index')
                     ->with('success', 'Manga creado correctamente.');
}
```

**Explicación**:
- **Validación**: Laravel automáticamente rechaza peticiones inválidas
- **Relación polimórfica**: Las imágenes usan `imageable_type` e `imageable_id` para relacionarse con cualquier modelo
- **Flash message**: `with('success', ...)` muestra un mensaje temporal en la siguiente página

### Subida de Imágenes
Las imágenes se guardan en `storage/app/public/productos/` y son accesibles vía el symlink `public/storage`.

```php
// Guardar archivo
$path = $request->file('imagen')->store('productos/mangas', 'public');
// Resultado: "productos/mangas/abc123.jpg"

// URL pública
asset('storage/' . $path);
// Resultado: "http://localhost/storage/productos/mangas/abc123.jpg"
```

---

## 4. Gestión de Categorías

### Estructura Polimórfica de Categorías
El sistema tiene 3 tipos de categorías independientes:
- `categorias_manga` → Para mangas
- `categorias_figura` → Para figuras
- `categorias_merch` → Para merchandising

### Controlador Dinámico
El `CategoriaController` maneja los 3 tipos con un solo controlador usando un parámetro `$tipo`:

```php
private function getModelo($tipo)
{
    return match($tipo) {
        'manga' => CategoriaManga::class,
        'figura' => CategoriaFigura::class,
        'merch' => CategoriaMerch::class,
        default => abort(404)
    };
}
```

**Rutas**:
- `/admin/categorias/manga` → Lista categorías de manga
- `/admin/categorias/figura` → Lista categorías de figura
- `/admin/categorias/merch` → Lista categorías de merch

---

## 5. Gestión de Usuarios

### Funcionalidades Implementadas:
1. **Listar usuarios** con búsqueda y filtros
2. **Crear nuevos usuarios** (con opción de admin)
3. **Editar usuarios** existentes
4. **Cambiar contraseña** 
5. **Promover/degradar** administradores
6. **Eliminar usuarios** (con protección)

### Protecciones de Seguridad:
- Un admin **no puede eliminarse a sí mismo**
- Un admin **no puede quitarse sus propios permisos**
- Las contraseñas se **encriptan con Hash**

```php
// Toggle admin
public function toggleAdmin(User $usuario)
{
    if ($usuario->id === auth()->id()) {
        return back()->with('error', 'No puedes cambiar tu propio estado.');
    }
    
    $usuario->is_admin = !$usuario->is_admin;
    $usuario->save();
}
```

---

## 6. Diseño del Panel de Administración

### Paleta de Colores MangUP
Se mantiene coherencia con el diseño de la tienda principal:

```css
:root {
    --admin-primary: #E4572E;      /* Naranja FNAC */
    --admin-primary-dark: #C94A26;  /* Naranja oscuro */
    --admin-sidebar-bg: #1A1A1A;    /* Negro sidebar */
    --admin-bg: #F5F5F5;            /* Gris claro fondo */
}
```

### Estructura del Layout
- **Sidebar fijo**: Navegación lateral siempre visible
- **Header sticky**: Título de página y usuario actual
- **Área de contenido**: Donde se carga cada vista

### Fuente Tipográfica
Se usa **Poppins** de Google Fonts, igual que en la tienda:
```html
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
```

---

## 📁 Estructura de Archivos Creados

```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   ├── DashboardController.php
│   │   ├── MangaController.php
│   │   ├── FiguraController.php
│   │   ├── MerchController.php
│   │   ├── CategoriaController.php
│   │   └── UserController.php
│   └── Middleware/
│       └── IsAdmin.php
│
resources/views/admin/
├── layouts/
│   └── app.blade.php
├── dashboard.blade.php
├── mangas/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── figuras/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── merch/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── categorias/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── usuarios/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php

database/
├── migrations/
│   └── xxxx_add_is_admin_to_users_table.php
└── seeders/
    └── AdminUserSeeder.php

public/css/
└── admin.css              ← CSS separado del admin (mejores prácticas)
```

---

## 7. Arquitectura CSS (Mejores Prácticas)

### ¿Por qué separar el CSS?
En desarrollo web profesional, se recomienda **separar los estilos CSS en archivos externos** por varias razones:

1. **Mantenibilidad**: Más fácil encontrar y modificar estilos
2. **Caché del navegador**: Los archivos CSS se cachean, mejorando rendimiento
3. **Reutilización**: Un mismo CSS puede usarse en múltiples vistas
4. **Separación de responsabilidades**: HTML para estructura, CSS para presentación

### Estructura CSS del proyecto:

```
public/css/
├── variables.css      → Variables CSS globales
├── base.css           → Estilos base y reset
├── components.css     → Componentes reutilizables
├── layout.css         → Estructura general
├── products.css       → Estilos de productos
├── pages.css          → Estilos específicos de páginas
├── responsive.css     → Media queries
└── admin.css          → Estilos del panel de administración
```

### Uso de Bootstrap
El proyecto usa **Bootstrap 5.3.2** como framework CSS base:

```html
<!-- Cargado vía CDN en el layout -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
```

**Ventajas de Bootstrap:**
- Grid system responsive (12 columnas)
- Componentes predefinidos (cards, buttons, forms, tables)
- Utilidades CSS (spacing, colors, display)
- Documentación extensa

### Variables CSS Personalizadas
Usamos **CSS Custom Properties** para mantener consistencia en los colores:

```css
:root {
    --admin-primary: #E4572E;      /* Naranja MangUP */
    --admin-sidebar-bg: #1A1A1A;   /* Negro del sidebar */
    --admin-bg: #F5F5F5;           /* Fondo gris claro */
}
```

**Uso en estilos:**
```css
.btn-admin-primary {
    background: var(--admin-primary);
}
```

---

## 🔐 Credenciales de Acceso

**Usuario Administrador de Prueba:**
- Email: `admin@mangup.com`
- Contraseña: `admin123`

---

## ✅ Funcionalidades Completadas

- [x] Sistema de middleware para protección de rutas
- [x] Dashboard con estadísticas
- [x] CRUD completo de Mangas
- [x] CRUD completo de Figuras
- [x] CRUD completo de Merchandising (con variantes talla/color)
- [x] Gestión de Categorías (3 tipos)
- [x] Gestión de Usuarios
- [x] Subida de imágenes
- [x] Diseño coherente con la tienda principal
- [x] Sistema de mensajes flash (éxito/error)
- [x] Búsqueda y paginación en listados
- [x] Ordenamiento por columnas
- [x] **CSS separado en archivos externos (mejores prácticas)**

---

## 📚 Glosario Técnico

| Término | Definición |
|---------|------------|
| **Middleware** | Capa que intercepta peticiones HTTP antes del controlador |
| **Migración** | Archivo PHP que define cambios en la estructura de la base de datos |
| **CRUD** | Create, Read, Update, Delete - Operaciones básicas de datos |
| **Route** | Definición de una URL y qué controlador/acción la maneja |
| **Controller** | Clase que contiene la lógica de negocio de la aplicación |
| **View (Blade)** | Archivo de plantilla que genera el HTML |
| **Eloquent** | ORM de Laravel para interactuar con la base de datos |
| **Bootstrap** | Framework CSS para diseño responsive y componentes |
| **CSS Variables** | Propiedades personalizadas reutilizables en CSS |
| **CDN** | Content Delivery Network - Servidor externo para recursos estáticos |
| **Seeder** | Script para poblar la base de datos con datos de prueba |
| **Flash Message** | Mensaje temporal que aparece una vez tras una acción |

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| Laravel | 12.x | Framework PHP backend |
| PHP | 8.5.1 | Lenguaje de programación |
| MySQL | 8.x | Base de datos |
| Bootstrap | 5.3.2 | Framework CSS |
| Bootstrap Icons | 1.11.1 | Iconografía |
| Poppins | - | Fuente tipográfica (Google Fonts) |

---

*Documento creado: Febrero 2026*
*Proyecto: MangUP - Tienda de Manga y Anime*
