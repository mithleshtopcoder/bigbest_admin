<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;
        $request = Request::instance();
        
        // Get device and platform info
        $userAgent = $request->userAgent();
        $device = $this->getDevice($userAgent);
        $platform = $this->getPlatform($userAgent);

        LoginHistory::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device' => $device,
            'platform' => $platform,
            'status' => 'success',
            'login_at' => now(),
        ]);
    }

    /**
     * Extract device information from user agent
     */
    private function getDevice(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        if (preg_match('/Mobile|Android|iPhone|iPad/i', $userAgent)) {
            if (preg_match('/iPhone/i', $userAgent)) {
                return 'iPhone';
            } elseif (preg_match('/iPad/i', $userAgent)) {
                return 'iPad';
            } elseif (preg_match('/Android/i', $userAgent)) {
                return 'Android';
            }
            return 'Mobile Device';
        }

        if (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            return 'Edge';
        } elseif (preg_match('/Opera/i', $userAgent)) {
            return 'Opera';
        }

        return 'Unknown Browser';
    }

    /**
     * Extract platform information from user agent
     */
    private function getPlatform(?string $userAgent): ?string
    {
        if (!$userAgent) {
            return 'Unknown';
        }

        if (preg_match('/Windows NT/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            return 'iOS';
        }

        return 'Unknown';
    }
}
