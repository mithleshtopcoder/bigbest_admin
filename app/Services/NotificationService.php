<?php

namespace App\Services;

use App\Jobs\SendNotificationJob;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

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

        Log::info('Notification record created', [
            'notification_id' => $notification->id,
            'customer_id' => $customerId,
            'type' => $type,
            'title' => $title,
        ]);

        SendNotificationJob::dispatch($notification);

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

        self::sendBulkPushNotifications($customerIds, $title, $message, $data, $actionUrl, $imageUrl);

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

    public static function sendPushNotification(Notification $notification): void
    {
        $notification->loadMissing('customer');
        $customer = $notification->customer;

        if (!$customer) {
            return;
        }

        $token = $customer->fcm_token ?: $customer->device_token;
        if (!$token) {
            Log::info('FCM token missing for customer', [
                'notification_id' => $notification->id,
                'customer_id' => $customer->id,
            ]);
            return;
        }

        try {
            $messaging = app('firebase.messaging');
        } catch (\Throwable $e) {
            Log::warning('Firebase messaging not available', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
            return;
        }

        $data = self::normalizePayload($notification->data ?? []);
        $data['notification_id'] = (string) $notification->id;
        $data['type'] = (string) $notification->type;

        if ($notification->action_url) {
            $data['action_url'] = $notification->action_url;
        }
        if ($notification->image_url) {
            $data['image_url'] = $notification->image_url;
        }

        $firebaseNotification = FirebaseNotification::create($notification->title, $notification->message);
        if ($notification->image_url) {
            $firebaseNotification = $firebaseNotification->withImageUrl($notification->image_url);
        }

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($firebaseNotification)
            ->withData($data);

        try {
            $messaging->send($message);
            Log::info('FCM notification sent', [
                'notification_id' => $notification->id,
                'customer_id' => $customer->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send FCM notification', [
                'notification_id' => $notification->id,
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private static function sendBulkPushNotifications(
        array $customerIds,
        string $title,
        string $message,
        array $data = [],
        ?string $actionUrl = null,
        ?string $imageUrl = null
    ): void {
        $customers = Customer::whereIn('id', $customerIds)
            ->get(['id', 'fcm_token', 'device_token']);

        $tokens = $customers->map(function ($customer) {
            return $customer->fcm_token ?: $customer->device_token;
        })->filter()->values()->all();

        if (empty($tokens)) {
            Log::info('FCM multicast skipped: no tokens', [
                'customer_count' => count($customerIds),
            ]);
            return;
        }

        try {
            $messaging = app('firebase.messaging');
        } catch (\Throwable $e) {
            Log::warning('Firebase messaging not available for bulk send', [
                'error' => $e->getMessage(),
            ]);
            return;
        }

        $payload = self::normalizePayload($data);
        if ($actionUrl) {
            $payload['action_url'] = $actionUrl;
        }
        if ($imageUrl) {
            $payload['image_url'] = $imageUrl;
        }

        $firebaseNotification = FirebaseNotification::create($title, $message);
        if ($imageUrl) {
            $firebaseNotification = $firebaseNotification->withImageUrl($imageUrl);
        }

        $message = CloudMessage::new()
            ->withNotification($firebaseNotification)
            ->withData($payload);

        try {
            $report = $messaging->sendMulticast($message, $tokens);
            Log::info('FCM multicast sent', [
                'successes' => $report->successCount(),
                'failures' => $report->failureCount(),
            ]);
            if ($report->failureCount() > 0) {
                Log::warning('FCM multicast had failures', [
                    'failures' => $report->failureCount(),
                    'successes' => $report->successCount(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send FCM multicast', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    private static function normalizePayload(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (is_scalar($value)) {
                $normalized[$key] = (string) $value;
            } else {
                $normalized[$key] = json_encode($value);
            }
        }

        return $normalized;
    }
}

