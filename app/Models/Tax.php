<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'rate',
        'cgst_rate',
        'sgst_rate',
        'igst_rate',
        'description',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * Get all products using this tax
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
