<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'name',
        'vendor_uid',
        'email',
        'phone',
        'password',
        'store_name',
        'address',

        // KYC
        'pan_number',
        'pan_file',
        'gst_number',
        'gst_file',
        'address_proof_type',
        'address_proof_file',

        'status',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
    ];

    /**
     * =======================
     * Relationships
     * =======================
     */

    // Vendor Bank Details
    public function bank()
    {
        return $this->hasOne(VendorBank::class);
    }

    // Vendor Payouts
    public function payouts()
    {
        return $this->hasMany(VendorPayout::class);
    }

    // Vendor Orders (store_id maps to vendor/store)
    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    /**
     * =======================
     * Earnings & Payout Logic
     * =======================
     */

    /**
     * Total earnings from delivered orders
     */
    public function totalEarnings(): float
    {
        return (float) $this->orders()
            ->where('status', 'delivered')
            ->with('items')
            ->get()
            ->flatMap->items
            ->sum('total_price');
    }

    /**
     * Total amount already paid to vendor
     */
    public function totalPaid(): float
    {
        return (float) $this->payouts()
            ->where('status', 'paid')
            ->sum('amount');
    }

    /**
     * Remaining payable amount
     */
    public function payableAmount(): float
    {
        return max(0, $this->totalEarnings() - $this->totalPaid());
    }

    /**
     * =======================
     * Status Helpers
     * =======================
     */

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}