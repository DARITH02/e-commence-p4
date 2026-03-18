<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

Route::get('/', function () {
    return view('admin.auth.login');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'km'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Admin Authentication
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.post');
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::get('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show']);
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index']);
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store']);
        Route::get('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show']);
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update']);
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy']);
        Route::delete('/products/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage']);

        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index']);
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show']);
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus']);

        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index']);
        Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show']);

        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index']);
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update']);

        Route::get('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'index']);
        Route::post('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'store']);
        Route::put('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'update']);
        Route::delete('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'destroy']);
    });
});

// API login fallback
Route::get('/login', function () {
    return response()->json(['message' => 'Please login via API'], 401);
})->name('login');