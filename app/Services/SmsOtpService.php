<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Customer;
use Exception;

class SmsOtpService
{
    protected int $otpExpiry = 20; // minutes
    public function __construct(protected SmsService $smsService)
    {
    }

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

        // Dispatch SMS synchronously (OTP should be fast)
        $this->smsService->dispatchSync('otp_login', $phone, [
            'otp' => $otp,
            'minutes' => $this->otpExpiry,
            'name' => $user ? trim($user->full_name) : 'Guest',
        ], 'sms');

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