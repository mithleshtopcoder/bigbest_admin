<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'area_name',
        'pincode',
        'city',
        'state',
        'radius_km',
        'min_order_amount',
        'delivery_charge',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'radius_km' => 'integer',
            'min_order_amount' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
