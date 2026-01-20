<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send notification to a customer
     */
    public static function send(
        $customerId,
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $actionUrl = null,
        ?string $imageUrl = null
    ): Notification {
        $notification = Notification::create([
            'customer_id' => $customerId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'image_url' => $imageUrl,
            'sent_at' => now(),
        ]);

        // Here you can add push notification logic (FCM, etc.)
        // self::sendPushNotification($notification);

        return $notification;
    }

    /**
     * Send notification to multiple customers
     */
    public static function sendToMany(
        array $customerIds,
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $actionUrl = null,
        ?string $imageUrl = null
    ): int {
        $notifications = [];
        $now = now();

        foreach ($customerIds as $customerId) {
            $notifications[] = [
                'customer_id' => $customerId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => json_encode($data),
                'action_url' => $actionUrl,
                'image_url' => $imageUrl,
                'sent_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Notification::insert($notifications);

        // Send push notifications
        // self::sendBulkPushNotifications($customerIds, $title, $message);

        return count($notifications);
    }

    /**
     * Send order notification
     */
    public static function sendOrderNotification(
        $customerId,
        $orderId,
        string $status,
        string $message
    ): Notification {
        return self::send(
            $customerId,
            'order',
            "Order #{$orderId} {$status}",
            $message,
            ['order_id' => $orderId, 'status' => $status],
            "/orders/{$orderId}"
        );
    }

    /**
     * Send promotion notification
     */
    public static function sendPromotionNotification(
        $customerId,
        string $title,
        string $message,
        ?string $offerId = null,
        ?string $imageUrl = null
    ): Notification {
        return self::send(
            $customerId,
            'promotion',
            $title,
            $message,
            $offerId ? ['offer_id' => $offerId] : [],
            $offerId ? "/offers/{$offerId}" : null,
            $imageUrl
        );
    }

    /**
     * Send payment notification
     */
    public static function sendPaymentNotification(
        $customerId,
        $paymentId,
        string $status,
        string $message
    ): Notification {
        return self::send(
            $customerId,
            'payment',
            "Payment {$status}",
            $message,
            ['payment_id' => $paymentId, 'status' => $status],
            "/payments/{$paymentId}"
        );
    }

    /**
     * Send delivery notification
     */
    public static function sendDeliveryNotification(
        $customerId,
        $orderId,
        string $message
    ): Notification {
        return self::send(
            $customerId,
            'delivery',
            "Delivery Update",
            $message,
            ['order_id' => $orderId],
            "/orders/{$orderId}"
        );
    }
}

