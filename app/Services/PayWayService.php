<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PayWayService
{
    private $merchant_id;
    private $api_key;
    private $base_url;
    private $is_mock;
    private $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->merchant_id = config('services.payway.merchant_id');
        $this->api_key = config('services.payway.api_key');
        $this->base_url = config('services.payway.base_url', 'https://checkout.ababank.com/api/payment-gateway/v1/payments/purchase');
        $this->is_mock = env('PAYWAY_MOCKED', false);
        $this->telegram = $telegram;
    }

    public function isMock()
    {
        return $this->is_mock;
    }

    public function getPaymentData(Order $order)
    {
        $tran_id = $order->order_number;
        $amount = number_format($order->total_amount, 2, '.', '');
        $req_time = now()->format('YmdHis');
        
        $hash = $this->generateHash($req_time, $tran_id, $amount);

        return [
            'merchant_id' => $this->merchant_id,
            'tran_id' => $tran_id,
            'amount' => $amount,
            'req_time' => $req_time,
            'hash' => $hash,
            'base_url' => $this->base_url,
            'firstname' => $order->user->first_name ?? 'Customer',
            'lastname' => $order->user->last_name ?? '',
            'email' => $order->user->email ?? '',
            'phone' => $order->shippingAddress->phone ?? '',
        ];
    }

    public function generateHash($req_time, $tran_id, $amount)
    {
        $raw = $this->merchant_id . $req_time . $tran_id . $amount;
        return base64_encode(hash_hmac('sha512', $raw, $this->api_key, true));
    }

    public function validateHash($response_hash, $data)
    {
        // For callback validation
        // Usually: hash = $this->api_key + $merchant_id + $tran_id + $amount + $status
        $raw = $this->api_key . $this->merchant_id . $data['tran_id'] . $data['amount'] . $data['status'];
        $calculated_hash = base64_encode(hash_hmac('sha512', $raw, $this->api_key, true));
        return $calculated_hash === $response_hash;
    }

    public function processCallback($data)
    {
        Log::info('PayWay Callback Processing Started: ', $data);

        $order = Order::where('order_number', $data['tran_id'])->first();
        if (!$order) {
            Log::warning('PayWay Callback: Order not found: ' . $data['tran_id']);
            return ['success' => false, 'message' => 'Order not found'];
        }

        try {
            Log::info('PayWay Callback: Creating/Finding Payment record...');
            $payment = Payment::firstOrCreate(
                ['transaction_id' => $data['ap_transaction_id'] ?? $data['tran_id']],
                [
                    'order_id' => $order->id,
                    'method' => 'payway',
                    'provider' => 'aba',
                    'amount' => $data['amount'],
                    'status' => 'pending',
                    'payment_method' => 'payway'
                ]
            );
            Log::info('PayWay Callback: Payment record ID: ' . $payment->id);

            if ($data['status'] == 0) { // 0 usually means success in ABA PayWay
                Log::info('PayWay Callback: Status is success (0). Updating status...');
                $payment->update([
                    'status' => 'completed',
                    'response_json' => $data
                ]);
                // Send Telegram Notification
                $this->telegram->sendOrderNotification($order);
                
                Log::info('PayWay Callback: Order and Payment updated to success.');
                return ['success' => true];
            } else {
                Log::info('PayWay Callback: Status is failed (' . $data['status'] . '). Updating status...');
                $payment->update([
                    'status' => 'failed',
                    'response_json' => $data
                ]);
                $order->update(['payment_status' => 'failed']);
                Log::info('PayWay Callback: Order and Payment updated to failed.');
                return ['success' => false, 'message' => 'Payment failed'];
            }
        } catch (\Exception $e) {
            Log::error('PayWay Callback Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}
