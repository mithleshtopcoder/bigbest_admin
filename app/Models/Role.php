<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Role extends SpatieRole
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'guard_name',
        'is_system',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if role has a specific permission by slug
     */
    public function hasPermission(string $permissionSlug): bool
    {
        $query = $this->permissions()->where('slug', $permissionSlug);
        
        // Only filter by is_active if the column exists
        if (Schema::hasColumn('permissions', 'is_active')) {
            $query->where('is_active', true);
        }
        
        return $query->exists();
    }
}