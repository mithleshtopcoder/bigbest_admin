<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTrainingAssignment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_module_id',
        'employee_profile_id',
        'onboarding_process_id',
        'assigned_date',
        'due_date',
        'completed_date',
        'status',
        'progress_percentage',
        'completion_notes',
        'assigned_by',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'due_date' => 'date',
            'completed_date' => 'date',
            'progress_percentage' => 'integer',
        ];
    }

    /**
     * Assignment belongs to a training module
     */
    public function trainingModule(): BelongsTo
    {
        return $this->belongsTo(TrainingModule::class);
    }

    /**
     * Assignment belongs to an employee profile
     */
    public function employeeProfile(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class);
    }

    /**
     * Assignment belongs to an onboarding process
     */
    public function onboardingProcess(): BelongsTo
    {
        return $this->belongsTo(OnboardingProcess::class);
    }

    /**
     * Assignment assigned by user
     */
    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
