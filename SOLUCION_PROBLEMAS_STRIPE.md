# 🔧 Solución de Problemas con Stripe

## ❌ Error: "Se rompe al rellenar datos de pago"

Si el formulario de pago falla al intentar procesar el pago, sigue esta guía paso a paso:

---

## 📋 Checklist Rápido

Ejecuta estos comandos en orden:

```bash
# 1. Ir al directorio del proyecto
cd tienda

# 2. Limpiar todas las cachés
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 3. Verificar que APP_KEY esté generada
php artisan key:generate

# 4. Verificar las migraciones
php artisan migrate:status

# Si hay migraciones pendientes:
php artisan migrate

# 5. Reiniciar el servidor
php artisan serve
```

---

## 🔍 Diagnóstico Detallado

### 1️⃣ Verificar Claves de Stripe

#### Paso 1: Abrir el archivo `.env`
Abre `tienda/.env` y busca estas líneas:

```env
STRIPE_KEY=tu_clave_publica_de_stripe
STRIPE_SECRET=tu_clave_secreta_de_stripe
```

#### Paso 2: Verificar que las claves sean válidas

✅ **Correcto:**
```env
STRIPE_KEY=tu_clave_publica_de_stripe_aqui
STRIPE_SECRET=tu_clave_secreta_de_stripe_aqui
```

❌ **Incorrecto:**
```env
# Sin configurar
STRIPE_KEY=
STRIPE_SECRET=

# Con valores de ejemplo (vacíos)
STRIPE_KEY=
STRIPE_SECRET=

# Con espacios (mal)
STRIPE_KEY= clave_aqui...
STRIPE_SECRET=clave_aqui...     
```

#### Paso 3: Obtener claves reales

1. Ve a https://dashboard.stripe.com/test/apikeys
2. Asegúrate de estar en modo **"Test"** (no "Live")
3. Copia la **Publishable key** (empieza con `pk_test_`)
4. Copia la **Secret key** (empieza con `sk_test_`) - Haz clic en "Reveal"

---

### 2️⃣ Verificar APP_KEY

La `APP_KEY` es **ESENCIAL** para que funcionen las sesiones y el carrito.

```bash
# Verificar si existe en .env
type .env | findstr APP_KEY
```

Debería mostrar algo como:
```
APP_KEY=base64:FPfVaLt/UGNJ7Ae9JqPKnhMmF0nJEkYPS/8Iz5gynOs=
```

Si está vacío o no existe:
```bash
php artisan key:generate
```

---

### 3️⃣ Verificar Base de Datos

Las sesiones se guardan en la base de datos. Si no hay migraciones, no funcionará.

```bash
# Ver estado de migraciones
php artisan migrate:status

# Si hay migraciones pendientes
php artisan migrate
```

**Importante:** Asegúrate de que la base de datos exista:
```sql
-- En MySQL
CREATE DATABASE IF NOT EXISTS mangup_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Y que las credenciales en `.env` sean correctas:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mangup_db
DB_USERNAME=root
DB_PASSWORD=root    # ← Cambia esto si tu MySQL tiene otra contraseña
```

---

### 4️⃣ Limpiar Cachés

Laravel puede estar usando configuración antigua:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

**Después de cambiar el .env, SIEMPRE ejecuta estos comandos.**

---

### 5️⃣ Ver Errores en Consola del Navegador

1. Abre la página del checkout
2. Presiona `F12` para abrir las DevTools
3. Ve a la pestaña **Console**
4. Intenta hacer un pago
5. Si hay errores, cópialos y compártelos

Errores comunes:

```
ERROR: STRIPE_KEY no está configurada
```
➡️ Solución: Configura `STRIPE_KEY` en el `.env`

```
Failed to load resource: the server responded with a status of 500
```
➡️ Solución: Revisa los logs de Laravel (ver paso 6)

---

### 6️⃣ Revisar Logs de Laravel

Los logs te dirán exactamente qué está fallando:

```bash
# Ver las últimas líneas del log
type storage\logs\laravel.log | more
```

O abre el archivo manualmente: `tienda/storage/logs/laravel.log`

Busca líneas que contengan:
- `ERROR`
- `Stripe`
- `session`
- `SQLSTATE`

---

### 7️⃣ Verificar Permisos (solo Linux/Mac)

Si estás en Linux o Mac, verifica permisos:

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🧪 Probar Configuración de Stripe

Crea un archivo de prueba `tienda/test-stripe.php`:

```php
<?php
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "STRIPE_KEY: " . (empty($_ENV['STRIPE_KEY']) ? '❌ NO configurada' : '✅ ' . substr($_ENV['STRIPE_KEY'], 0, 20) . '...') . "\n";
echo "STRIPE_SECRET: " . (empty($_ENV['STRIPE_SECRET']) ? '❌ NO configurada' : '✅ ' . substr($_ENV['STRIPE_SECRET'], 0, 20) . '...') . "\n";

try {
    \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET']);
    $account = \Stripe\Account::retrieve();
    echo "\n✅ Conexión con Stripe exitosa!\n";
    echo "ID de cuenta: " . $account->id . "\n";
} catch (\Exception $e) {
    echo "\n❌ Error al conectar con Stripe:\n";
    echo $e->getMessage() . "\n";
}
```

Ejecutar:
```bash
php test-stripe.php
```

---

## 📞 Si Nada Funciona

1. **Comparte los errores:** Copia el contenido de `storage/logs/laravel.log`
2. **Comparte el .env (sin las claves completas):**
   ```env
   APP_KEY=base64:... (presente)
   STRIPE_KEY=pk_test_... (presente)
   STRIPE_SECRET=sk_test_... (presente)
   DB_CONNECTION=mysql
   DB_DATABASE=mangup_db
   SESSION_DRIVER=database
   ```
3. **Indica qué comandos has ejecutado**
4. **Copia el error de la consola del navegador (F12)**

---

## ✅ Configuración Completa desde Cero

Si quieres empezar de cero:

```bash
# 1. Clonar (si aún no lo has hecho)
git clone https://github.com/lkmark956/MangUP.git
cd MangUP/tienda

# 2. Instalar dependencias
composer install
npm install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Editar .env con tus datos
# - Base de datos
# - Claves de Stripe

# 5. Crear base de datos
# En MySQL: CREATE DATABASE mangup_db;

# 6. Ejecutar migraciones
php artisan migrate

# 7. Cargar datos de prueba
php artisan db:seed

# 8. Limpiar cachés
php artisan config:clear
php artisan cache:clear

# 9. Compilar assets (opcional)
npm run dev

# 10. Iniciar servidor
php artisan serve
```

---

## 🎯 Resumen de Comandos

```bash
# Limpieza completa
php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan route:clear

# Verificar configuración
php artisan config:show stripe
php artisan migrate:status

# Ver logs
type storage\logs\laravel.log | more

# Reiniciar servidor
# Ctrl+C para detener
php artisan serve
```
