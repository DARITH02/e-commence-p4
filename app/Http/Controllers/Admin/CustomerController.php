<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::withCount('orders')
                         ->withSum('orders', 'total_amount')
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        // Stats
        $withOrders = User::has('orders')->count();
        $newThisMonth = User::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $totalRevenue = User::withSum('orders', 'total_amount')->get()->sum('orders_sum_total_amount');

        $stats = [
            'with_orders' => $withOrders,
            'new_this_month' => $newThisMonth,
            'total_revenue' => $totalRevenue,
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders' => function($q) {
            $q->orderBy('created_at', 'desc')->take(5);
        }]);

        $customer->loadCount('orders');
        $customer->loadSum('orders', 'total_amount');

        $customer->created_at_formatted = $customer->created_at->format('d M Y');
        $customer->is_new = $customer->created_at >= Carbon::now()->subDays(30);
        $customer->is_active = true; // Placeholder for active status if no `is_active` column exists on users table

        // Format orders for the drawer
        $customer->recent_orders = $customer->orders->map(function ($order) {
            $order->created_at_formatted = $order->created_at->format('d M Y');
            return $order;
        });

        return response()->json($customer);
    }
}
