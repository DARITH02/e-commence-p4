<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | E-commerce Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-4">
    <div class="max-w-md w-full glass p-10 rounded-3xl text-center">
        <div class="w-24 h-24 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-8 animate-shake">
            <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        
        <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-red-400 to-rose-500 bg-clip-text text-transparent">Payment Failed</h1>
        <p class="text-slate-400 mb-8">We're sorry, your payment for order #{{ $order->order_number ?? 'Unknown' }} could not be processed.</p>

        <div class="glass p-6 rounded-2xl mb-8 space-y-3 text-left">
            <div class="flex justify-between">
                <span class="text-slate-400">Total Total</span>
                <span class="font-bold">${{ number_format($order->total_amount ?? 0, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Reason</span>
                <span class="text-red-400 font-bold">Transaction Cancelled</span>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('checkout.index') }}" class="block w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/20 active:scale-95">Retry Payment</a>
            <a href="/" class="block text-slate-400 text-sm hover:text-white transition">Back to Home</a>
        </div>
    </div>
</body>
</html>
