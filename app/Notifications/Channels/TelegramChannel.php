<?php

namespace App\Notifications\Channels;

use App\Services\TelegramService;
use Illuminate\Notifications\Notification;

class TelegramChannel
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toTelegram')) {
            return;
        }

        $message = $notification->toTelegram($notifiable);
        $chatId = $notifiable->telegram_chat_id ?? null; // For users or models that have this field
        
        $this->telegram->sendMessage($message, $chatId);
    }
}
