<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\OfertaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;

<<<<<<< kev
// Registro
Route::post('/register', [RegisterController::class, 'register']);

// Tienda y Carrito
Route::get('/catalog', [StoreController::class, 'catalog']);
Route::post('/cart/add/{id}', [StoreController::class, 'addToCart']);
Route::post('/cart/checkout', [StoreController::class, 'checkout'])->middleware('auth');
=======
Route::get('/', fn () => redirect()->route('login'));

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/empresa/registro', [EmpresaController::class, 'showRegister'])->name('empresa.register');
    Route::post('/empresa/registro', [EmpresaController::class, 'register'])->name('empresa.register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Panel de empresa
Route::middleware(['auth', 'es.empresa'])->prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/dashboard', [EmpresaController::class, 'dashboard'])->name('dashboard');

    Route::middleware('empresa.aprobada')->group(function () {
        Route::resource('ofertas', OfertaController::class)->except(['show']);
        Route::get('/cupones', [CuponController::class, 'index'])->name('cupones.index');
        Route::patch('/cupones/{id}/canjear', [CuponController::class, 'canjear'])->name('cupones.canjear');
    });
});
>>>>>>> main
