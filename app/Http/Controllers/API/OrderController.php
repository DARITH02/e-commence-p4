<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\ShippingAddress;
use App\Services\PayWayService;
use App\Services\KHQRService;
use App\Services\CODService;
use App\Notifications\NewOrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private $payway;
    private $khqr;
    private $cod;

    public function __construct(PayWayService $payway, KHQRService $khqr, CODService $cod)
    {
        $this->payway = $payway;
        $this->khqr = $khqr;
        $this->cod = $cod;
    }

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'address_line1' => 'required|string',
            'payment_method' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            $user = $request->user();

            // 1. Create shipping address
            $shippingAddress = ShippingAddress::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->zip ?? $request->postal_code,
                'address_line1' => $request->address_line1,
                'country' => $request->country ?? 'Cambodia',
            ]);

            // 2. Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $request->total_amount ?? 0.00,
                'payment_method' => $request->payment_method ?? 'unspecified',
                'payment_status' => 'pending',
                'shipping_address_id' => $shippingAddress->id,
                'notes' => $request->notes ?? ''
            ]);

            if ($request->has('items')) {
                foreach ($request->items as $item) {
                     OrderItem::create([
                          'order_id' => $order->id,
                          'product_id' => $item['product_id'],
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

            // Prepare response with payment redirect
            $redirectUrl = null;
            switch ($request->payment_method) {
                case 'payway':
                    $redirectUrl = $this->payway->isMock() 
                        ? route('payment.payway.mock', $order->order_number)
                        : route('payment.payway.initiate', $order->order_number);
                    break;
                case 'khqr':
                    $redirectUrl = route('payment.khqr.show', $order->order_number);
                    break;
                case 'cod':
                    $this->cod->process($order);
                    $redirectUrl = route('payment.success', ['order' => $order->order_number]);
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_number' => $order->order_number,
                'redirect_url' => $redirectUrl,
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
