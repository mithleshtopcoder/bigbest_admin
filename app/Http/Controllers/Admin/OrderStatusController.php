<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderStatusController extends Controller
{
    /**
     * Display orders filtered by status (ONLINE orders only)
     */
    public function index(string $status)
    {
        // URL status → DB status mapping
        $statusMap = [
            'all'               => null,
            'pending'           => 'pending',
            'accepted'          => 'confirmed',
            'preparing'         => 'processing',
            'ready'             => 'ready_to_ship',
            'shipped'           => 'shipped',
            'out-for-delivery'  => 'out_for_delivery',
            'completed'         => 'delivered',
            'cancelled'         => 'cancelled',
        ];

        abort_unless(array_key_exists($status, $statusMap), 404);

        // ✅ Base query → ONLINE orders only
        $query = Order::with(['customer', 'deliveryAddress', 'items'])
            ->where('order_source', 'online')
            ->latest();

        // Apply status filter (except "all")
        if (!is_null($statusMap[$status])) {
            $query->where('status', $statusMap[$status]);
        }

        $orders = $query->paginate(10);

        // Order workflow sequence
        $statusSequence = [
            'pending',
            'confirmed',
            'processing',
            'ready_to_ship',
            'shipped',
            'out_for_delivery',
            'delivered',
            'cancelled',
        ];

        // Labels for UI
        $statusLabels = [
            'pending'           => 'Pending',
            'confirmed'         => 'Accepted',
            'processing'        => 'Preparing',
            'ready_to_ship'     => 'Ready',
            'shipped'           => 'Shipped',
            'out_for_delivery'  => 'Out for Delivery',
            'delivered'         => 'Completed',
            'cancelled'         => 'Cancelled',
        ];

        // Compute allowed next statuses per order
        foreach ($orders as $order) {
            $currentIndex = array_search($order->status, $statusSequence);

            $order->nextStatuses = $currentIndex !== false
                ? array_slice($statusSequence, $currentIndex + 1)
                : [];
        }

        return view('order-status.index', compact(
            'orders',
            'status',
            'statusLabels'
        ));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Ensure only ONLINE orders are updated
        abort_if($order->order_source !== 'online', 403);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,ready_to_ship,out_for_delivery,shipped,delivered,cancelled',
        ]);

        // Prevent updating completed/cancelled orders
        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return redirect()->back()
                ->with('error', 'This order cannot be updated.');
        }

        $order->update([
            'status' => $request->status,
        ]);

        Log::info('Order status updated (admin)', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'previous_status' => $order->getOriginal('status'),
            'new_status' => $request->status,
            'customer_id' => $order->customer_id,
        ]);

        if ($order->customer_id) {
            $statusLabel = str_replace('_', ' ', $request->status);
            $message = "Your order #{$order->order_number} status updated to {$statusLabel}.";

            Log::info('Triggering order status notification', [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'status' => $request->status,
            ]);

            NotificationService::send(
                $order->customer_id,
                'order',
                "Order {$statusLabel}",
                $message,
                ['order_id' => $order->id, 'status' => $request->status],
                "/orders/{$order->id}"
            );
        } else {
            Log::warning('Order status updated but no customer_id', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }
}