<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorBank extends Model
{
    protected $fillable = [
        'vendor_id', 'account_name', 'account_number', 'ifsc_code', 'bank_name', 'bank_file'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}