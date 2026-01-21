<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'status',
        'payment_method',
        'reference_no',
        'note',
        'paid_at',
    ];

    // Relation to the user/vendor
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id'); // your vendor_id links to users table
    }
}