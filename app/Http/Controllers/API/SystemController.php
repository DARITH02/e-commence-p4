<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;

class SystemController extends Controller
{
    public function status()
    {
        return response()->json([
            'status' => 'online',
            'env' => config('app.env'),
            'timestamp' => now()->toIso8601String()
        ]);
    }

    public function dashboard()
    {
        return response()->json([
            'stats' => [
                'active_users' => User::count(),
                'orders' => Order::count(),
                'revenue' => Order::where('payment_status', 'paid')->sum('total_amount')
            ]
        ]);
    }
    
    public function cart()
    {
        return response()->json(['message' => 'Cart retrieval online']);
    }
}
