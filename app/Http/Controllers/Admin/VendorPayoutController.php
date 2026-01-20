<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorPayoutController extends Controller
{
    /**
     * List all payouts
     */
    public function index()
    {
        $payouts = VendorPayout::with('vendor')
            ->latest()
            ->paginate(20);

        return view('admin.vendor_payouts.index', compact('payouts'));
    }

    /**
     * Show create payout form
     */
    public function create()
    {
        $vendors = Vendor::where('status', 'approved')->get();

        return view('admin.vendor_payouts.create', compact('vendors'));
    }

    /**
     * Store payout request
     */
    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount'    => 'required|numeric|min:1',
            'note'      => 'nullable|string|max:500',
        ]);

        $vendor = Vendor::findOrFail($request->vendor_id);

        $payable = $vendor->payableAmount();

        if ($payable <= 0) {
            return back()->withErrors([
                'amount' => 'Vendor has no payable balance'
            ]);
        }

        if ($request->amount > $payable) {
            return back()->withErrors([
                'amount' => 'Amount exceeds vendor payable balance'
            ]);
        }

        VendorPayout::create([
            'vendor_id' => $vendor->id,
            'amount'    => $request->amount,
            'status'    => 'pending',
            'note'      => $request->note,
        ]);

        return redirect()
            ->route('vendor-payouts.index')
            ->with('success', 'Payout request created successfully');
    }

    /**
     * Mark payout as paid
     */
    public function markPaid(Request $request, VendorPayout $payout)
    {
        if ($payout->status === 'paid') {
            return back()->withErrors('This payout is already marked as paid.');
        }

        $request->validate([
            'payment_method' => 'required|string|max:100',
            'reference_no'   => 'required|string|max:150',
        ]);

        DB::transaction(function () use ($request, $payout) {

            $payout->update([
                'status'         => 'paid',
                'payment_method' => $request->payment_method,
                'reference_no'   => $request->reference_no,
                'paid_at'        => now(),
            ]);

        });

        return back()->with('success', 'Payout marked as paid successfully');
    }
}