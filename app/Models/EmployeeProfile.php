<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_profile';

    protected $fillable = [
    'user_id',
    'employee_code',
    'employee_type_id',
    'department_id',
    'designation_id',
    'store_id',
    'joining_date',
    'report_to',
    'resign_date',
    'resign_status',
    'expresnce',
    'date_brith',
    'marital_status_id',
    'blood_group_id',
    'emergency_contact',
    'emergency_contact_name',
    'emergency_contact_relation',
    'emergency_contact_relation_name',
    'pan_no',
    'aadhar_no',
    'passport_no',
    'memo',
    'address_line_1',
    'address_line_2',
    'country',
    'state',
    'city',
    'pincode',
    'mobile_number',
    'phone',
    'user_name',
];


    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'resign_date' => 'date',
            'date_brith' => 'date',
            'resign_status' => 'boolean',
        ];
    }

    /**
     * Employee profile belongs to a user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Employee profile belongs to employee type (option)
     */
    public function employeeType(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'employee_type_id');
    }

    /**
     * Employee profile belongs to a department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Employee profile belongs to a designation
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * Employee profile belongs to a store
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * Employee profile reports to a user
     */
    public function reportingTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'report_to');
    }

    /**
     * Employee profile belongs to marital status (option)
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'marital_status_id');
    }

    /**
     * Employee profile belongs to blood group (option)
     */
    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'blood_group_id');
    }

    /**
     * Employee profile has many educational infos
     */
    public function educationalInfos(): HasMany
    {
        return $this->hasMany(EmployeeEducationalInfo::class)->orderBy('sort_order');
    }

    /**
     * Employee profile has many experiences
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(EmployeeExperience::class)->orderBy('sort_order');
    }

    /**
     * Employee profile has many documents
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class)->orderBy('sort_order');
    }

    /**
     * Employee profile has many salary structures
     */
    public function salaryStructures(): HasMany
    {
        return $this->hasMany(EmployeeSalaryStructure::class)->orderBy('effective_date', 'desc');
    }

    /**
     * Employee profile has many attendances
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class)->orderBy('attendance_date', 'desc');
    }

    /**
     * Employee profile has many leaves
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class)->orderBy('from_date', 'desc');
    }

    /**
     * Employee profile has many shift assignments
     */
    public function shiftAssignments(): HasMany
    {
        return $this->hasMany(ShiftAssignment::class)->orderBy('effective_from', 'desc');
    }

    /**
     * Employee profile has many payrolls
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class)->orderBy('payroll_period', 'desc');
    }
}