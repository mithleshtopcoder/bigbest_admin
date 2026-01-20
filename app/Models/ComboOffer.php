<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComboOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'original_price',
        'discounted_price',
        'discount_amount',
        'discount_percentage',
        'min_quantity',
        'max_quantity',
        'valid_from',
        'valid_to',
        'valid_from_time',
        'valid_to_time',
        'stock_quantity',
        'sold_quantity',
        'priority',
        'is_active',
        'is_featured',
        'terms_conditions',
    ];

    protected function casts(): array
    {
        return [
            'original_price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'min_quantity' => 'integer',
            'max_quantity' => 'integer',
            'valid_from' => 'date',
            'valid_to' => 'date',
            'stock_quantity' => 'integer',
            'sold_quantity' => 'integer',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function items()
    {
        return $this->hasMany(ComboOfferItem::class)->orderBy('sort_order');
    }

    /**
     * Check if combo offer is valid
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

        if ($this->stock_quantity && $this->sold_quantity >= $this->stock_quantity) {
            return false;
        }

        return true;
    }

    /**
     * Check stock availability
     */
    public function isAvailable($quantity = 1)
    {
        if ($this->stock_quantity) {
            return ($this->sold_quantity + $quantity) <= $this->stock_quantity;
        }
        return true;
    }
}
