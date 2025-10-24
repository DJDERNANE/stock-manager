<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

