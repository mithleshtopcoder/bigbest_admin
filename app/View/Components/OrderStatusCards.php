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

    $isSuperAdmin = $user->roles()
        ->where('name', 'Super Admin')
        ->exists();

    /** ================= STORE ACCESS ================= */
    $storeIds = collect();

    if (! $isSuperAdmin) {

        if ($user->store_id) {
            $storeIds->push($user->store_id);
        }

        $storeIds = $storeIds
            ->merge($user->stores()->pluck('stores.id'))
            ->unique();
    }

    /** ================= ONLINE BASE QUERY ================= */
    $onlineQuery = Order::where('order_source', 'online');

    if (! $isSuperAdmin) {
        if ($storeIds->isEmpty()) {
            $onlineQuery->whereRaw('1 = 0');
        } else {
            $onlineQuery->whereIn('store_id', $storeIds);
        }
    }

    /** ================= POS BASE QUERY ================= */
    $posQuery = Order::where('order_source', 'pos');

    if (! $isSuperAdmin) {
        if ($storeIds->isEmpty()) {
            $posQuery->whereRaw('1 = 0');
        } else {
            $posQuery->whereIn('store_id', $storeIds);
        }
    }

    /** ================= COUNTS ================= */

    // 🔵 New Online Orders
    $this->newOrderCount = (clone $onlineQuery)
        ->where('order_source', 'online')
        ->count();

    // 🟡 New POS Orders (ONLY HERE)
    $this->newPosOrderCount = (clone $posQuery)
    ->whereIn('status', ['pending', 'hold','delivered'])
    ->count();

    // Online totals
    $this->totalOrders = (clone $onlineQuery)->count();
    $this->totalAmount = (clone $onlineQuery)->sum('total_amount');

    // 🚨 STATUS COUNTS → ONLINE ONLY
    $this->statusCounts = (clone $onlineQuery)
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();
}


    public function render()
    {
        return view('components.order-status-cards');
    }
}