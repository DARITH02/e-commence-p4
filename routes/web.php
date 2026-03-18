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

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Category Management
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.categories.show');
        Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Product Management
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products');
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::delete('/products/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('admin.products.images.destroy');

        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.status');
        Route::get('/customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers');
        Route::get('/customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customers.show');
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('admin.settings.update');

        // Admin Management (Super Admin only check inside controller)
        Route::get('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'index'])->name('admin.admins');
        Route::post('/admins', [\App\Http\Controllers\Admin\AdminManageController::class, 'store'])->name('admin.admins.store');
        Route::put('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'update'])->name('admin.admins.update');
        Route::delete('/admins/{user}', [\App\Http\Controllers\Admin\AdminManageController::class, 'destroy'])->name('admin.admins.destroy');
    });
});

// User Login Placeholder (for Sanctum/SPA)
Route::get('/login', function() { 
    return response()->json(['message' => 'Please login via API'], 401); 
})->name('login');
