<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class CODService
{
    private $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function process(Order $order)
    {
        Log::info("Processing Cash on Delivery for: {$order->order_number}");

        $payment = Payment::create([
            'order_id' => $order->id,
            'method' => 'cod',
            'provider' => 'cash',
            'amount' => $order->total_amount,
            'status' => 'pending',
            'transaction_id' => 'COD-' . uniqid(),
            'response_json' => ['payment_type' => 'cod', 'timestamp' => now()],
            'payment_method' => 'cod'
        ]);

        $order->update(['payment_status' => 'pending', 'status' => 'processing', 'payment_method' => 'cod']);

        // Send Telegram Notification for new COD Order
        $this->telegram->sendOrderNotification($order, 'Pending (COD)');

        return ['success' => true, 'order_number' => $order->order_number];
    }
}
