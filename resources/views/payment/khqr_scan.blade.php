<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHQR Secure Payment | E-commerce Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --bakong-red: #e11d48;
            --aba-blue: #004d8c;
        }
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .gradient-bg { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); }
        .qr-card { border-radius: 24px; box-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.15); }
        .shimmer {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .step-circle { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 700; }
        .pulse-border { border: 2px solid #e2e8f0; animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { border-color: #e2e8f0; box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { border-color: #3b82f6; box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { border-color: #e2e8f0; box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">
    <!-- Navbar / Header -->
    <header class="w-full py-4 px-6 border-b bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">E</div>
                <span class="font-bold text-xl tracking-tight">E-commerce <span class="text-blue-600">Pro</span></span>
            </div>
            <div class="hidden md:flex items-center space-x-4 text-sm font-medium text-slate-500">
                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L9.03 9.122a2 2 0 001.938 0L17.834 4.9A2 2 0 0016 4H4a2 2 0 00-1.834.9zM18 6.836V14a2 2 0 01-2 2H4a2 2 0 01-2-2V6.836l5.54 3.41a4 4 0 003.92 0L18 6.836z" clip-rule="evenodd"></path></svg> {{ $order->user->email ?? 'Support@store.com' }}</span>
                <span class="h-4 w-px bg-slate-200"></span>
                <span class="flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg> Secure 256-bit SSL</span>
            </div>
        </div>
    </header>

    <main class="flex-1 flex flex-col items-center justify-center p-4 md:p-8">
        <div class="max-w-5xl w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left: Payment Instructions & QR Card -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-[28px] p-6 md:p-10 qr-card relative overflow-hidden border border-slate-100">
                    <!-- Top Ribbon -->
                    <div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-r from-blue-500 via-rose-500 to-indigo-500"></div>
                    
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900 mb-1 tracking-tight">Scan & Pay</h1>
                            <p class="text-xs text-slate-500 uppercase font-bold tracking-widest opacity-60">Cambodia KHQR Standard</p>
                        </div>
                        <img src="https://bakong.nbc.org.kh/assets/img/bakong-logo-title.png" alt="KHQR" class="h-8 opacity-90">
                    </div>

                    <div class="flex flex-col md:flex-row items-center md:items-start space-y-8 md:space-y-0 md:space-x-8">
                        <!-- QR Code Container -->
                        <div class="flex flex-col items-center shrink-0">
                            <div class="p-4 bg-white rounded-2xl pulse-border relative shadow-sm">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrData['qr_string']) }}" alt="KHQR Code" class="w-44 h-44">
                                <!-- App Shortcuts for Mobile -->
                                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 flex space-x-1.5">
                                    <div class="w-7 h-7 rounded-full bg-white shadow-md p-1 flex items-center justify-center border border-slate-100"><img src="https://www.ababank.com/favicon.ico" class="w-full"></div>
                                    <div class="w-7 h-7 rounded-full bg-white shadow-md p-1 text-[10px] flex items-center justify-center font-bold text-red-600 border border-slate-100">KH</div>
                                </div>
                            </div>
                            <p class="mt-6 font-mono text-[10px] text-slate-400 bg-slate-50 px-2 py-0.5 rounded-full border border-slate-100">{{ $order->order_number }}</p>
                        </div>

                        <!-- Instructions -->
                        <div class="flex-1 space-y-6 w-full">
                            <h3 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Payment Steps</h3>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="step-circle bg-blue-50 text-blue-600 mr-3 shrink-0 !w-6 !h-6 !text-xs">1</div>
                                    <p class="text-[13px] text-slate-600 leading-snug">Open your <strong>Banking App</strong></p>
                                </div>
                                <div class="flex items-start">
                                    <div class="step-circle bg-blue-50 text-blue-600 mr-3 shrink-0 !w-6 !h-6 !text-xs">2</div>
                                    <p class="text-[13px] text-slate-600 leading-snug">Select <strong>"Scan"</strong> and point camera to QR</p>
                                </div>
                                <div class="flex items-start">
                                    <div class="step-circle bg-blue-50 text-blue-600 mr-3 shrink-0 !w-6 !h-6 !text-xs">3</div>
                                    <p class="text-[13px] text-slate-600 leading-snug">Confirm amount & enter <strong>PIN</strong></p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-50">
                                <button id="verify-btn" class="w-full group bg-slate-900 hover:bg-black text-white text-sm font-bold py-3.5 rounded-xl transition-all shadow-lg active:scale-[0.98] flex items-center justify-center">
                                    <span id="btn-text">I have paid manually</span>
                                    <svg id="btn-spinner" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                                <p class="text-center text-[10px] text-slate-400 mt-3 hover:text-slate-600 cursor-pointer transition">Need help with your transaction?</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary & Countdown -->
            <div class="lg:col-span-5 flex flex-col space-y-4">
                <!-- Countdown -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 flex flex-col items-center">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Session Expires In</span>
                    <div id="countdown" class="text-3xl font-black text-slate-800 tabular-nums">15:00</div>
                    <div class="w-full bg-slate-100 h-1 rounded-full mt-3 overflow-hidden">
                        <div id="countdown-progress" class="bg-blue-600 h-full w-full transition-all duration-1000"></div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-[24px] p-6 shadow-sm border border-slate-100 flex-1">
                    <h3 class="font-bold text-base mb-4 border-b pb-3">Payment Summary</h3>
                    
                    <div class="space-y-4">
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100/30">
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-tight mb-1">Total Amount</p>
                            <div class="flex items-baseline justify-between">
                                <p class="text-2xl font-black text-blue-600">${{ number_format($qrData['amount'], 2) }}</p>
                                <p class="text-xs font-bold text-slate-500">USD</p>
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <div class="flex justify-between text-[11px]">
                                <span class="text-slate-500">Beneficiary</span>
                                <span class="font-bold text-slate-800">{{ $qrData['merchant_name'] }}</span>
                            </div>
                            <div class="flex justify-between text-[11px]">
                                <span class="text-slate-500">Transaction Ref</span>
                                <span class="font-mono text-slate-800">{{ $order->order_number }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-4 p-3 bg-amber-50/50 rounded-xl border border-amber-100/30 flex items-start">
                             <svg class="w-3.5 h-3.5 text-amber-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                             <p class="text-[10px] text-amber-800 leading-tight">Do not close this window until you receive confirmation.</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-dashed">
                        <a href="{{ route('checkout.index') }}" class="flex items-center justify-center text-slate-400 font-bold text-[11px] hover:text-rose-500 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Cancel and Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 text-center text-slate-400 text-xs">
        <p class="mb-2">Official KHQR Payment Gateway | E-commerce Pro System</p>
        <div class="flex justify-center space-x-4">
            <span class="hover:text-slate-600 cursor-pointer">Terms of Service</span>
            <span>&bull;</span>
            <span class="hover:text-slate-600 cursor-pointer">Privacy Policy</span>
            <span>&bull;</span>
            <span class="hover:text-slate-600 cursor-pointer">Support</span>
        </div>
    </footer>

    <script>
        // Verification Logic
        document.getElementById('verify-btn').addEventListener('click', async () => {
            const btn = document.getElementById('verify-btn');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            btn.disabled = true;
            btnText.textContent = 'Verifying with Bank...';
            spinner.classList.remove('hidden');

            try {
                const response = await fetch("{{ route('payment.khqr.verify', $order->order_number) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Bank Status: ' + data.message + '. Please ensure you have completed the transaction.');
                    btn.disabled = false;
                    btnText.textContent = 'I have paid manually';
                    spinner.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Connection error with the banking server.');
                btn.disabled = false;
                btnText.textContent = 'I have paid manually';
                spinner.classList.add('hidden');
            }
        });

        // Countdown Timer
        let seconds = 900; // 15 mins
        const countdownEl = document.getElementById('countdown');
        const progressEl = document.getElementById('countdown-progress');
        
        const timer = setInterval(() => {
            seconds--;
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            countdownEl.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            
            const percentage = (seconds / 900) * 100;
            progressEl.style.width = `${percentage}%`;

            if (seconds <= 0) {
                clearInterval(timer);
                alert('Payment session expired. Please create a new order.');
                window.location.href = "{{ route('checkout.index') }}";
            }
        }, 1000);

        // Simulated Auto-Verification (Polling)
        /*
        setInterval(async () => {
            try {
                const response = await fetch("{{ route('payment.khqr.verify', $order->order_number) }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                if (data.success) window.location.href = data.redirect_url;
            } catch (e) {}
        }, 8000);
        */
    </script>
</body>
</html>
