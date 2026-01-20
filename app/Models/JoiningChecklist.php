<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JoiningChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'onboarding_process_id',
        'checklist_template_id',
        'task_name',
        'description',
        'task_category',
        'status',
        'due_date',
        'completed_date',
        'completion_notes',
        'assigned_to',
        'completed_by',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Checklist belongs to an onboarding process
     */
    public function onboardingProcess(): BelongsTo
    {
        return $this->belongsTo(OnboardingProcess::class);
    }

    /**
     * Checklist belongs to a template
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(JoiningChecklistTemplate::class, 'checklist_template_id');
    }

    /**
     * Checklist assigned to user
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Checklist completed by user
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }
}
