<?php

// routes/api.php
use App\Http\Controllers\api\AuthAPIController;
use App\Http\Controllers\api\ProductController;
use Illuminate\Support\Facades\Route;
Route::post('/login', [AuthAPIController::class, 'login']);
Route::post('/register', [AuthAPIController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthAPIController::class, 'logout']);
    Route::get('/products', [ProductController::class, 'index']);
});
