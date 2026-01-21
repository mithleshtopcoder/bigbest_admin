<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id',
        'slug',
        'sku',
        'description',
        'short_description',
        'category_id',
        'sub_category_id',
        'brand_id',
        'tax_id',
        'barcode',
        'item_type',
        'item_code',
        'collection',
        'season',
        'length',
        'width',
        'height',
        'packaging_type',
        'shelf_life_days',
        'origin_country',
        'ingredients',
        'nutritional_info',
        'storage_location',
        'storage_instructions',
        'thumbnail_image',
        'min_order_quantity',
        'max_order_quantity',
        'status',
        'variant_type',
        'is_featured',
        'top_selling_product',
        'sort_order',
        'view_count',
        'rating',
        'total_reviews',
        'vendor_id',
         'approval_status',
    'rejection_reason',
    'approved_at',
    'approved_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'top_selling_product' => 'boolean',
        'item_type' => 'integer',
        'collection' => 'integer',
        'season' => 'integer',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
        'sort_order' => 'integer',
        'view_count' => 'integer',
        'rating' => 'float',
        'total_reviews' => 'integer',
    ];


    // Relationships
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

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    // Stocks via variants (no ambiguity)
    public function stocks()
    {
        return $this->hasManyThrough(
            ProductStock::class,
            ProductVariant::class,
            'product_id',         // Foreign key on ProductVariant table
            'product_variant_id', // Foreign key on ProductStock table
            'id',                 // Local key on Product table
            'id'                  // Local key on ProductVariant table
        );
    }

    // Helper to get total stock easily
    public function getTotalStockAttribute()
    {
        return $this->variants->sum(function ($variant) {
            return $variant->stocks->sum('quantity');
        });
    }

    public function getThumbnailImageAttribute($value)
{
    return $value ? url('images/product_thumbnail/' . $value) : null;
}

    

    // Helper to get total variant count
    public function getVariantCountAttribute()
    {
        return $this->variants->count();
    }

    /**
     * Product has many reviews
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Product has many approved reviews
     */
    public function approvedReviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }

 public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id', 'id')
                ->where('user_type', 'vendor'); // ensures only vendor users
}
}