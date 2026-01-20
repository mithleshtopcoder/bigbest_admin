<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\AppSetting;
use App\Services\InvoiceService;
use App\Helpers\RazorpayHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Helper: Get Razorpay credentials from DB
     */
    protected function getRazorpayCredentials()
    {
        $settings = AppSetting::getSettings();

        if (!$settings->razorpay_key || !$settings->razorpay_secret) {
            throw new \Exception('Razorpay credentials are not configured in App Settings.');
        }

        return [
            'key' => $settings->razorpay_key,
            'secret' => $settings->razorpay_secret,
            'webhook_secret' => $settings->razorpay_webhook_secret,
        ];
    }

    /**
     * Show payment details
     */
    public function show(Request $request, $id)
    {
        $customer = $request->user();

        $payment = Payment::where('id', $id)
            ->where('customer_id', $customer->id)
            ->with('order')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * Create Razorpay Order (Online Payment)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:online,upi,card,netbanking',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = $request->user();

        $order = Order::where('id', $request->order_id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order already paid'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Generate payment number
            $paymentNumber = 'PAY' . now()->format('Ymd') . strtoupper(uniqid());

            // Get Razorpay credentials from AppSetting
            $credentials = $this->getRazorpayCredentials();

            $razorpay = new RazorpayHelper($credentials['key'], $credentials['secret']);

            $razorpayOrder = $razorpay->createOrder(
                $order->total_amount,
                $order->order_number
            );

            if (!$razorpayOrder['success']) {
                throw new \Exception($razorpayOrder['message']);
            }

            // Create Payment record
            $payment = Payment::create([
                'payment_number'          => $paymentNumber,
                'order_id'               => $order->id,
                'customer_id'            => $customer->id,
                'payment_method'         => 'online',
                'payment_gateway'        => 'razorpay',
                'gateway_transaction_id' => $razorpayOrder['data']['id'], // razorpay_order_id
                'payment_type'           => 'full',
                'status'                 => 'pending',
                'amount'                 => $order->total_amount,
            ]);

            // Update order status
            $order->update([
                'payment_status' => 'pending',
                'payment_method' => 'online',
                'status'         => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'razorpay_order_id' => $razorpayOrder['data']['id'],
                    'amount'            => $razorpayOrder['data']['amount'],
                    'currency'          => 'INR',
                    'key'               => $credentials['key'],
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Payment Creation Failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment creation failed'
            ], 500);
        }
    }

    /**
     * Frontend Callback (UI ONLY – NO DB UPDATE)
     */
    public function callback(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $credentials = $this->getRazorpayCredentials();
            $razorpayHelper = new RazorpayHelper($credentials['key'], $credentials['secret']);

            $isValid = $razorpayHelper->verifyPaymentSignature(
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $request->razorpay_signature
            );

            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid payment signature'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment received. Awaiting confirmation.'
            ]);

        } catch (\Exception $e) {
            Log::error('Payment callback failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed'
            ], 500);
        }
    }

    /**
     * Razorpay Webhook (SERVER TO SERVER – FINAL AUTHORITY)
     */
    public function webhook(Request $request)
    {
        try {
            $credentials = $this->getRazorpayCredentials();
            $signature = $request->header('X-Razorpay-Signature');
            $webhookSecret = $credentials['webhook_secret'] ?? null;

            if (!$webhookSecret) {
                Log::error('Razorpay webhook secret not set in App Settings');
                return response()->json(['error' => 'Webhook secret missing'], 500);
            }

            $expectedSignature = hash_hmac(
                'sha256',
                $request->getContent(),
                $webhookSecret
            );

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Invalid Razorpay Webhook Signature');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = $request->all();
            $event = $payload['event'] ?? null;

            // ================= PAYMENT CAPTURED =================
            if ($event === 'payment.captured') {
                $entity = $payload['payload']['payment']['entity'];

                DB::transaction(function () use ($entity) {
                    $payment = Payment::where('gateway_transaction_id', $entity['order_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$payment || $payment->status === 'completed') return;

                    $payment->update([
                        'status'          => 'completed',
                        'transaction_id' => $entity['id'],
                        'paid_at'         => now(),
                    ]);

                    $order = $payment->order;

                    $order->update([
                        'payment_status' => 'paid',
                        'status'         => 'confirmed',
                        'confirmed_at'   => now(),
                    ]);

                    // Generate invoice async
                    (new InvoiceService())->dispatchGenerateInvoice($order, true);
                });
            }

            // ================= PAYMENT FAILED =================
            if ($event === 'payment.failed') {
                $entity = $payload['payload']['payment']['entity'];

                Payment::where('gateway_transaction_id', $entity['order_id'])
                    ->update([
                        'status' => 'failed',
                        'failure_reason' => $entity['error_description'] ?? 'Payment failed'
                    ]);
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }
}