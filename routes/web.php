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
        Route::get('/empresas', [AdminController::class, 'empresasIndex'])->name('admin.empresas.index');
        Route::get('/empresas/{id}/editar', [AdminController::class, 'empresasEdit'])->whereNumber('id')->name('admin.empresas.edit');
        Route::put('/empresas/{id}', [AdminController::class, 'empresasUpdate'])->whereNumber('id')->name('admin.empresas.update');
        Route::delete('/empresas/{id}', [AdminController::class, 'empresasDestroy'])->whereNumber('id')->name('admin.empresas.destroy');
        Route::get('/clientes', [AdminController::class, 'clientesIndex'])->name('admin.clientes.index');
        Route::get('/clientes/{id}', [AdminController::class, 'clientesShow'])->whereNumber('id')->name('admin.clientes.show');
        Route::delete('/clientes/{id}', [AdminController::class, 'clientesDestroy'])->whereNumber('id')->name('admin.clientes.destroy');
        Route::get('/admins', [AdminController::class, 'adminsIndex'])->name('admin.admins.index');
        Route::get('/admins/crear', [AdminController::class, 'adminsCreate'])->name('admin.admins.create');
        Route::post('/admins', [AdminController::class, 'adminsStore'])->name('admin.admins.store');
        Route::get('/admins/{id}/editar', [AdminController::class, 'adminsEdit'])->whereNumber('id')->name('admin.admins.edit');
        Route::put('/admins/{id}', [AdminController::class, 'adminsUpdate'])->whereNumber('id')->name('admin.admins.update');
        Route::delete('/admins/{id}', [AdminController::class, 'adminsDestroy'])->whereNumber('id')->name('admin.admins.destroy');
    });
});

// Panel de cliente
Route::middleware(['auth', 'cliente'])->prefix('cliente')->name('cliente.')->group(function () {
    Route::get('/dashboard', [ClienteController::class, 'dashboard'])->name('dashboard');
    Route::get('/cupones', [ClienteController::class, 'cupones'])->name('cupones.index');
    Route::get('/facturas', [ClienteController::class, 'facturas'])->name('facturas.index');
    Route::get('/facturas/{id_factura}/pdf', [ClienteController::class, 'facturaPdf'])->name('facturas.pdf');
    Route::get('/facturas/{id_factura}', [ClienteController::class, 'facturaShow'])->name('facturas.show');
    Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito', [CarritoController::class, 'store'])->name('carrito.store');
    Route::patch('/carrito/{id}', [CarritoController::class, 'update'])->name('carrito.update');
    Route::delete('/carrito/{id}', [CarritoController::class, 'destroy'])->name('carrito.destroy');
    Route::post('/carrito/checkout', [CarritoController::class, 'checkout'])->name('carrito.checkout');
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
