<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success | E-commerce Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen py-10 px-4">
    <div class="max-w-md w-full glass p-10 rounded-3xl text-center">
        <div class="w-24 h-24 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        
        <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-green-400 to-emerald-500 bg-clip-text text-transparent">Payment Success!</h1>
        <p class="text-slate-400 mb-8">Thank you, your order #{{ $order->order_number }} has been placed successfully.</p>

        <div class="glass p-6 rounded-2xl mb-8 space-y-3 text-left">
            <div class="flex justify-between">
                <span class="text-slate-400">Total Paid</span>
                <span class="font-bold">${{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Order Number</span>
                <span class="font-mono">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400">Payment Status</span>
                <span class="px-3 py-1 bg-green-500/10 text-green-500 rounded-full text-xs font-bold uppercase">{{ $order->payment_status }}</span>
            </div>
        </div>

        <div class="space-y-4">
            <a href="http://localhost:3000" class="block w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/20 active:scale-95">Back to Home</a>
            <a href="http://localhost:3000/en/account?order={{ $order->order_number }}" class="block text-slate-400 text-sm hover:text-white transition">View Order Details on Website</a>
        </div>
    </div>
</body>
</html>
