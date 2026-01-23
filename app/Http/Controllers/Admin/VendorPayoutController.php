<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Users act as vendors
use App\Models\VendorPayout;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorPayoutController extends Controller
{
    /**
     * List payouts
     */
    public function index()
{
    // Get all vendors
    $vendors = \App\Models\User::where('user_type', 'vendor')
        ->with('vendorDocument') // eager load bank details
        ->get()
        ->map(function ($vendor) {

            // Remaining amount to pay
            $remaining = $vendor->payableAmount();

            // Total earned from delivered order items
            $totalEarned = \App\Models\OrderItem::where('vendor_id', $vendor->vendor_id)
                                ->whereHas('order', fn($q) => $q->where('status', 'delivered'))
                                ->sum('total_price');

            // Total paid = total earned - remaining
            $totalPaid = $totalEarned - $remaining;

            $vendor->totalEarned = $totalEarned;
            $vendor->totalPaid = $totalPaid;
            $vendor->remainingPayable = $remaining;

            return $vendor;
        });

    return view('admin.vendor_payouts.index', compact('vendors'));
}




    /**
     * Show create payout form
     */
    public function create(Request $request)
{
    // Get vendor_id from query string (from index Pay button)
    $vendorId = $request->query('vendor_id');

    if (!$vendorId) {
        abort(404, 'Vendor not found');
    }

    // Load vendor using the vendor_id column
    $vendor = User::with('vendorDocument')
        ->where('vendor_id', $vendorId)
        ->firstOrFail(); // throws 404 if not found

    // Calculate remaining payable
    $remainingPayable = $vendor->payableAmount();

    return view('admin.vendor_payouts.create', compact('vendor', 'remainingPayable'));
}


    /**
     * Store payout request
     */
 public function store(Request $request)
{
    $user = Auth::user();

    // Only Admin can store payouts
    if ($user->user_type === 'vendor') {
        abort(403, 'Unauthorized');
    }

    $request->validate([
        'vendor_id'      => 'required|exists:users,vendor_id', // match your model
        'amount'         => 'required|numeric|min:1',
        'payment_method' => 'required|string|max:100',
        'reference_no'   => 'required|string|max:150',
        'note'           => 'nullable|string|max:500',
    ]);

    // Find vendor by vendor_id (not id)
    $vendor = User::where('vendor_id', $request->vendor_id)->firstOrFail();

    // Check remaining payable
    if (method_exists($vendor, 'payableAmount')) {
        $payable = $vendor->payableAmount();

        if ($payable <= 0) {
            return back()->withErrors(['amount' => 'Vendor has no payable balance']);
        }

        if ($request->amount > $payable) {
            return back()->withErrors(['amount' => 'Amount exceeds vendor payable balance']);
        }
    }

    // Create payout record
    VendorPayout::create([
        'vendor_id'      => $vendor->vendor_id, // matches User.vendor_id
        'amount'         => $request->amount,
        'status'         => 'paid',
        'payment_method' => $request->payment_method,
        'reference_no'   => $request->reference_no,
        'note'           => $request->note,
        'paid_at'        => null,
    ]);

    return redirect()
        ->route('vendor-payouts.index')
        ->with('success', 'Payout request created successfully');
}

public function transactions(\App\Models\User $vendor)
{
    $transactions = \App\Models\VendorPayout::where('vendor_id', $vendor->vendor_id)
                        ->latest()
                        ->get();

    return view('admin.vendor_payouts.transactions', compact('vendor', 'transactions'));
}

// Generate PDF for transactions
public function transactionsPdf(\App\Models\User $vendor)
{
    $transactions = \App\Models\VendorPayout::where('vendor_id', $vendor->vendor_id)
                        ->latest()
                        ->get();

    $pdf = Pdf::loadView('admin.vendor_payouts.transactions_pdf', compact('vendor', 'transactions'));

    return $pdf->download("Vendor_{$vendor->vendor_id}_Transactions.pdf");
}
    /**
     * Mark payout as paid
     */
    public function markPaid(Request $request, VendorPayout $payout)
    {
        $user = Auth::user();

        // Only Admin can mark as paid
        if ($user->user_type === 'vendor') {
            abort(403, 'Unauthorized');
        }

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