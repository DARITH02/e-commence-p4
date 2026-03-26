<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to ABA PayWay...</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #0f172a; color: #f8fafc; }
        .spinner { border-top-color: #3b82f6; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="w-20 h-20 border-4 border-slate-700 border-t-blue-500 rounded-full animate-spin mx-auto mb-6"></div>
        <h2 class="text-2xl font-bold mb-2">Secure Connection...</h2>
        <p class="text-slate-400">Redirecting to ABA PayWay securely. Please do not refresh the page.</p>

        <!-- ABA PayWay Redirect Form -->
        <form action="{{ $paymentData['base_url'] }}" method="POST" id="payway-form" class="hidden">
            <input type="hidden" name="merchant_id" value="{{ $paymentData['merchant_id'] }}">
            <input type="hidden" name="tran_id" value="{{ $paymentData['tran_id'] }}">
            <input type="hidden" name="amount" value="{{ $paymentData['amount'] }}">
            <input type="hidden" name="req_time" value="{{ $paymentData['req_time'] }}">
            <input type="hidden" name="hash" value="{{ $paymentData['hash'] }}">
            <input type="hidden" name="firstname" value="{{ $paymentData['firstname'] }}">
            <input type="hidden" name="lastname" value="{{ $paymentData['lastname'] }}">
            <input type="hidden" name="email" value="{{ $paymentData['email'] }}">
            <input type="hidden" name="phone" value="{{ $paymentData['phone'] }}">
            <!-- Optional: push_notification, return_url, cancel_url etc -->
            <input type="hidden" name="return_url" value="{{ base64_encode(route('payment.success', ['order' => $paymentData['tran_id']])) }}">
            <input type="hidden" name="cancel_url" value="{{ base64_encode(route('payment.failure', ['order' => $paymentData['tran_id']])) }}">
        </form>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                document.getElementById('payway-form').submit();
            }, 1500); // 1.5s delay for effect
        };
    </script>
</body>
</html>
