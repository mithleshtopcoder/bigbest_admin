<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'content',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get policy by type
     */
    public static function getByType($type)
    {
        return self::where('type', $type)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();
    }

    /**
     * Get all active policies
     */
    public static function getAllActive()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type');
    }
}
