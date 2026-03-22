<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramWebhook extends Command
{
    protected $signature = 'telegram:webhook {--delete : Delete the current webhook}';
    protected $description = 'Set or delete the Telegram Bot webhook';

    public function handle()
    {
        $token = config('services.telegram.bot_token');
        $baseUrl = config('app.url');

        if ($this->option('delete')) {
            $this->info("Deleting webhook for bot...");
            $response = Http::post("https://api.telegram.org/bot{$token}/deleteWebhook");
            if ($response->successful()) {
                $this->info("✅ Webhook deleted successfully!");
            } else {
                $this->error("❌ Failed to delete webhook: " . $response->body());
            }
            return 0;
        }

        $webhookUrl = rtrim($baseUrl, '/') . '/api/telegram/webhook';
        $this->info("Setting webhook to: {$webhookUrl}");

        $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => $webhookUrl
        ]);

        if ($response->successful()) {
            $this->info("✅ Webhook set successfully!");
        } else {
            $this->error("❌ Failed to set webhook: " . $response->body());
            $this->warn("Make sure your APP_URL is NOT localhost and bot token is correct.");
        }

        return 0;
    }
}
