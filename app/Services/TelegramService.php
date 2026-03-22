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
}
