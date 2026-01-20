<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserLogout
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
    public function handle(Logout $event): void
    {
        $user = $event->user;
        
        if ($user) {
            // Update the most recent login history record for this user
            $latestLogin = LoginHistory::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->orderBy('login_at', 'desc')
                ->first();

            if ($latestLogin) {
                $latestLogin->update([
                    'logout_at' => now(),
                ]);
            }
        }
    }
}
