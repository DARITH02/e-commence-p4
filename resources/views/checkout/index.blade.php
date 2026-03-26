<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | E-commerce Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .checkout-card { border-radius: 24px; box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.05); }
        .payment-option input[type="radio"]:checked + label {
            border-color: #3b82f6;
            background-color: #eff6ff;
            box-shadow: 0 0 0 2px #3b82f6;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="w-full py-4 px-6 border-b bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">E</div>
                <span class="font-bold text-xl tracking-tight">E-commerce <span class="text-blue-600">Pro</span></span>
            </div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 font-medium">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L9.03 9.122a2 2 0 001.938 0L17.834 4.9A2 2 0 0016 4H4a2 2 0 00-1.834.9zM18 6.836V14a2 2 0 01-2 2H4a2 2 0 01-2-2V6.836l5.54 3.41a4 4 0 003.92 0L18 6.836z" clip-rule="evenodd"></path></svg>
                <span>Secure Checkout</span>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto py-10 px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <!-- Payment Section -->
            <div class="lg:col-span-7 space-y-8">
                <div>
                   <h1 class="text-3xl font-black text-slate-900 mb-2">Checkout</h1>
                   <p class="text-slate-500">Please choose your preferred payment method to complete the order.</p>
                </div>

                <div class="bg-white p-8 checkout-card border border-slate-100 mb-6">
                    <h2 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center text-xs mr-3">1</span>
                        Customer Information
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">First Name</label>
                            <input type="text" name="first_name" id="first_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="John">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="Doe">
                        </div>
                        <div class="col-span-2 space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" id="email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="john@example.com">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" name="phone" id="phone" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="012 345 678">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">City</label>
                            <input type="text" name="city" id="city" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="Phnom Penh">
                        </div>
                        <div class="col-span-2 space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shipping Address</label>
                            <textarea name="address_line1" id="address_line1" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all" placeholder="No. 123, St. 456, Sangkat..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 checkout-card border border-slate-100">
                    <h2 class="text-lg font-bold mb-6 flex items-center">
                        <span class="w-6 h-6 bg-slate-100 rounded-full flex items-center justify-center text-xs mr-3">2</span>
                        Payment Method
                    </h2>

                    <form id="checkout-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <!-- ABA PayWay -->
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="payway" value="payway" class="hidden" checked>
                                <label for="payway" class="flex items-center p-5 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 border border-slate-100 p-2">
                                        <img src="https://www.ababank.com/favicon.ico" class="w-full">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-slate-800">ABA PayWay</h3>
                                            <div class="flex space-x-1 opacity-60 scale-75 origin-right">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-4">
                                            </div>
                                        </div>
                                        <p class="text-xs text-slate-400">Pay via ABA Mobile App, Visa or Mastercard</p>
                                    </div>
                                </label>
                            </div>

                            <!-- KHQR -->
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="khqr" value="khqr" class="hidden">
                                <label for="khqr" class="flex items-center p-5 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all">
                                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center mr-4 border border-slate-100 p-2">
                                        <img src="https://static.tildacdn.one/tild3133-3762-4664-b634-653566333735/bakong-square.png" class="w-full">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-slate-800">KHQR (Scan to Pay)</h3>
                                        <p class="text-xs text-slate-400">Scan with any Cambodia banking app (ABA, ACLEDA, etc.)</p>
                                    </div>
                                </label>
                            </div>

                            <!-- COD -->
                            <div class="payment-option">
                                <input type="radio" name="payment_method" id="cod" value="cod" class="hidden">
                                <label for="cod" class="flex items-center p-5 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all">
                                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mr-4 border border-slate-100">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-slate-800">Cash on Delivery</h3>
                                        <p class="text-xs text-slate-400">Pay cash once your order is safely delivered</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-10">
                            <button type="submit" id="submit-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/10 transition-all active:scale-[0.98] flex items-center justify-center">
                                <span id="btn-text">Pay Now</span>
                                <svg id="btn-spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                            <div class="mt-6 flex items-center justify-center space-x-6 opacity-40 grayscale blur-[0.2px]">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-4">
                                <img src="https://raw.githubusercontent.com/socheatsok7/cambodia-banks/master/logos/khqr.png" class="h-4">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-5">
                <div class="bg-white p-6 checkout-card border border-slate-100 sticky top-24">
                    <h2 class="text-lg font-bold mb-6">Order Summary</h2>
                    
                    <div class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($products as $product)
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl overflow-hidden border border-slate-100 shrink-0">
                                @php
                                    $image = $product->images->first();
                                    $imageUrl = $image ? $image->image_url : 'https://placehold.co/200x200?text=No+Image';
                                @endphp
                                <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ $product->name }}</p>
                                <p class="text-[11px] text-slate-400">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                            <span class="text-sm font-bold text-slate-800">${{ number_format($product->price, 2) }}</span>
                        </div>
                        @endforeach
                        
                        @if($products->isEmpty())
                        <p class="text-center text-slate-400 py-4 italic">No products in checkout.</p>
                        @endif
                    </div>

                    <div class="space-y-3 pt-6 border-t border-slate-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Subtotal</span>
                            <span class="font-medium text-slate-800">${{ number_format($total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Shipping</span>
                            <span class="text-green-500 font-bold uppercase text-[10px] tracking-wide">Free</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Tax</span>
                            <span class="font-medium text-slate-800">$0.00</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-900 border-dashed">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-bold text-slate-900">Final Total</span>
                            <span class="text-2xl font-black text-blue-600 tracking-tight">${{ number_format($total_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="bg-slate-50 rounded-xl p-4 flex items-center">
                             <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                             <p class="text-[11px] text-slate-500">Your transaction is encrypted with 256-bit security. Privacy guaranteed.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center text-slate-400 text-xs mt-20">
        <p>&copy; 2026 E-commerce Pro System. Fast, Secure, & Professional.</p>
    </footer>

    <script>
        const form = document.getElementById('checkout-form');
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const spinner = document.getElementById('btn-spinner');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            btn.disabled = true;
            btnText.textContent = 'Contacting Provider...';
            spinner.classList.remove('hidden');

            const payload = {
                first_name: document.getElementById('first_name').value,
                last_name: document.getElementById('last_name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                city: document.getElementById('city').value,
                address_line1: document.getElementById('address_line1').value,
                payment_method: document.querySelector('input[name="payment_method"]:checked').value,
                items: [
                    @foreach($products as $product)
                    { product_id: {{ $product->id }}, quantity: 1, price: {{ $product->price }} },
                    @endforeach
                ],
                total_amount: {{ $total_amount }},
                _token: '{{ csrf_token() }}'
            };

            try {
                // We use relative path so it works with local and production
                const response = await fetch('/checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert('Checkout Error: ' + data.message);
                    resetUI();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Connection failure. Check your internet or CORS settings.');
                resetUI();
            }
        });

        function resetUI() {
            btn.disabled = false;
            btnText.textContent = 'Pay Now';
            spinner.classList.add('hidden');
        }
    </script>
</body>
</html>
