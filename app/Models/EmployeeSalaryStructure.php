<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeSalaryStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_profile_id',
        'basic_salary',
        'hra',
        'da',
        'ta',
        'medical_allowance',
        'other_allowances',
        'pf',
        'esi',
        'tds',
        'other_deductions',
        'gross_salary',
        'net_salary',
        'effective_date',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'hra' => 'decimal:2',
            'da' => 'decimal:2',
            'ta' => 'decimal:2',
            'medical_allowance' => 'decimal:2',
            'other_allowances' => 'decimal:2',
            'pf' => 'decimal:2',
            'esi' => 'decimal:2',
            'tds' => 'decimal:2',
            'other_deductions' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'effective_date' => 'date',
            'status' => 'boolean',
        ];
    }

    /**
     * Salary structure belongs to employee profile
     */
    public function employeeProfile(): BelongsTo
    {
        return $this->belongsTo(EmployeeProfile::class);
    }
}
