<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Jobs\SendSmsJob;
use Exception;

class SmsOtpService
{
    protected array $templates = [
        'otp_login' => [
            'template_id' => '1707176906714045127',
            'message' => 'Welcome to Real Gold Organic Mart. Please use OTP {otp} to complete your login. This OTP is valid for {minutes} minutes.'
        ],
        'order_confirmation' => [
            'template_id' => '1707180000000000',
            'message' => 'Hi {name}, your order {order_id} has been confirmed. Total: {amount}.'
        ],
        'abandoned_cart' => [
            'template_id' => '1707181111111111',
            'message' => 'Hi {name}, you left items in your cart. Complete your purchase before it expires!'
        ],
    ];

    protected int $otpExpiry = 20; // minutes

    /**
     * Generate OTP, store in cache, and dispatch SMS synchronously
     */
    public function sendOtp(string $phone, string $flow = 'login'): array
    {
        $user = Customer::where('phone', $phone)->first();

        if ($flow === 'signup' && $user) {
            throw new Exception('Phone number already registered', 409);
        }

        if ($flow === 'login' && !$user) {
            throw new Exception('Phone number not registered', 404);
        }

        $otp = $this->generateOtp();

        // Store OTP in cache
        Cache::put($this->otpKey($phone), $otp, now()->addMinutes($this->otpExpiry));

        // Dispatch SMS synchronously (no queue worker needed)
        $this->sendSmsJob('otp_login', $phone, [
            'otp' => $otp,
            'minutes' => $this->otpExpiry,
            'name' => $user ? trim($user->full_name) : 'Guest',
        ]);

        return [
            'flow' => $flow,
            'phone' => $phone,
            'otp' => $otp, // optional: useful for dev/testing
        ];
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(string $phone, string $otp, string $flow = 'login'): array
    {
        $storedOtp = Cache::get($this->otpKey($phone));

        if (!$storedOtp || !hash_equals((string)$storedOtp, (string)$otp)) {
            throw new Exception('Invalid or expired OTP', 400);
        }

        Cache::forget($this->otpKey($phone));

        if ($flow === 'login') {
            $user = Customer::where('phone', $phone)->first();
            if (!$user) throw new Exception('User not found', 404);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'flow' => 'login',
                'user' => $user->makeHidden(['password', 'remember_token']),
                'token' => $token,
            ];
        }

        return [
            'flow' => 'signup',
            'phone' => $phone,
        ];
    }

    /**
     * Dispatch SMS synchronously (runs immediately, no queue)
     */
    public function sendSmsJob(string $templateKey, string $phone, array $data = []): void
    {
        if (!isset($this->templates[$templateKey])) {
            throw new Exception("SMS template not found: {$templateKey}");
        }

        // Dispatch job immediately (synchronous)
        SendSmsJob::dispatchSync($templateKey, $phone, $data);
    }

    /**
     * Send SMS directly (used by the job)
     */
    public function send(string $templateKey, string $phone, array $data = []): bool
    {
        if (!isset($this->templates[$templateKey])) {
            throw new Exception("SMS template not found: {$templateKey}");
        }

        $template = $this->templates[$templateKey];
        $message = $template['message'];

        foreach ($data as $key => $value) {
            $message = str_replace("{" . $key . "}", $value, $message);
        }

        $smsUrl = env('SMPP_URL', 'http://smpp.webtechsolution.co/http-api.php');

        $params = [
            'username'   => env('SMPP_USERNAME', 'RGMart'),
            'password'   => env('SMPP_PASSWORD', 'RGMart'),
            'senderid'   => env('SMPP_SENDER_ID', 'RGFMRT'),
            'route'      => env('SMPP_ROUTE', '4'),
            'number'     => $phone,
            'message'    => $message,
            'templateid' => $template['template_id'],
        ];

        $finalUrl = $smsUrl . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        try {
            $response = Http::get($finalUrl);

            Log::info('SMS sent', [
                'phone' => $phone,
                'template' => $templateKey,
                'response' => $response->body(),
            ]);

            return $response->successful();
        } catch (Exception $e) {
            Log::error('SMS sending failed', [
                'phone' => $phone,
                'template' => $templateKey,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Generate OTP cache key
     */
    protected function otpKey(string $phone): string
    {
        return 'otp_' . $phone;
    }

    /**
     * Generate random 6-digit OTP
     */
    protected function generateOtp(): string
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}