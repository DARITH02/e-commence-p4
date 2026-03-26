<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PayWayService;
use App\Services\KHQRService;
use App\Services\CODService;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
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

    public function index()
    {
        // Fetch real products from DB for simulation
        $products = Product::where('is_active', true)
            ->with(['images' => function($q) {
                $q->orderBy('is_primary', 'desc');
            }])
            ->take(2)
            ->get();

        $total_amount = $products->sum('price');

        return view('checkout.index', compact('products', 'total_amount'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'address_line1' => 'required|string',
            'payment_method' => 'required|in:payway,khqr,cod',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'total_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Create shipping address first
            $shippingAddress = ShippingAddress::create([
                'user_id' => $request->user()?->id,
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

            $order = Order::create([
                'user_id' => $request->user()?->id,
                'order_number' => 'ORD-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(uniqid()),
                'status' => 'pending',
                'total_amount' => $request->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address_id' => $shippingAddress->id,
                'notes' => $request->notes ?? '',
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);
            }

            DB::commit();

            // Handle based on payment method
            switch ($request->payment_method) {
                case 'payway':
                    if ($this->payway->isMock()) {
                        return response()->json(['success' => true, 'redirect_url' => route('payment.payway.mock', $order->order_number)]);
                    }
                    return response()->json(['success' => true, 'redirect_url' => route('payment.payway.initiate', $order->order_number)]);
                case 'khqr':
                    return response()->json(['success' => true, 'redirect_url' => route('payment.khqr.show', $order->order_number)]);
                case 'cod':
                    $this->cod->process($order);
                    return response()->json(['success' => true, 'redirect_url' => route('payment.success', ['order' => $order->order_number])]);
                default:
                    return response()->json(['success' => false, 'message' => 'Unsupported payment method']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Process Failed: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
