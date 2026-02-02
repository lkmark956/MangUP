<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;

// ==========================================
// Rutas de Autenticación
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// Rutas Públicas
// ==========================================
Route::get('/', function () {
    // Obtener productos destacados (mezcla de tipos)
    $mangas = Manga::with('imagenes')->take(2)->get()->map(function ($item) {
        $item->tipo = 'manga';
        return $item;
    });
    
    $figuras = Figura::with('imagenes')->take(2)->get()->map(function ($item) {
        $item->tipo = 'figura';
        return $item;
    });
    
    $productosDestacados = $mangas->concat($figuras);
    
    // Últimos mangas
    $ultimosMangas = Manga::with('imagenes', 'categoria')->latest()->take(4)->get()->map(function ($item) {
        $item->tipo = 'manga';
        return $item;
    });
    
    // Figuras destacadas
    $figurasDestacadas = Figura::with('imagenes', 'categoria')->take(4)->get()->map(function ($item) {
        $item->tipo = 'figura';
        return $item;
    });
    
    return view('welcome', compact('productosDestacados', 'ultimosMangas', 'figurasDestacadas'));
})->name('home');

// Rutas de productos (públicas)
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// ==========================================
// Rutas Protegidas (requieren login)
// ==========================================
Route::middleware('auth')->group(function () {
    // Carrito
    Route::get('/carrito', function () {
        return view('carrito.index');
    })->name('carrito.index');
    
    Route::post('/carrito/agregar', function () {
        // Lógica para agregar al carrito
        return back()->with('success', 'Producto agregado al carrito');
    })->name('carrito.agregar');
    
    // Mi Cuenta
    Route::get('/mi-cuenta', [CuentaController::class, 'datosPersonales'])->name('cuenta.index');
    Route::get('/mi-cuenta/datos-personales', [CuentaController::class, 'datosPersonales'])->name('cuenta.datos-personales');
    Route::put('/mi-cuenta/datos-personales', [CuentaController::class, 'actualizarDatos'])->name('cuenta.actualizar-datos');
    Route::post('/mi-cuenta/cambio-password', [CuentaController::class, 'cambiarPassword'])->name('cuenta.actualizar-password');
    
    // Mis Pedidos
    Route::get('/mi-cuenta/pedidos', [CuentaController::class, 'misPedidos'])->name('cuenta.pedidos');
    
    // Direcciones
    Route::get('/mi-cuenta/direcciones', [CuentaController::class, 'direcciones'])->name('cuenta.direcciones');
    Route::post('/mi-cuenta/direcciones', [CuentaController::class, 'agregarDireccion'])->name('cuenta.agregar-direccion');
    Route::get('/mi-cuenta/direcciones/{id}/editar', [CuentaController::class, 'editarDireccion'])->name('cuenta.editar-direccion');
    Route::put('/mi-cuenta/direcciones/{id}', [CuentaController::class, 'actualizarDireccion'])->name('cuenta.actualizar-direccion');
    Route::delete('/mi-cuenta/direcciones/{id}', [CuentaController::class, 'eliminarDireccion'])->name('cuenta.eliminar-direccion');
});
