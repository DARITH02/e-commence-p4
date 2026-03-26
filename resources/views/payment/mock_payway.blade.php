<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABA PayWay Simulation | E-commerce Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .aba-header { background-color: #004d8c; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <div class="aba-header w-full py-6 px-10 text-white flex justify-between items-center shadow-lg">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center p-1">
                <img src="https://www.ababank.com/favicon.ico" class="w-full">
            </div>
            <span class="text-xl font-bold tracking-tight">PayWay <span class="font-light">Simulation</span></span>
        </div>
        <div class="text-xs opacity-70">Development Mode</div>
    </div>

    <main class="flex-1 flex items-center justify-center p-6">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Order Amount</span>
                    <span class="text-2xl font-black text-slate-900">${{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Merchant</span>
                        <span class="font-semibold">E-commerce Pro Store</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Order Ref</span>
                        <span class="font-mono text-blue-600">{{ $order->order_number }}</span>
                    </div>
                </div>
            </div>

                <div class="flex flex-col items-center justify-center space-y-4 py-4">
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                        <div class="relative p-3 bg-white border border-slate-100 rounded-2xl shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode(route('payment.payway.simulate', ['order' => $order->order_number, 'status' => 'success'])) }}&color=004d8c" alt="PayWay QR Code" class="w-40 h-40">
                            
                            <!-- Scanning Animation Line -->
                            <div class="absolute inset-x-0 top-0 h-0.5 bg-blue-500 shadow-[0_0_10px_#3b82f6] animate-[scan_3s_ease-in-out_infinite]"></div>
                        </div>
                    </div>
                    
                    <div class="text-center space-y-1">
                        <div class="flex items-center justify-center space-x-2 text-[#004d8c] font-bold">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14h2v2h-2v-2zm0-10h2v8h-2V6z"/></svg>
                            <span class="text-sm">Scan with ABA Mobile</span>
                        </div>
                        <p class="text-[10px] text-slate-400 max-w-[200px] leading-relaxed">In production, this QR would link to the secure ABA encrypted payment data.</p>
                    </div>
                </div>

                <style>
                    @keyframes scan {
                        0%, 100% { top: 0% }
                        50% { top: 100% }
                    }
                </style>

                <div class="space-y-3 pt-2 pb-6 px-5">
                    <button onclick="simulate('success')" class="w-full bg-[#004d8c] hover:bg-[#003d6e] text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-blue-100 active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simulate Payment Success
                    </button>
                    
                    <button onclick="simulate('failure')" class="w-full bg-white border-2 border-slate-200 hover:border-slate-300 text-slate-700 font-bold py-4 rounded-2xl transition-all active:scale-95 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Simulate Payment Failure
                    </button>
                </div>

                <p class="text-[10px] text-center text-slate-400 pb-6">This page is a local mock and does not contact ABA Bank servers.</p>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-slate-400 text-[10px]">
        &copy; 2026 PayWay Integration Tooling v1.0
    </footer>

    <script>
        async function simulate(status) {
            try {
                const response = await fetch("{{ route('payment.payway.simulate', $order->order_number) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status })
                });
                const data = await response.json();
                window.location.href = data.redirect_url;
            } catch (e) {
                alert('Simulation error');
            }
        }
    </script>
</body>
</html>
