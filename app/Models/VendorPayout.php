<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'remark',
        'created_by',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Vendor relation
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Admin who created payout
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}