<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/ofertas/{id}', [PublicOfertaController::class, 'show'])->name('ofertas.show');

// Registro
// Route::post('/register', [RegisterController::class, 'register']);

// Rutas para el Administrador
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/solicitudes', [AdminController::class, 'listarSolicitudes'])->name('admin.solicitudes');
        Route::get('/revisar/{id}', [AdminController::class, 'revisar'])->name('admin.revisar');
        Route::post('/aprobar/{id}', [AdminController::class, 'procesar'])->name('admin.aprobar');
        Route::get('/reportes', [AdminController::class, 'verReportes'])->name('admin.reportes');
    });
});

// Panel de cliente
Route::middleware(['auth', 'cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
});

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/cliente/registro', [ClienteController::class, 'showRegister'])->name('cliente.register');
    Route::post('/cliente/registro', [ClienteController::class, 'register'])->name('cliente.register.store');
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
