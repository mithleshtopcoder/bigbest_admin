<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'country',
        'latitude',
        'longitude',
        'manager_name',
        'manager_phone',
        'status',
        'is_online',
        'opening_time',
        'closing_time',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'is_online' => 'boolean',
        ];
    }

    public function serviceAreas()
    {
        return $this->hasMany(StoreServiceArea::class);
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function employeeProfiles()
    {
        return $this->hasMany(EmployeeProfile::class, 'store_id');
    }

    /**
     * Store has many users (many-to-many)
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'store_user')
            ->withTimestamps();
    }
}