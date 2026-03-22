<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\TelegramService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramReport extends Command
{
    protected $signature = 'telegram:report';
    protected $description = 'Generate daily sales report (Excel/CSV) and send to Telegram';

    public function handle(TelegramService $telegram)
    {
        $this->info('Generating daily sales report...');

        // For testing we take Today, but usually it's Yesterday
        $date = Carbon::today();
        $orders = Order::with('user')
            ->whereDate('created_at', $date)
            ->get();

        $filename = 'sales-report-' . $date->format('Y-m-d') . '.csv';
        $path = storage_path('app/' . $filename);

        if (!is_dir(storage_path('app'))) {
            mkdir(storage_path('app'), 0755, true);
        }

        $handle = fopen($path, 'w');
        
        // UTF-8 BOM for Excel visibility
        fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // CSV Header
        fputcsv($handle, ['Order Number', 'Customer Name', 'Email', 'Total Amount', 'Status', 'Time']);

        $totalRevenue = 0;
        $totalOrders = $orders->count();
        $statusCounts = $orders->groupBy('status')->map->count();

        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->user->name ?? 'Guest',
                $order->user->email ?? 'N/A',
                '$' . number_format($order->total_amount, 2),
                ucfirst($order->status),
                $order->created_at->format('H:i:s'),
            ]);
            if ($order->status !== 'cancelled') {
                $totalRevenue += $order->total_amount;
            }
        }

        fclose($handle);

        $caption = "✨ PREMIUM SALES REPORT ✨\n";
        $caption .= "📅 Date: " . $date->format('l, d M Y') . "\n";
        $caption .= "───────────────────\n\n";
        
        $caption .= "📊 Performance Summary:\n";
        $caption .= "📦 Transactions: " . $totalOrders . "\n";
        $caption .= "💰 Daily Revenue: $" . number_format($totalRevenue, 2) . "\n\n";

        if ($statusCounts->isNotEmpty()) {
            $caption .= "📝 Status Breakdown:\n";
            foreach ($statusCounts as $status => $count) {
                $emoji = match($status) {
                    'completed' => '✅',
                    'pending' => '⏳',
                    'processing' => '⚙️',
                    'cancelled' => '❌',
                    'shipped' => '🚚',
                    default => '🔹'
                };
                $caption .= "{$emoji} " . ucfirst($status) . ": " . $count . "\n";
            }
        }

        $caption .= "\n━━━━━━━━━━━━━━━━━━━\n";
        $caption .= "🚀 Automated Store Reporting";

        $ok = $telegram->sendDocument($path, $caption);

        if ($ok) {
            $this->info('Report sent to Telegram successfully.');
            unlink($path); 
        } else {
            $this->error('Failed to send report to Telegram.');
            Log::error('Telegram Report failed to send.');
        }

        return 0;
    }
}
