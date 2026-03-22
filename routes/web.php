<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

Route::get('/', [AdminAuthController::class, 'showLoginForm']);

Route::get('/lang/{locale}', [\App\Http\Controllers\LocalizationController::class, 'switch'])->name('lang.switch');

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

        // Categories
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('/categories/{id}/restore', [\App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('admin.categories.restore');
        Route::delete('/categories/image/{image}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroyImage'])->name('admin.categories.image.destroy');


        // Products
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products');
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('/products/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('admin.products.images.destroy');

        // Brands
        Route::get('/brands', [\App\Http\Controllers\Admin\BrandController::class, 'index'])->name('admin.brands');
        Route::post('/brands', [\App\Http\Controllers\Admin\BrandController::class, 'store'])->name('admin.brands.store');
        Route::get('/brands/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'show'])->name('admin.brands.show');
        Route::put('/brands/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'update'])->name('admin.brands.update');
        Route::delete('/brands/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('admin.brands.destroy');

        // Orders
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::post('/orders/{order}/telegram', [\App\Http\Controllers\Admin\OrderController::class, 'sendTelegramMessage'])->name('admin.orders.telegram');

        // Customers
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers');
        Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customers.show');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');

        // Admin Management
        Route::get('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'index'])->name('admin.admins');
        Route::post('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'store'])->name('admin.admins.store');
        Route::put('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'update'])->name('admin.admins.update');
        Route::delete('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'destroy'])->name('admin.admins.destroy');
    });
});

// Diagnostics & Setup (Temporary Public for Render Free)
Route::get('/force-migrate', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:status');
        $status = \Illuminate\Support\Facades\Artisan::output();
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrate = \Illuminate\Support\Facades\Artisan::output();
        return response()->json(['success' => true, 'status' => $status, 'migrate' => $migrate]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::get('/telegram-setup', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('telegram:setup');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json(['success' => true, 'output' => $output]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

Route::get('/view-logs', function() {
    $path = storage_path('logs/laravel.log');
    if (!file_exists($path)) return response()->json(['message' => 'No logs yet.']);
    
    $content = file_get_contents($path);
    // Get last 2000 characters
    if (strlen($content) > 2000) {
        $content = substr($content, -2000);
    }
    
    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/reset-db', function() {
    try {
        // Warning: This deletes everything!
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json(['success' => true, 'message' => 'Database reset and seeded!', 'output' => $output]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

Route::get('/telegram-backup', function() {
    try {
        \Illuminate\Support\Facades\Artisan::call('telegram:backup');
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json(['success' => true, 'output' => $output]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'output' => $e->getMessage()], 200);
    }
});

Route::get('/telegram-report', function() {
    try {
         \Illuminate\Support\Facades\Artisan::call('telegram:report');
         $output = \Illuminate\Support\Facades\Artisan::output();
         return response()->json(['success' => true, 'output' => $output]);
    } catch (\Exception $e) {
         return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});

// API login fallback
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');