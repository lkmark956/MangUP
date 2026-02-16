# MangUP - Tienda Online de Manga y Anime

Ecommerce desarrollado con **Laravel 11** para la venta de manga, figuras coleccionables y merchandising anime. Incluye pasarela de pago **Stripe** y panel de administración completo.

> La aplicación Laravel se encuentra en la carpeta `tienda/`. Todos los comandos deben ejecutarse desde ahí.

---

## Características

### Tienda (Cliente)
- Catálogo con filtros por tipo (manga, figura, merch), categoría, precio y ofertas
- Sistema de ofertas con descuentos porcentuales o cantidad fija
- Carrito de compras con sesión persistente
- Checkout con Stripe (tarjetas de crédito)
- IVA 21% incluido en precios
- Cuenta de usuario: datos personales, direcciones, historial de pedidos

### Panel de Administración
- **Dashboard** con estadísticas de ventas
- **Productos**: CRUD completo de mangas, figuras y merchandising
- **Categorías**: gestión por tipo de producto
- **Usuarios**: crear, editar, asignar rol administrador
- **Pedidos**: listado, detalle y cambio de estado (pendiente → completado)
- **Ofertas**: crear descuentos por porcentaje o cantidad fija, aplicables a todos los productos, por tipo o producto específico

---

## Requisitos

- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js >= 18

### Extensiones PHP
`openssl`, `pdo_mysql`, `mbstring`, `curl`, `fileinfo`, `xml`, `ctype`, `json`

---

## Instalación

```bash
# Clonar y entrar al proyecto
git clone https://github.com/lkmark956/MangUP.git
cd MangUP/tienda

# Instalar dependencias
composer install
npm install

# Configurar entorno
copy .env.example .env   # Windows
cp .env.example .env     # Linux/Mac
php artisan key:generate

# Configurar base de datos en MySQL
# CREATE DATABASE mangup_db;

# Editar .env con credenciales de MySQL y Stripe

# Migrar y sembrar datos
php artisan migrate --seed

# Enlace de storage y compilar assets
php artisan storage:link
npm run build

# Iniciar servidor
php artisan serve
```

Accede a: http://localhost:8000

---

## Configuración de Stripe

