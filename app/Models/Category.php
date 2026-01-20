<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'label',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getImageAttribute($value)
{
    return $value ? url('images/categories/' . $value) : null;
}

public function products()
{
    return $this->hasMany(Product::class);
}
    /**
     * Get the sub-categories for the category.
     */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class)->where('is_active', true)->orderBy('sort_order');
    }
}