<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\VendorPayout;

class VendorDashboardController extends Controller
{
    /**
     * Show vendor dashboard
     */
    public function index()
    {
        $vendor = Auth::user(); // logged-in vendor

        // Total earned from delivered orders
        $totalEarned = OrderItem::where('vendor_id', $vendor->vendor_id)
                            ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
                            ->sum('total_price');

        // Total paid to vendor
        $totalPaid = VendorPayout::where('vendor_id', $vendor->vendor_id)
                            ->where('status', 'paid')
                            ->sum('amount');

        // Remaining payable
        $remainingPayable = $totalEarned - $totalPaid;

        // Latest 5 payouts
        $recentPayouts = VendorPayout::where('vendor_id', $vendor->vendor_id)
                                ->latest()
                                ->take(5)
                                ->get();

        return view('admin.vendors.dashboard', compact(
            'vendor',
            'totalEarned',
            'totalPaid',
            'remainingPayable',
            'recentPayouts'
        ));
    }
}