<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'working_days',
        'description',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'working_days' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Shift created by user
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Shift has many assignments
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class);
    }
}
