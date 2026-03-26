<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class KHQRService
{
    private $bakong_id;
    private $api_key;
    private $merchant_name;
    private $merchant_city;
    private $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->bakong_id = config('services.khqr.bakong_id', 'example@bakong');
        $this->api_key = config('services.khqr.api_key');
        $this->merchant_name = config('services.khqr.merchant_name', 'E-commerce Store');
        $this->merchant_city = config('services.khqr.merchant_city', 'Phnom Penh');
        $this->telegram = $telegram;
    }

    public function generateQRString(Order $order)
    {
        // For KHQR Bakong, usually you'd call their API to get a deep link & QR string
        // or use their library to generate the EMV QR string.
        // Here, we simulate the logic.
        
        $tran_id = $order->order_number;
        $amount = $order->total_amount;
        
        // This is a placeholder for actual QR string logic
        $qrString = "00020101021229300012{$this->bakong_id}520400005303840540" . strlen($amount) . $amount . "5802KH59" . strlen($this->merchant_name) . $this->merchant_name . "6010" . $this->merchant_city . "62150511{$tran_id}6304ABCD";

        return [
            'qr_string' => $qrString,
            'merchant_name' => $this->merchant_name,
            'amount' => $amount,
            'currency' => 'USD',
            'order_number' => $tran_id,
        ];
    }

    public function verifyPayment($order_number)
    {
        // Simulate API call to Bakong/Provider to verify payment
        // In a real scenario, you'd check their API using a webhook or polling.
        
        Log::info("Verifying KHQR Payment for: $order_number");

        $order = Order::where('order_number', $order_number)->first();
        if (!$order) {
            return ['status' => 'error', 'message' => 'Order not found'];
        }

        // Simulating logic: if it ends in 'S' it's success (just for demonstration)
        // or check some internal flag.
        
        $isPaid = true; // Assume success for demonstration or simulation

        if ($isPaid) {
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id, 'method' => 'khqr'],
                [
                    'provider' => 'bakong',
                    'amount' => $order->total_amount,
                    'status' => 'completed',
                    'transaction_id' => 'KHQR-' . uniqid(),
                    'response_json' => ['verified' => true, 'timestamp' => now()],
                    'payment_method' => 'khqr'
                ]
            );

            $order->update(['payment_status' => 'paid', 'status' => 'processing']);

            // Send Telegram Notification
            $this->telegram->sendOrderNotification($order);

            return ['status' => 'success', 'message' => 'Payment verified'];
        }

        return ['status' => 'pending', 'message' => 'Payment still pending'];
    }
}
