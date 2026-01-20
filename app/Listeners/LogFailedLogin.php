<?php

namespace App\Listeners;

use App\Models\FailedLoginHistory;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class LogFailedLogin
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
    public function handle(Failed $event): void
    {
        $request = Request::instance();
        $email = $event->credentials['email'] ?? $request->input('email');
        
        // Get device and platform info
        $userAgent = $request->userAgent();
        $device = $this->getDevice($userAgent);
        $platform = $this->getPlatform($userAgent);

        // Determine reason for failed login
        $reason = $this->determineReason($event);

        FailedLoginHistory::create([
            'email' => $email,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device' => $device,
            'platform' => $platform,
            'reason' => $reason,
            'attempted_at' => now(),
        ]);
    }

    /**
     * Determine the reason for failed login
     */
    private function determineReason(Failed $event): string
    {
        // The Failed event has a 'user' property only if user was found but password was wrong
        // If user is null, it means user was not found
        if (!isset($event->user) || $event->user === null) {
            return 'User Not Found';
        }

        $user = $event->user;

        // Check if account is locked or inactive
        if (isset($user->status)) {
            if ($user->status === 'inactive' || $user->status === 0 || $user->status === false) {
                return 'Account Locked';
            }
        }

        return 'Invalid Password';
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
