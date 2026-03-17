<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(15);
        
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        
        $stats = [
            'total' => $totalOrders,
            'completed' => $completedOrders,
            'pending' => $pendingOrders,
            'revenue' => $totalRevenue,
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product.images']);
        $order->created_at_formatted = $order->created_at->format('d M Y, H:i');
        
        $order->order_items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->product ? $item->product->name : 'Unknown Product',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'product' => [
                    'name' => $item->product ? $item->product->name : null,
                    'image_url' => $item->product && $item->product->images->first() ? $item->product->images->first()->image_url : null,
                ]
            ];
        });
        
        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return response()->json(['message' => __('admin.status_updated') ?? 'Status completely updated']);
    }
}
