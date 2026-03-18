<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminLoggedIn extends Notification
{

    protected $user;
    protected $ipAddress;
    protected $userAgent;

    public function __construct($user, $ipAddress, $userAgent)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    public function via($notifiable): array
    {
        return ['database', TelegramChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'admin_login',
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'ip_address' => $this->ipAddress,
            'message' => 'Admin logged in: ' . $this->user->name,
        ];
    }

    public function toTelegram($notifiable)
    {
        return "<b>🔓 Admin Logged In</b>\n\n" .
               "<b>Name:</b> " . $this->user->name . "\n" .
               "<b>Email:</b> " . $this->user->email . "\n" .
               "<b>IP Address:</b> " . $this->ipAddress . "\n" .
               "<b>Time:</b> " . now('Asia/Phnom_Penh')->format('Y-m-d H:i:s') . " (ICT)";
    }
}
