<?php

    namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = \App\Models\Order::where('payment_status', 'paid')->sum('total_amount');
        $ordersCount = \App\Models\Order::count();
        $customersCount = \App\Models\User::whereHas('roles', function($q) {
            $q->where('slug', 'customer');
        })->count();
        
        $latestOrders = \App\Models\Order::with('user')->latest()->limit(8)->get();
        
        $trendingCategories = \App\Models\Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(4)
            ->get();

        $topProducts = \App\Models\Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();

        $recentActivity = \App\Models\ActivityLog::with('user')->latest()->limit(6)->get();

        // Stats for KPI (Mocked for demo purposes, usually calculated vs previous period)
        $stats = [
            'revenue_change' => 12.5,
            'orders_change' => 8.2,
            'customers_change' => -2.4,
            'conversion_change' => 1.1,
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ordersCount', 
            'customersCount', 
            'latestOrders',
            'trendingCategories',
            'topProducts',
            'recentActivity',
            'stats'
        ));
    }
}
