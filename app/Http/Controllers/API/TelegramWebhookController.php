<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(Request $request)
    {
        $payload = $request->all();
        $message = $payload['message'] ?? null;

        if (!$message) return response()->json(['status' => 'ok']);

        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';
        $contact = $message['contact'] ?? null;

        // 1. If user sent a contact (Share Contact button)
        if ($contact) {
            $phoneNumber = $this->normalizePhone($contact['phone_number']);
            $linked = false;

            // Link to Users (All active users with this phone number)
            $users = User::where('phone', 'LIKE', "%$phoneNumber%")->get();
            foreach ($users as $user) {
                $user->update(['telegram_chat_id' => $chatId]);
                $linked = true;

                // Sync all orders for this user
                Order::where('user_id', $user->id)->update(['telegram_chat_id' => $chatId]);
            }

            // Link to Guest Orders (via shipping address phone)
            $guestOrders = Order::whereNull('telegram_chat_id')
                ->whereHas('shippingAddress', function($q) use ($phoneNumber) {
                    $q->where('phone', 'LIKE', "%$phoneNumber%");
                })->get();
            
            foreach ($guestOrders as $order) {
                $order->update(['telegram_chat_id' => $chatId]);
                $linked = true;
            }

            if ($linked) {
                $this->telegram->sendMessage("✅ Successfully linked! You will now receive order status updates through this chat.", $chatId);
            } else {
                $this->telegram->sendMessage("❌ We couldn't find an account or order associated with this phone number ({$phoneNumber}) in our system.", $chatId);
            }

            return response()->json(['status' => 'ok']);
        }

        // 2. Initial /start command
        if (strpos($text, '/start') === 0) {
            $this->telegram->sendMessage("👋 <b>Welcome to " . config('app.name') . "!</b>\n\nYou can receive automatic updates for your orders here.\n\n👇 Please click the button below to link your Telegram with your phone number.", $chatId);
            
            // Send keyboard with "Share Contact" button
            $this->requestContact($chatId);
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'ok']);
    }

    protected function requestContact($chatId)
    {
        $token = config('services.telegram.bot_token');
        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => "Please share your contact to securely link your account:",
            'reply_markup' => json_encode([
                'keyboard' => [[
                    ['text' => "📲 Share Contact", 'request_contact' => true]
                ]],
                'one_time_keyboard' => true,
                'resize_keyboard' => true
            ])
        ]);
    }

    protected function normalizePhone($phone)
    {
        // Remove +, spaces, dashes
        $phone = preg_replace('/[^0-9]/', '', $phone);
        // If it starts with 855 or country codes, maybe keep only the last 8-9 digits
        if (strlen($phone) > 9) {
            return substr($phone, -9);
        }
        return $phone;
    }
}
