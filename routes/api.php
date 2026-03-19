<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderPlaced;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// 1. Health & Status (Production monitoring)
Route::get('/status', function () {
    return response()->json([
        'status' => 'online',
        'env' => config('app.env'),
        'timestamp' => now()->toIso8601String()
    ]);
});

// 2. Public Storefront Routes (Throttled for production)
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);
});

// 3. Protected Customer Routes
Route::middleware(['auth:sanctum', 'throttle:100,1'])->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Cart / Wishlist (Production placeholders)
    Route::get('/cart', function() { return response()->json(['message' => 'Cart retrieval online']); });

    // Order Checkout with production error handling & notifications
    Route::post('/checkout', function(Request $request) {
        try {
            $user = $request->user();

            // Production Validation: In a real app, this would be validated from $request
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $request->total_amount ?? 0.00,
                'payment_method' => $request->payment_method ?? 'unspecified',
                'payment_status' => 'pending',
                'notes' => $request->notes ?? ''
            ]);

            // Add Items if provided
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                     OrderItem::create([
                         'order_id' => $order->id,
                         'product_id' => $item['id'],
                         'quantity' => $item['quantity'],
                         'price' => $item['price'],
                     ]);
                }
            }

            // Production Fix: Safe Notification Handling (doesn't break checkout if Telegram/Email fails)
            try {
                $superAdmins = User::whereHas('roles', function($q) {
                    $q->where('slug', 'super_admin');
                })->get();

                if ($superAdmins->isNotEmpty()) {
                    Notification::send($superAdmins, new NewOrderPlaced($order));
                }
            } catch (\Exception $ne) {
                Log::error("API Notification failure: " . $ne->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_number' => $order->order_number
            ], 201);

        } catch (\Exception $e) {
            Log::error("Checkout failure: " . $e->getMessage());
            return response()->json(['error' => 'An internal error occurred during checkout'], 500);
        }
    });
});

// 4. Secured Admin API
Route::middleware(['auth:sanctum', 'can:admin-access', 'throttle:100,1'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', function() {
            return response()->json([
                'stats' => [
                    'active_users' => User::count(),
                    'orders' => Order::count(),
                    'revenue' => Order::where('payment_status', 'paid')->sum('total_amount')
                ]
            ]);
        });
    });