1. Regístrate en [stripe.com](https://stripe.com)
2. Ve a [Dashboard > API Keys](https://dashboard.stripe.com/test/apikeys) (modo Test)
3. Copia las claves en `.env`:

```env
STRIPE_KEY=pk_test_tu_clave_publica
STRIPE_SECRET=sk_test_tu_clave_secreta
```

### Tarjeta de prueba
| Campo | Valor |
|-------|-------|
| Número | 4242 4242 4242 4242 |
| Fecha | Cualquier fecha futura |
| CVV | Cualquier 3 dígitos |

---

## Estructura del Proyecto

```
MangUP/
└── tienda/                    # Aplicación Laravel
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── Admin/         # Controladores del panel admin
    │   │   ├── Auth/          # Login y registro
    │   │   ├── CarritoController.php
    │   │   ├── CheckoutController.php
    │   │   └── ProductoController.php
    │   └── Models/            # Modelos Eloquent
    │       ├── Manga.php
    │       ├── Figura.php
    │       ├── Merch.php
    │       ├── Oferta.php
    │       ├── Pedido.php
    │       └── User.php
    ├── database/
    │   ├── migrations/        # Esquema de BD
    │   └── seeders/           # Datos iniciales
    ├── public/                # Assets públicos
    ├── resources/views/       # Vistas Blade
    │   ├── admin/             # Panel administración
    │   ├── carrito/
    │   ├── checkout/
    │   ├── cuenta/
    │   └── productos/
    ├── routes/web.php         # Definición de rutas
    └── .env                   # Variables de entorno
```

---

## Usuario Administrador

El seeder crea un usuario admin por defecto:

| Campo | Valor |
|-------|-------|
| Email | admin@mangup.com |
| Password | admin123 |

Acceso al panel: http://localhost:8000/admin

---

## Tecnologías

| Componente | Tecnología |
|------------|------------|
| Backend | Laravel 11 |
| Frontend | Blade + Vite |
| Base de datos | MySQL 8 |
| Pagos | Stripe PHP v19 |
| CSS | Bootstrap 5 |

---

## Rutas Principales

### Públicas
| Ruta | Descripción |
|------|-------------|
| `/` | Página de inicio |
| `/productos` | Catálogo con filtros |
| `/productos/{id}` | Detalle de producto |
| `/carrito` | Ver carrito |
| `/checkout` | Proceso de pago |

### Usuario Autenticado
| Ruta | Descripción |
|------|-------------|
| `/mi-cuenta` | Datos personales |
| `/mi-cuenta/pedidos` | Historial de pedidos |
| `/mi-cuenta/direcciones` | Gestión de direcciones |

### Administración (`/admin`)
| Ruta | Descripción |
|------|-------------|
| `/admin` | Dashboard |
| `/admin/mangas` | CRUD Mangas |
| `/admin/figuras` | CRUD Figuras |
| `/admin/merch` | CRUD Merchandising |
| `/admin/categorias/{tipo}` | Gestión categorías |
| `/admin/usuarios` | Gestión usuarios |
| `/admin/pedidos` | Gestión pedidos |
| `/admin/ofertas` | Gestión ofertas |

---

## Sistema de Ofertas

Las ofertas pueden configurarse desde `/admin/ofertas`:

- **Tipo de descuento**: Porcentaje (%) o cantidad fija (€)
- **Aplicable a**: Todos los productos, solo mangas, solo figuras, solo merch, o un producto específico
- **Vigencia**: Fechas de inicio y fin opcionales
- **Activación**: Pueden activarse/desactivarse

El sistema aplica automáticamente la mejor oferta disponible a cada producto.

---

## Diagrama Entidad-Relación

```mermaid
erDiagram
    USER ||--o{ PEDIDO : realiza
    USER ||--o{ DIRECCION : tiene
    
    PEDIDO ||--|{ PEDIDO_ITEM : contiene
    PEDIDO_ITEM }o--|| MANGA : referencia
    PEDIDO_ITEM }o--|| FIGURA : referencia
    PEDIDO_ITEM }o--|| MERCH : referencia
    PEDIDO_ITEM }o--o| MERCH_VARIANTE : tiene
    
    MANGA }o--|| CATEGORIA_MANGA : pertenece
    FIGURA }o--|| CATEGORIA_FIGURA : pertenece
    MERCH }o--|| CATEGORIA_MERCH : pertenece
    MERCH ||--o{ MERCH_VARIANTE : tiene
    
    MERCH_VARIANTE }o--o| TALLA : tiene
    MERCH_VARIANTE }o--o| COLOR : tiene
    
    MANGA ||--o{ IMAGEN : tiene
    FIGURA ||--o{ IMAGEN : tiene
    MERCH ||--o{ IMAGEN : tiene
    
    OFERTA }o--o| MANGA : aplica
    OFERTA }o--o| FIGURA : aplica
    OFERTA }o--o| MERCH : aplica

    USER {
        int id PK
        string name
        string email UK
        string password
        boolean is_admin
        string foto_perfil
    }
    
    PEDIDO {
        int id PK
        int user_id FK
        string numero_pedido UK
        string estado
        decimal subtotal
        decimal impuesto
        decimal total
        string stripe_session_id
    }
    
    PEDIDO_ITEM {
        int id PK
        int pedido_id FK
        string producto_type
        int producto_id
        int variante_id FK
        string nombre_producto
        decimal precio_unitario
        int cantidad
    }
    
    MANGA {
        int id PK
        string nombre
        decimal precio
        int stock
        string autor
        string editorial
        string isbn
        int categoria_manga_id FK
    }
    
    FIGURA {
        int id PK
        string nombre
        decimal precio
        int stock
        int categoria_figura_id FK
    }
    
    MERCH {
        int id PK
        string nombre
        decimal precio
        int categoria_merch_id FK
    }
    
    MERCH_VARIANTE {
        int id PK
        int merch_id FK
        int talla_id FK
        int color_id FK
        int stock
    }
    
    OFERTA {
        int id PK
        string nombre
        string tipo_descuento
        decimal valor_descuento
        string aplica_a
        date fecha_inicio
        date fecha_fin
        boolean activa
    }
    
    DIRECCION {
        int id PK
        int user_id FK
        string calle
        string ciudad
        string codigo_postal
        boolean es_default
    }
    
    IMAGEN {
        int id PK
        string ruta
        boolean es_principal
        string imageable_type
        int imageable_id
    }
```

---

## Diagrama de Flujo - Proceso de Compra

```mermaid
flowchart TD
    A[Cliente visita tienda] --> B[Navega catálogo]
    B --> C{¿Usa filtros?}
    C -->|Sí| D[Filtra por tipo/categoría/precio/ofertas]
    C -->|No| E[Ve todos los productos]
    D --> E
    
    E --> F[Selecciona producto]
    F --> G[Ve detalle del producto]
    G --> H{¿Añadir al carrito?}
    H -->|No| B
    H -->|Sí| I[Producto añadido al carrito]
    
    I --> J{¿Seguir comprando?}
    J -->|Sí| B
    J -->|No| K[Ir al carrito]
    
    K --> L[Revisa productos y cantidades]
    L --> M{¿Modificar carrito?}
    M -->|Sí| N[Actualiza cantidades o elimina]
    N --> L
    M -->|No| O[Proceder al checkout]
    
    O --> P{¿Usuario logueado?}
    P -->|No| Q[Inicia sesión o registra]
    Q --> P
    P -->|Sí| R[Selecciona dirección de envío]
    
    R --> S[Ve resumen con IVA desglosado]
    S --> T[Clic en Pagar con Stripe]
    T --> U[Redirige a Stripe Checkout]
    
    U --> V{¿Pago exitoso?}
    V -->|No| W[Pago cancelado]
    W --> K
    V -->|Sí| X[Stripe confirma pago]
    
    X --> Y[Se crea pedido en BD]
    Y --> Z[Se descuenta stock]
    Z --> AA[Se vacía carrito]
    AA --> AB[Muestra confirmación]
    AB --> AC[Pedido disponible en Mi Cuenta]
```

---

## Diagrama de Flujo - Panel de Administración

```mermaid
flowchart TD
    A[Admin accede /admin] --> B{¿Está logueado?}
    B -->|No| C[Redirige a login]
    C --> D[Introduce credenciales]
    D --> E{¿Es admin?}
    E -->|No| F[Acceso denegado]
    E -->|Sí| G[Dashboard]
    B -->|Sí| G
    
    G --> H{Selecciona sección}
    
    H -->|Productos| I[Gestión de productos]
    I --> I1[Mangas]
    I --> I2[Figuras]
    I --> I3[Merchandising]
    I1 --> I4[CRUD: Crear/Leer/Editar/Eliminar]
    I2 --> I4
    I3 --> I4
    
    H -->|Categorías| J[Gestión de categorías]
    J --> J1[Por tipo: manga/figura/merch]
    J1 --> I4
    
    H -->|Usuarios| K[Gestión de usuarios]
    K --> K1[Ver lista usuarios]
    K --> K2[Crear usuario]
    K --> K3[Asignar rol admin]
    
    H -->|Pedidos| L[Gestión de pedidos]
    L --> L1[Ver lista pedidos]
    L --> L2[Ver detalle]
    L --> L3[Cambiar estado]
    L3 --> L4{Nuevo estado}
    L4 -->|Completado| L5[Pedido completado]
    L4 -->|Cancelado| L6[Pedido cancelado]
    
    H -->|Ofertas| M[Gestión de ofertas]
    M --> M1[Crear oferta]
    M --> M2[Editar oferta]
    M --> M3[Activar/Desactivar]
    M1 --> M4[Configura descuento y vigencia]
```

---

## Licencia

Proyecto educativo - 2º Trimestre Optativa
