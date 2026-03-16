<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Cart Routes (Placeholders)
    Route::get('/cart', function() { return response()->json(['message' => 'Cart contents']); });
    Route::post('/cart/add', function() { return response()->json(['message' => 'Product added to cart']); });

    // Order Routes
    Route::post('/checkout', function() { return response()->json(['message' => 'Order created']); });
});

// Admin API
Route::middleware(['auth:sanctum', 'can:admin-access'])->prefix('admin')->group(function () {
   Route::get('/dashboard', function() { return response()->json(['message' => 'Admin Dashboard Data']); });
});
