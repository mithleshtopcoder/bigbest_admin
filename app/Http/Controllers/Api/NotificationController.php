<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\NotificationEvent;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications for authenticated customer
     */
    public function index(Request $request)
    {
        try {
            $customer = $request->user();

            $query = Notification::where('customer_id', $customer->id)
                ->orderBy('created_at', 'desc');

            // Filter by read/unread
            if ($request->has('status')) {
                if ($request->status === 'unread') {
                    $query->where('is_read', false);
                } elseif ($request->status === 'read') {
                    $query->where('is_read', true);
                }
            }

            // Filter by type
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $notifications = $query->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully',
                'data' => $notifications,
                'unread_count' => $customer->unreadNotificationsCount(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request)
    {
        try {
            $customer = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'unread_count' => $customer->unreadNotificationsCount(),
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show single notification
     */
    public function show(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $notification = Notification::where('id', $id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            // Mark as read when viewing
            if (!$notification->is_read) {
                $notification->markAsRead();
            }

            return response()->json([
                'success' => true,
                'message' => 'Notification retrieved successfully',
                'data' => $notification,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve notification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $notification = Notification::where('id', $id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'data' => $notification,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $customer = $request->user();

            $updated = Notification::where('customer_id', $customer->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'data' => [
                    'updated_count' => $updated,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, $id)
    {
        try {
            $customer = $request->user();

            $notification = Notification::where('id', $id)
                ->where('customer_id', $customer->id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead(Request $request)
    {
        try {
            $customer = $request->user();

            $deleted = Notification::where('customer_id', $customer->id)
                ->where('is_read', true)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'All read notifications deleted',
                'data' => [
                    'deleted_count' => $deleted,
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete read notifications',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create and broadcast notification
     * 
     * Use this method whenever you want to send a notification
     */
   public function createNotification($customerId, $type, $title, $message, $data = [])
{
    $notification = Notification::create([
        'customer_id' => $customerId,
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'data' => $data,
        'sent_at' => now(),
        'is_read' => false,
    ]);

    // Dispatch a job to broadcast the notification
    SendNotificationJob::dispatch($notification);

    return $notification;
}

}