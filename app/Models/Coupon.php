<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'coupon_type',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'maximum_discount_amount',
        'applicable_to',
        'category_id',
        'sub_category_id',
        'product_id',
        'brand_id',
        'valid_from',
        'valid_to',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'is_active',
        'is_first_order_only',
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
            'usage_limit' => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
            'is_first_order_only' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Check if coupon is valid
     */
    public function isValid($customerId = null, $orderAmount = 0)
    {
        if (!$this->is_active) {
            return false;
        }

        if (now()->lt($this->valid_from) || now()->gt($this->valid_to)) {
            return false;
        }

        if ($orderAmount < $this->minimum_order_amount) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($customerId) {
            $userUsageCount = $this->usages()
                ->where('customer_id', $customerId)
                ->count();

            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($orderAmount)
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($orderAmount * $this->discount_value) / 100;
            if ($this->maximum_discount_amount) {
                $discount = min($discount, $this->maximum_discount_amount);
            }
            return round($discount, 2);
        } else {
            return min($this->discount_value, $orderAmount);
        }
    }
}
