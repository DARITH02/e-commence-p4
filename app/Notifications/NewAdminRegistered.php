<?php

namespace App\Notifications;

use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdminRegistered extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail', TelegramChannel::class];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Admin Registered')
            ->line('A new administrator has registered on the platform.')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->action('View Admin Panel', route('admin.dashboard'))
            ->line('Please review their permissions.');
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'new_admin',
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'message' => 'New admin registered: ' . $this->user->name,
        ];
    }

    public function toTelegram($notifiable)
    {
        return "<b>🚨 New Admin Registered</b>\n\n" .
               "<b>Name:</b> " . $this->user->name . "\n" .
               "<b>Email:</b> " . $this->user->email . "\n" .
               "<b>Time:</b> " . now()->format('Y-m-d H:i:s');
    }
}
