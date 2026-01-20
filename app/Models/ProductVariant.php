<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'name',
        'code',
        'sku',
        'barcode',
        'unit',
        'unit_value',
        'is_default',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

   public function prices()
{
    return $this->hasMany(ProductPrice::class, 'product_variant_id');
}
    

   public function stocks()
{
    return $this->hasMany(ProductStock::class, 'product_variant_id');
}


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}