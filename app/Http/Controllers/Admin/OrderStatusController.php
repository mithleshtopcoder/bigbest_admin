<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderStatusController extends Controller
{
    /**
     * Display orders filtered by status (ONLINE orders only)
     */
    public function index(string $status)
{
    $user = auth()->user();
    $isVendor = $user->user_type === 'vendor';

    // URL → DB status mapping
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

    // ✅ BASE QUERY → ONLINE ONLY
    $query = Order::with(['customer', 'deliveryAddress', 'items'])
        ->where('order_source', 'online')
        ->latest();

    /**
     * 🔐 VENDOR FILTER
     * Show ONLY orders having order_items.vendor_id = auth vendor_id
     */
    if ($isVendor) {
        $query->whereHas('items', function ($q) use ($user) {
            $q->where('vendor_id', $user->vendor_id);
        });
    }

    // Status filter (except "all")
    if (! is_null($statusMap[$status])) {
        $query->where('status', $statusMap[$status]);
    }

    $orders = $query->paginate(10);

    // Order workflow
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
    $user = auth()->user();
    $isVendor = $user->user_type === 'vendor';

    abort_if($order->order_source !== 'online', 403);

    // 🔐 Vendor ownership check
    if ($isVendor) {
        $hasAccess = $order->items()
            ->where('vendor_id', $user->vendor_id)
            ->exists();

        abort_unless($hasAccess, 403);
    }

    $request->validate([
        'status' => 'required|in:pending,confirmed,processing,ready_to_ship,out_for_delivery,shipped,delivered,cancelled',
    ]);

    if (in_array($order->status, ['delivered', 'cancelled'])) {
        return back()->with('error', 'This order cannot be updated.');
    }

    $order->update([
        'status' => $request->status,
    ]);

    return back()->with('success', 'Order status updated successfully.');
}

}