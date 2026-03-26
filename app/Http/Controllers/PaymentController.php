<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PayWayService;
use App\Services\KHQRService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $payway;
    private $khqr;

    public function __construct(PayWayService $payway, KHQRService $khqr)
    {
        $this->payway = $payway;
        $this->khqr = $khqr;
    }

    public function initiatePayWay(Order $order)
    {
        $paymentData = $this->payway->getPaymentData($order);
        return view('payment.payway_form', compact('paymentData'));
    }

    public function mockPayWay(Order $order)
    {
        return view('payment.mock_payway', compact('order'));
    }

    public function simulatePayWay(Request $request, Order $order)
    {
        try {
            $status = $request->status ?? 'success'; // success or failure
            
            $data = [
                'tran_id' => $order->order_number,
                'ap_transaction_id' => 'MOCK-' . strtoupper(uniqid()),
                'amount' => $order->total_amount,
                'status' => ($status === 'success') ? 0 : 1, // 0 success, 1 failure
            ];

            $this->payway->processCallback($data);

            if ($request->ajax() || $request->wantsJson()) {
                if ($status === 'success') {
                    return response()->json(['success' => true, 'redirect_url' => route('payment.success', ['order' => $order->order_number])]);
                }
                return response()->json(['success' => false, 'redirect_url' => route('payment.failure', ['order' => $order->order_number])]);
            }

            // For normal GET requests (like QR scans)
            if ($status === 'success') {
                return redirect()->route('payment.success', ['order' => $order->order_number]);
            }
            return redirect()->route('payment.failure', ['order' => $order->order_number]);
        } catch (\Exception $e) {
            Log::error('PayWay Simulation Error: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function paywayCallback(Request $request)
    {
        Log::info('PayWay Callback Received: ', $request->all());

        // Validate hash
        $isValid = $this->payway->validateHash($request->hash, $request->all());

        if ($isValid) {
            $this->payway->processCallback($request->all());
            return response()->json(['success' => true]);
        }

        Log::error('PayWay Hash mismatch: ', ['response_hash' => $request->hash]);
        return response()->json(['success' => false, 'message' => 'Invalid hash'], 400);
    }

    public function showKHQR(Order $order)
    {
        $qrData = $this->khqr->generateQRString($order);
        return view('payment.khqr_scan', compact('qrData', 'order'));
    }

    public function verifyKHQR(Request $request, Order $order)
    {
        $result = $this->khqr->verifyPayment($order->order_number);
        if ($result['status'] == 'success') {
            return response()->json(['success' => true, 'redirect_url' => route('payment.success', ['order' => $order->order_number])]);
        }
        return response()->json(['success' => false, 'message' => 'Payment still pending']);
    }

    public function success(Request $request)
    {
        $order = Order::where('order_number', $request->order)->firstOrFail();
        return view('payment.success', compact('order'));
    }

    public function failure(Request $request)
    {
        $order = Order::where('order_number', $request->order)->first();
        return view('payment.failure', compact('order'));
    }
}
