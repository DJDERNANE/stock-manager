<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'signupForm'])->name('signup-form');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
    
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login-form');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{product}/restock', [ProductController::class, 'restock'])->name('products.restock');
    Route::get('/products/{product}/history', [ProductController::class, 'history'])->name('products.history');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

