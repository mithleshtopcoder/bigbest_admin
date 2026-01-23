<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderStatusCards extends Component
{
    public $totalOrders;
    public $totalAmount;
    public $statusCounts;
    public $currentStatus;
    public $newOrderCount;
    public $newPosOrderCount;

   public function __construct($currentStatus = null)
{
    $this->currentStatus = $currentStatus;

    $user = Auth::user();
    $isVendor = $user->user_type === 'vendor';

    /** ================= ONLINE BASE QUERY ================= */
    $onlineQuery = Order::where('order_source', 'online');

    /** ================= POS BASE QUERY ================= */
    $posQuery = Order::where('order_source', 'pos');

    /**
     * 🔒 VENDOR FILTER
     * Only orders having order_items.vendor_id = auth vendor_id
     */
    if ($isVendor) {
        $onlineQuery->whereHas('items', function ($q) use ($user) {
            $q->where('vendor_id', $user->vendor_id);
        });

        $posQuery->whereHas('items', function ($q) use ($user) {
            $q->where('vendor_id', $user->vendor_id);
        });
    }

    /** ================= COUNTS ================= */

    // 🔵 New Online Orders
    $this->newOrderCount = (clone $onlineQuery)->count();

    // 🟡 POS Orders
    $this->newPosOrderCount = (clone $posQuery)
        ->whereIn('status', ['pending', 'hold', 'delivered'])
        ->count();

    // 🟢 Online totals
    $this->totalOrders = (clone $onlineQuery)->count();
    $this->totalAmount = (clone $onlineQuery)->sum('total_amount');

    // 🔴 Status-wise counts (ONLINE ONLY)
    $this->statusCounts = (clone $onlineQuery)
        ->select('status', DB::raw('COUNT(DISTINCT orders.id) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();
}



    public function render()
    {
        return view('components.order-status-cards');
    }
}