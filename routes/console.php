<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('telegram:setup', function () {
    $token = config('services.telegram.bot_token');
    $url = config('app.url') . '/api/telegram/webhook';
    $this->info("Setting webhook to: " . $url);
    
    $res = Http::post("https://api.telegram.org/bot{$token}/setWebhook", ['url' => $url]);
    $this->info($res->body());
})->purpose('Set the telegram webhook');

Artisan::command('telegram:poll', function () {
    $token = config('services.telegram.bot_token');
    $this->info("Starting Telegram Polling... (Ctrl+C to stop)");
    
    // Clear any existing webhook first
    Http::post("https://api.telegram.org/bot{$token}/setWebhook", ['url' => '']);
    
    $offset = 0;
    while (true) {
        $res = Http::get("https://api.telegram.org/bot{$token}/getUpdates", [
            'offset' => $offset,
            'timeout' => 30
        ]);

        if ($res->successful()) {
            $updates = $res->json()['result'] ?? [];
            foreach ($updates as $update) {
                $offset = $update['update_id'] + 1;
                
                // Process the update using the WebhookController logic (Mock the request)
                $request = new \Illuminate\Http\Request();
                $request->replace($update);
                
                $controller = app()->make(\App\Http\Controllers\API\TelegramWebhookController::class);
                $controller->handle($request);
                
                $this->comment("Processed update #{$update['update_id']}");
            }
        } else {
            $this->error("Error fetching updates: " . $res->body());
            sleep(5);
        }
    }
})->purpose('Poll for telegram updates (Useful for local development)');
