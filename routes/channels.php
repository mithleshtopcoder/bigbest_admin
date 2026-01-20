<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('notifications.{customerId}', function ($user, $customerId) {
    // Only allow the authenticated customer to listen to their own notifications
    return (int) $user->id === (int) $customerId;
});