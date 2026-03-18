<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminManageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Cleaned routes for admin dashboard and guest landing page.
|
*/

// Root route: redirect based on authentication
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('admin.login'); // Show login page for guests
});

// Language switcher
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'km'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register.post');
    Route::get('/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.forgot-password');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Categories
        Route::resource('/categories', CategoryController::class)->except(['create', 'edit']);

        // Products
        Route::resource('/products', ProductController::class)->except(['create', 'edit']);
        Route::delete('/products/images/{image}', [ProductController::class, 'destroyImage'])->name('admin.products.images.destroy');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.status');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('admin.customers.show');

        // Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');

        // Admin Management (Super Admin only)
        Route::get('/admins', [AdminManageController::class, 'index'])->name('admin.admins');
        Route::post('/admins', [AdminManageController::class, 'store'])->name('admin.admins.store');
        Route::put('/admins/{user}', [AdminManageController::class, 'update'])->name('admin.admins.update');
        Route::delete('/admins/{user}', [AdminManageController::class, 'destroy'])->name('admin.admins.destroy');
    });
});

// API / SPA placeholder login route
Route::get('/login', function() { 
    return response()->json(['message' => 'Please login via API'], 401); 
})->name('login');