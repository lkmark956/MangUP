<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
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
// Rutas del Carrito
// ==========================================
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::patch('/carrito/{tipo}/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/{tipo}/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

// ==========================================
// Rutas Protegidas (requieren login)
// ==========================================
Route::middleware('auth')->group(function () {
    // Perfil de usuario
    Route::get('/mi-cuenta', function () {
        return view('cuenta.index');
    })->name('cuenta.index');
});
