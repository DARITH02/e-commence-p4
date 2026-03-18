<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminLoggedOut extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database', TelegramChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'admin_logout',
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'message' => 'Admin logged out: ' . $this->user->name,
        ];
    }

    public function toTelegram($notifiable)
    {
        return "<b>🔒 Admin Logged Out</b>\n\n" .
               "<b>Name:</b> " . $this->user->name . "\n" .
               "<b>Email:</b> " . $this->user->email . "\n" .
               "<b>Time:</b> " . now('Asia/Phnom_Penh')->format('Y-m-d H:i:s') . " (ICT)";
    }
}
