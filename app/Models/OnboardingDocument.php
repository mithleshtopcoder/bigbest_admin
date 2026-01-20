<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardingDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'onboarding_process_id',
        'document_type',
        'document_name',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'status',
        'verification_notes',
        'submitted_date',
        'verified_date',
        'verified_by',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_date' => 'date',
            'verified_date' => 'date',
        ];
    }

    /**
     * Document belongs to an onboarding process
     */
    public function onboardingProcess(): BelongsTo
    {
        return $this->belongsTo(OnboardingProcess::class);
    }

    /**
     * Document verified by user
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
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
