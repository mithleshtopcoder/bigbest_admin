<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_adjustment_id',
        'product_variant_id',
        'current_quantity',
        'adjusted_quantity',
        'new_quantity',
        'unit_cost',
        'total_value',
        'remark',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'current_quantity' => 'integer',
            'adjusted_quantity' => 'integer',
            'new_quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'total_value' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    public function stockAdjustment()
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
