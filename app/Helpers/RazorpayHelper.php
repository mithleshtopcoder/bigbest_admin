<?php

namespace App\Helpers;

use Razorpay\Api\Api;
use Exception;
use Log;

class RazorpayHelper
{
    protected $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    /**
     * Create Razorpay Order
     *
     * @param float $amount Amount in INR
     * @param string $receipt Receipt/order number
     * @param array $notes Optional notes
     * @return array
     */
    public function createOrder($amount, $receipt, $notes = [])
    {
        try {
            $order = $this->api->order->create([
                'amount' => $amount * 100,        // in paise
                'currency' => 'INR',
                'receipt' => $receipt,
                'payment_capture' => 1,           // auto capture
                'notes' => is_array($notes) ? $notes : [],
            ]);

            return [
                'success' => true,
                'data' => $order->toArray(),
            ];
        } catch (Exception $e) {
            Log::error('Razorpay Create Order Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify Razorpay Payment Signature
     *
     * @param string $razorpayPaymentId
     * @param string $razorpayOrderId
     * @param string $razorpaySignature
     * @return bool
     */
    public function verifyPaymentSignature($razorpayPaymentId, $razorpayOrderId, $razorpaySignature)
    {
        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_signature' => $razorpaySignature,
            ]);
            return true;
        } catch (Exception $e) {
            Log::error('Razorpay Signature Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch Order Details
     *
     * @param string $orderId
     * @return array
     */
    public function fetchOrder($orderId)
    {
        try {
            $order = $this->api->order->fetch($orderId);
            return [
                'success' => true,
                'data' => $order->toArray(),
            ];
        } catch (Exception $e) {
            Log::error('Razorpay Fetch Order Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch Payment Details
     *
     * @param string $paymentId
     * @return array
     */
    public function fetchPayment($paymentId)
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return [
                'success' => true,
                'data' => $payment->toArray(),
            ];
        } catch (Exception $e) {
            Log::error('Razorpay Fetch Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Capture Payment
     *
     * @param string $paymentId
     * @param float $amount Amount in INR
     * @return array
     */
    public function capturePayment($paymentId, $amount)
    {
        try {
            $payment = $this->api->payment->fetch($paymentId)->capture([
                'amount' => $amount * 100,
                'currency' => 'INR',
            ]);

            return [
                'success' => true,
                'data' => $payment->toArray(),
            ];
        } catch (Exception $e) {
            Log::error('Razorpay Capture Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a Razorpay Payment Link
     *
     * @param array $data
     *  - amount
     *  - customer: ['name'=>'', 'email'=>'', 'contact'=>'']
     *  - description
     *  - callback_url
     * @return array
     */
    public function createPaymentLink($data)
    {
        try {
            if (isset($data['amount'])) {
                $data['amount'] = $data['amount'] * 100; // in paise
            }

            $default = [
                'currency' => 'INR',
                'accept_partial' => false,
                'reference_id' => uniqid('pay_'),
                'reminder_enable' => false,
                'notify' => ['email', 'sms'],
            ];

            $data = array_merge($default, $data);

            $paymentLink = $this->api->paymentLink->create($data);

            return [
                'success' => true,
                'data' => $paymentLink->toArray(),
            ];
        } catch (Exception $e) {
            Log::error('Razorpay Create Payment Link Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}