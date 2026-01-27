<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;

// Página principal
Route::get('/', [ProductoController::class, 'index'])->name('inicio');

// Rutas de productos
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

// Rutas de categorías
Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
Route::get('/categorias/{id}/productos', [ProductoController::class, 'porCategoria'])->name('productos.categoria');
