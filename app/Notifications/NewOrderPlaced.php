<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewOrderPlaced extends Notification
{

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return [TelegramChannel::class, 'database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_order',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'total_amount' => $this->order->total_amount,
            'message' => 'New order placed: #' . $this->order->order_number,
        ];
    }

    public function toTelegram($notifiable)
    {
        return "<b>🛍️ New Order Placed</b>\n\n" .
               "<b>Order #:</b> #" . $this->order->order_number . "\n" .
               "<b>Total:</b> $" . number_format($this->order->total_amount, 2) . "\n" .
               "<b>Customer:</b> " . $this->order->user->name . "\n" .
               "<b>Time:</b> " . now('Asia/Phnom_Penh')->format('Y-m-d H:i:s') . " (ICT)";
    }
}
