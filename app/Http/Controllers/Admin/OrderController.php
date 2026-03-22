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
        $formattedDate = $order->created_at->translatedFormat('d M Y, H:i');
        if (app()->getLocale() === 'km') {
            $khmerDigits = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
            $formattedDate = str_replace(range(0, 9), $khmerDigits, $formattedDate);
        }
        $order->created_at_formatted = $formattedDate;
        
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
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'message' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Send Notification
        $notifiable = $order->user ?? $order;
        if ($notifiable->telegram_chat_id) {
            $notifiable->notify(new \App\Notifications\OrderStatusUpdated($order, $oldStatus, $request->message));
        }

        return response()->json(['message' => __('admin.status_updated') ?? 'Status completely updated']);
    }

    public function sendTelegramMessage(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $notifiable = $order->user ?? $order;

        if (!$notifiable->telegram_chat_id) {
            return response()->json(['message' => 'Customer has not linked Telegram.'], 422);
        }

        $service = new \App\Services\TelegramService();
        $ok = $service->sendMessage($request->message, $notifiable->telegram_chat_id);

        if ($ok) {
            return response()->json(['message' => 'Message sent successfully!']);
        }

        return response()->json(['message' => 'Failed to send message.'], 500);
    }
}
