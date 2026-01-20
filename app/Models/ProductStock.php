<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ProductStock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'store_id',
        'quantity',
        'reserved_quantity',
        'in_transit_quantity',
        'pending_quantity',
        'min_stock_level',
        'max_stock_level',
        'stock_status',
        'last_restocked_at',
        'last_restocked_by',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'reserved_quantity' => 'integer',
            'in_transit_quantity' => 'integer',
            'pending_quantity' => 'integer',
            'min_stock_level' => 'integer',
            'max_stock_level' => 'integer',
            'last_restocked_at' => 'date',
        ];
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function lastRestockedByUser()
    {
        return $this->belongsTo(User::class, 'last_restocked_by');
    }
}
