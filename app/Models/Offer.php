<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'offer_type',
        'discount_type',
        'discount_value',
        'buy_quantity',
        'get_quantity',
        'minimum_order_amount',
        'maximum_discount_amount',
        'product_id',
        'category_id',
        'sub_category_id',
        'brand_id',
        'valid_from',
        'valid_to',
        'valid_from_time',
        'valid_to_time',
        'priority',
        'is_active',
        'is_featured',
        'terms_conditions',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'minimum_order_amount' => 'decimal:2',
            'maximum_discount_amount' => 'decimal:2',
            'valid_from' => 'date',
            'valid_to' => 'date',
            'buy_quantity' => 'integer',
            'get_quantity' => 'integer',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Check if offer is valid
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($now->lt($this->valid_from) || $now->gt($this->valid_to)) {
            return false;
        }

        if ($this->valid_from_time && $this->valid_to_time) {
            $currentTime = $now->format('H:i:s');
            if ($currentTime < $this->valid_from_time || $currentTime > $this->valid_to_time) {
                return false;
            }
        }

        return true;
    }
}
