<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_transfer_id',
        'product_variant_id',
        'requested_quantity',
        'approved_quantity',
        'transferred_quantity',
        'received_quantity',
        'damaged_quantity',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'requested_quantity' => 'integer',
            'approved_quantity' => 'integer',
            'transferred_quantity' => 'integer',
            'received_quantity' => 'integer',
            'damaged_quantity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
