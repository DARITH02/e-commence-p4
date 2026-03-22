<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\SystemController;
use App\Http\Controllers\API\TelegramWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Health & Status
Route::get('/status', [SystemController::class, 'status']);
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
Route::get('/force-clear-cache', function () {
    \Illuminate\Support\Facades\Cache::increment('product_cache_version');
    \Illuminate\Support\Facades\Cache::forget('products_all');
    return response()->json(['success' => true, 'message' => 'Product cache busted! Now version: ' . \Illuminate\Support\Facades\Cache::get('product_cache_version', 1)]);
});

// 2. Public Storefront Routes
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/latest', [ProductController::class, 'latest']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryController::class, 'show']);

    // Brands
    Route::get('/brands', [BrandController::class, 'index']);
    Route::get('/brands/{slug}', [BrandController::class, 'show']);
});

// 3. Protected Customer Routes
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    // User Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Order History & Checkout
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order_number}', [OrderController::class, 'show']);
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // Cart Placeholder
    Route::get('/cart', [SystemController::class, 'cart']);
});

// 4. Secured Admin API
Route::middleware(['auth:sanctum', 'can:admin-access', 'throttle:100,1'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [SystemController::class, 'dashboard']);

        // Brand Management
        Route::post('/brands', [BrandController::class, 'store']);
        Route::put('/brands/{id}', [BrandController::class, 'update']);
        Route::delete('/brands/{id}', [BrandController::class, 'destroy']);
    });
