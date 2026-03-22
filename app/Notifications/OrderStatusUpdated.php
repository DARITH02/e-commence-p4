<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $customMessage;

    public function __construct($order, $oldStatus = null, $customMessage = null)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->customMessage = $customMessage;
    }

    public function via($notifiable): array
    {
        // Only send if the notifiable (user or order) has a chat id
        if (!$notifiable->telegram_chat_id) {
            return ['database'];
        }
        return [TelegramChannel::class, 'database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'order_status_updated',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => "Order #{$this->order->order_number} status updated to " . ucfirst($this->order->status),
        ];
    }

    public function toTelegram($notifiable)
    {
        $status = $this->order->status;
        $statusLabel = match($status) {
            'pending' => 'Pending (រង់ចាំ)',
            'processing' => 'Processing (កំពុងដំណើរការ)',
            'shipped' => 'Shipped (បានផ្ញើចេញ)',
            'completed' => 'Completed (បានបញ្ចប់)',
            'cancelled' => 'Cancelled (បានបោះបង់)',
            default => ucfirst($status),
        };

        $message = "<b>📦 Order Update: #{$this->order->order_number}</b>\n\n";
        $message .= "Your order status has been updated to: <b>{$statusLabel}</b>\n";
        
        if ($this->customMessage) {
            $message .= "\n<b>Message from Store:</b>\n<i>{$this->customMessage}</i>\n";
        }

        $message .= "\nThank you for shopping with us!";

        return $message;
    }
}
