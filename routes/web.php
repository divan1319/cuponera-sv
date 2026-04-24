<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;

// Registro
Route::post('/register', [RegisterController::class, 'register']);

// Tienda y Carrito
Route::get('/catalog', [StoreController::class, 'catalog']);
Route::post('/cart/add/{id}', [StoreController::class, 'addToCart']);
Route::post('/cart/checkout', [StoreController::class, 'checkout'])->middleware('auth');