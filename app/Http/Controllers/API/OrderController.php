<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Notifications\NewOrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->orders()->with('items.product')->latest()->paginate(10)
        );
    }

    public function show(Request $request, $order_number)
    {
        $order = $request->user()->orders()
            ->where('order_number', $order_number)
            ->with(['items.product.images', 'shippingAddress', 'user'])
            ->firstOrFail();
            
        return response()->json($order);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();
            $user = $request->user();

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $request->total_amount ?? 0.00,
                'payment_method' => $request->payment_method ?? 'unspecified',
                'payment_status' => 'pending',
                'notes' => $request->notes ?? ''
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                     OrderItem::create([
                          'order_id' => $order->id,
                          'product_id' => $item['id'],
                          'quantity' => $item['quantity'],
                          'price' => $item['price'],
                          'total_price' => $item['quantity'] * $item['price'],
                     ]);
                }
            }
            DB::commit();

            // Notify stakeholders safely
            $telegramNotified = false;
            try {
                // 1. Notify Super Admins
                $superAdmins = User::whereHas('roles', function($q) {
                    $q->where('slug', 'super_admin');
                })->get();

                if ($superAdmins->isNotEmpty()) {
                    Notification::send($superAdmins, new NewOrderPlaced($order));
                }

                // 2. Notify the customer if they have Telegram linked
                if ($user && $user->telegram_chat_id) {
                    $user->notify(new NewOrderPlaced($order));
                    $telegramNotified = true;
                }
            } catch (\Exception $ne) {
                Log::error("API Notification failure: " . $ne->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_number' => $order->order_number,
                'telegram_linked' => (bool)$user?->telegram_chat_id,
                'telegram_notified' => $telegramNotified
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Checkout failure: " . $e->getMessage());
            return response()->json(['error' => 'An internal error occurred during checkout'], 500);
        }
    }
}
