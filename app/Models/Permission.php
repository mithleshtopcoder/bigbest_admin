<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends SpatiePermission
{
    use SoftDeletes;

    protected $fillable = [
        'permission_category_id',
        'name',
        'slug',
        'description',
        'guard_name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'permission_category_id' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the category that owns the permission
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PermissionCategory::class, 'permission_category_id');
    }
}
