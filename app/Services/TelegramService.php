<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage($message, $chatId = null)
    {
        $targetChatId = $chatId ?: $this->chatId;

        if (!$this->token || !$targetChatId) {
            Log::warning('Telegram credentials or chat_id not set.');
            return false;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $targetChatId,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            if (!$response->successful()) {
                Log::error('Telegram API error: ' . $response->body());
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram notification error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendDocument($filePath, $caption = '', $chatId = null)
    {
        $targetChatId = $chatId ?: $this->chatId;

        if (!$this->token || !$targetChatId) {
            Log::warning('Telegram credentials or chat_id not set.');
            return false;
        }

        if (!file_exists($filePath)) {
            Log::error("Backup file not found at: {$filePath}");
            return false;
        }

        try {
            $response = Http::attach(
                'document', 
                file_get_contents($filePath), 
                basename($filePath)
            )->post("https://api.telegram.org/bot{$this->token}/sendDocument", [
                'chat_id' => $targetChatId,
                'caption' => $caption,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram document send error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendOrderNotification($order, $status = 'Paid')
    {
        $order->load(['user', 'items.product', 'shippingAddress']);
        $name = $order->user?->name ?? 'Guest';
        
        $emoji = $status === 'Paid' ? '✅' : '🔴';
        $title = "<b>{$emoji} Order {$status}</b>\n\n";
        
        $customer = "👤 <b>Customer:</b> {$name}\n";
        if ($order->shippingAddress) {
            $customer .= "📞 <b>Phone:</b> <code>{$order->shippingAddress->phone}</code>\n";
            $customer .= "📍 <b>City:</b> {$order->shippingAddress->city}\n\n";
        }
        
        $details = "🔢 <b>Order #:</b> <code>{$order->order_number}</code>\n";
        $details .= "🗓 <b>Date:</b> " . $order->created_at->timezone('Asia/Phnom_Penh')->format('d-M-Y H:i A') . "\n";
        $details .= "💰 <b>Amount:</b> $ " . number_format($order->total_amount, 2) . "\n";
        $details .= "💳 <b>Method:</b> " . strtoupper($order->payment_method) . "\n\n";
        
        $items = "<b>📦 Order Items:</b>\n";
        foreach ($order->items as $item) {
            $productName = $item->product->name ?? 'Product';
            $items .= "- {$productName} (x{$item->quantity})\n";
        }
        
        $message = $title . $customer . $details . $items;
        
        return $this->sendMessage($message);
    }
}
