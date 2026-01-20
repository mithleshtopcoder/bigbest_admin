<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardingProcess extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'candidate_id',
        'employee_profile_id',
        'user_id',
        'joining_date',
        'completion_date',
        'status',
        'notes',
        'assigned_to',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    /**
     * Onboarding process belongs to a candidate
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    /**
     * Onboarding process belongs to an employee profile
     */
    public function employeeProfile(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class);
    }

    /**
     * Onboarding process belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Onboarding process assigned to a user
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Onboarding process has many documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(OnboardingDocument::class);
    }

    /**
     * Onboarding process has many checklists
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(JoiningChecklist::class);
    }

    /**
     * Onboarding process has many training assignments
     */
    public function trainingAssignments(): HasMany
    {
        return $this->hasMany(EmployeeTrainingAssignment::class);
    }

    /**
     * Created by user
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated by user
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
