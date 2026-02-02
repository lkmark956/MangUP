<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MangaController as AdminMangaController;
use App\Http\Controllers\Admin\FiguraController as AdminFiguraController;
use App\Http\Controllers\Admin\MerchController as AdminMerchController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
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

// ==========================================
// Rutas de Administración
// ==========================================
// 
// Estas rutas están protegidas por DOS middlewares:
// 1. 'auth' - Verifica que el usuario esté logueado
// 2. 'admin' - Verifica que el usuario sea administrador (is_admin = true)
//
// prefix('admin') - Todas las URLs empiezan con /admin
// name('admin.') - Todos los nombres de ruta empiezan con admin.
//
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard principal: /admin
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // ==========================================
    // CRUD de Mangas
    // ==========================================
    // GET    /admin/mangas           → index   (lista todos)
    // GET    /admin/mangas/crear     → create  (formulario crear)
    // POST   /admin/mangas           → store   (guarda nuevo)
    // GET    /admin/mangas/{id}/editar → edit  (formulario editar)
    // PUT    /admin/mangas/{id}      → update  (actualiza)
    // DELETE /admin/mangas/{id}      → destroy (elimina)
    Route::resource('mangas', AdminMangaController::class)->parameters(['mangas' => 'manga']);
    
    // ==========================================
    // CRUD de Figuras
    // ==========================================
    Route::resource('figuras', AdminFiguraController::class)->parameters(['figuras' => 'figura']);
    
    // ==========================================
    // CRUD de Merchandising
    // ==========================================
    Route::resource('merch', AdminMerchController::class);
    
    // ==========================================
    // Gestión de Categorías (por tipo: manga, figura, merch)
    // ==========================================
    Route::get('categorias/{tipo}', [AdminCategoriaController::class, 'index'])->name('categorias.index');
    Route::get('categorias/{tipo}/crear', [AdminCategoriaController::class, 'create'])->name('categorias.create');
    Route::post('categorias/{tipo}', [AdminCategoriaController::class, 'store'])->name('categorias.store');
    Route::get('categorias/{tipo}/{id}/editar', [AdminCategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('categorias/{tipo}/{id}', [AdminCategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('categorias/{tipo}/{id}', [AdminCategoriaController::class, 'destroy'])->name('categorias.destroy');
    
    // ==========================================
    // Gestión de Usuarios
    // ==========================================
    Route::get('usuarios', [AdminUserController::class, 'index'])->name('usuarios.index');
    Route::get('usuarios/crear', [AdminUserController::class, 'create'])->name('usuarios.create');
    Route::post('usuarios', [AdminUserController::class, 'store'])->name('usuarios.store');
    Route::get('usuarios/{usuario}/editar', [AdminUserController::class, 'edit'])->name('usuarios.edit');
    Route::put('usuarios/{usuario}', [AdminUserController::class, 'update'])->name('usuarios.update');
    Route::patch('usuarios/{usuario}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('usuarios.toggle-admin');
    Route::delete('usuarios/{usuario}', [AdminUserController::class, 'destroy'])->name('usuarios.destroy');
});
