<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_return_id',
        'purchase_receipt_item_id',
        'product_variant_id',
        'returned_quantity',
        'unit_cost',
        'total_cost',
        'tax_rate',
        'tax_amount',
        'reason',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'returned_quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'tax_rate' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Item belongs to a purchase return
     */
    public function purchaseReturn(): BelongsTo
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    /**
     * Item belongs to a purchase receipt item
     */
    public function purchaseReceiptItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseReceiptItem::class);
    }

    /**
     * Item belongs to a product variant
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
