<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'uuid',
        'name',
        'user_type', // user_type == admin or customer, customer == vendors
        'is_access', // is_access == admin or user, vendors's user
        'vendor_id',
        'email',
        'password',
        'mobile_number',
        'store_id',
        'status',
        'user_type',
        'created_by',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->uuid)) {
                $user->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /**
     * User belongs to a store
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * User has one employee profile
     */
    public function employeeProfile(): HasOne
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    /**
     * User belongs to many stores (many-to-many)
     */
    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, 'store_user')
            ->withTimestamps();
    }

    public function primaryStore()
{
    return $this->belongsTo(Store::class, 'store_id');
}

    /**
     * Check if user has role by slug (custom method - use hasRole() for Spatie's standard check)
     */
    public function hasRoleBySlug(string $roleSlug): bool
    {
        $query = $this->roles()->where('slug', $roleSlug);
        
        // Only filter by is_active if the column exists
        if (\Schema::hasColumn('roles', 'is_active')) {
            $query->where('is_active', true);
        }
        
        return $query->exists();
    }

    /**
     * Check permission by slug (custom method - use hasPermissionTo() for Spatie's standard check)
     * This is kept for backward compatibility with AppServiceProvider
     */
    public function hasPermission(string $permissionSlug): bool
    {
        $query = $this->roles();
        
        // Only filter by is_active if the column exists
        if (\Schema::hasColumn('roles', 'is_active')) {
            $query->where('is_active', true);
        }
        
        return $query->whereHas('permissions', function ($q) use ($permissionSlug) {
            $q->where('slug', $permissionSlug);
            
            // Only filter by is_active if the column exists
            if (\Schema::hasColumn('permissions', 'is_active')) {
                $q->where('is_active', true);
            }
        })->exists();
    }

    /**
     * Get all permissions through roles (using Spatie's method)
     * Note: Spatie's HasRoles trait provides getAllPermissions() method
     */
}