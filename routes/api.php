<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\NewOrderPlaced;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

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
    Route::post('/checkout', function(Request $request) {
        $user = $request->user();
        
        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'status' => 'pending',
            'total_amount' => 150.00, // Dummy amount
            'payment_method' => 'stripe',
            'payment_status' => 'paid',
        ]);

        // Add dummy item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => 1, // Assume product 1 exists
            'quantity' => 1,
            'price' => 150.00,
        ]);

        // Notify Super Admins via Bot
        $superAdmins = User::whereHas('roles', function($q) {
            $q->where('slug', 'super_admin');
        })->get();

        Notification::send($superAdmins, new NewOrderPlaced($order));

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order
        ]);
    });
});

// Admin API
Route::middleware(['auth:sanctum', 'can:admin-access'])->prefix('admin')->group(function () {
   Route::get('/dashboard', function() { return response()->json(['message' => 'Admin Dashboard Data']); });
});
