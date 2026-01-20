<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_profile_id',
        'salary_structure_id',
        'payroll_period',
        'period_start_date',
        'period_end_date',
        'working_days',
        'present_days',
        'absent_days',
        'leave_days',
        'holiday_days',
        'weekend_days',
        'basic_salary',
        'gross_salary',
        'net_salary',
        'total_allowances',
        'total_deductions',
        'attendance_bonus',
        'overtime_amount',
        'advance_deduction',
        'other_adjustments',
        'status',
        'payment_date',
        'remarks',
        'processed_by',
        'approved_by',
        'processed_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start_date' => 'date',
            'period_end_date' => 'date',
            'payment_date' => 'date',
            'processed_at' => 'datetime',
            'approved_at' => 'datetime',
            'basic_salary' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'attendance_bonus' => 'decimal:2',
            'overtime_amount' => 'decimal:2',
            'advance_deduction' => 'decimal:2',
            'other_adjustments' => 'decimal:2',
        ];
    }

    /**
     * Payroll belongs to employee profile
     */
    public function employeeProfile(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class);
    }

    /**
     * Payroll belongs to salary structure
     */
    public function salaryStructure(): BelongsTo
    {
        return $this->belongsTo(EmployeeSalaryStructure::class, 'salary_structure_id');
    }

    /**
     * Payroll has many items
     */
    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->orderBy('sort_order');
    }

    /**
     * Payroll processed by user
     */
    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Payroll approved by user
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
