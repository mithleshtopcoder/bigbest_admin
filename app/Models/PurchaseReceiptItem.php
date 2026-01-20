<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseReceiptItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_receipt_id',
        'purchase_order_item_id',
        'product_variant_id',
        'received_quantity',
        'damaged_quantity',
        'returned_quantity',
        'accepted_quantity',
        'unit_cost',
        'total_cost',
        'expiry_date',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'received_quantity' => 'integer',
            'damaged_quantity' => 'integer',
            'returned_quantity' => 'integer',
            'accepted_quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
            'expiry_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Item belongs to a purchase receipt
     */
    public function purchaseReceipt(): BelongsTo
    {
        return $this->belongsTo(PurchaseReceipt::class);
    }

    /**
     * Item belongs to a purchase order item
     */
    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    /**
     * Item belongs to a product variant
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Item has many invoice items
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    /**
     * Item has many return items
     */
    public function returnItems(): HasMany
    {
        return $this->hasMany(PurchaseReturnItem::class);
    }

}