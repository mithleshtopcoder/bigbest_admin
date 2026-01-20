<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\PayrollItem;
use App\Models\EmployeeProfile;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeAttendance;
use App\Models\AppSetting;
use App\Models\EmployeeLeave;
use App\Models\EmployeeHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\MyHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    /**
     * Display payroll management page
     */
    public function index()
    {
        return view('employee-management.payroll.index');
    }

    /**
     * Get payrolls for DataTables
     */
    public function getPayrolls(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $employeeId = $request->input('employee_id');
        $payrollPeriod = $request->input('payroll_period');
        $status = $request->input('status');

        $query = Payroll::with(['employeeProfile.user', 'salaryStructure']);

        if (!empty($search)) {
            $query->whereHas('employeeProfile.user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $search . '%');
            })->orWhere('payroll_period', 'like', '%' . $search . '%');
        }

        if (!empty($employeeId)) {
            $query->where('employee_profile_id', $employeeId);
        }

        if (!empty($payrollPeriod)) {
            $query->where('payroll_period', $payrollPeriod);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = Payroll::count();
        $filteredRecords = $query->count();

        $payrolls = $query->orderBy('payroll_period', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $payrolls->map(function ($payroll) {
            $employee = $payroll->employeeProfile;
            $user = $employee->user;
            
            $statusBadge = match($payroll->status) {
                'draft' => '<span class="badge bg-secondary">Draft</span>',
                'processed' => '<span class="badge bg-info">Processed</span>',
                'approved' => '<span class="badge bg-primary">Approved</span>',
                'paid' => '<span class="badge bg-success">Paid</span>',
                'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($payroll->status) . '</span>'
            };

            return [
                'id' => $payroll->id,
                'employee_code' => $employee->employee_code ?? 'N/A',
                'employee_name' => $user->name ?? 'N/A',
                'payroll_period' => $payroll->payroll_period,
                'period_range' => Carbon::parse($payroll->period_start_date)->format('d M') . ' - ' . Carbon::parse($payroll->period_end_date)->format('d M Y'),
                'present_days' => $payroll->present_days,
                'absent_days' => $payroll->absent_days,
                'leave_days' => $payroll->leave_days,
                'gross_salary' => MyHelper::formatCurrency($payroll->gross_salary),
                'net_salary' => MyHelper::formatCurrency($payroll->net_salary),
                'status' => $statusBadge,
                'status_value' => $payroll->status,
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data->values()->toArray()
        ]);
    }

    /**
     * Show the form for creating payroll
     */
    public function create()
    {
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        return view('employee-management.payroll.create', compact('employees'));
    }

    /**
     * Process payroll for selected employees
     */
    public function processPayroll(Request $request)
    {
        $validated = $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employee_profile,id',
            'payroll_period' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'period_start_date' => 'required|date',
            'period_end_date' => 'required|date|after:period_start_date',
        ]);

        try {
            DB::beginTransaction();
            
            $processedCount = 0;
            $errors = [];

            foreach ($validated['employee_ids'] as $employeeId) {
                try {
                    $employee = EmployeeProfile::with('user')->findOrFail($employeeId);
                    
                    // Check if payroll already exists for this period
                    $existingPayroll = Payroll::where('employee_profile_id', $employeeId)
                        ->where('payroll_period', $validated['payroll_period'])
                        ->first();

                    if ($existingPayroll) {
                        $errors[] = "Payroll already exists for {$employee->user->name} ({$validated['payroll_period']})";
                        continue;
                    }

                    // Get active salary structure
                    $salaryStructure = EmployeeSalaryStructure::where('employee_profile_id', $employeeId)
                        ->where('status', true)
                        ->where('effective_date', '<=', $validated['period_end_date'])
                        ->orderBy('effective_date', 'desc')
                        ->first();

                    if (!$salaryStructure) {
                        $errors[] = "No active salary structure found for {$employee->user->name}";
                        continue;
                    }

                    // Calculate attendance
                    $attendanceData = $this->calculateAttendance(
                        $employeeId,
                        $validated['period_start_date'],
                        $validated['period_end_date']
                    );

                    // Calculate payroll
                    $payrollData = $this->calculatePayroll(
                        $salaryStructure,
                        $attendanceData,
                        $validated['period_start_date'],
                        $validated['period_end_date']
                    );

                    // Create payroll
                    $payroll = Payroll::create([
                        'employee_profile_id' => $employeeId,
                        'salary_structure_id' => $salaryStructure->id,
                        'payroll_period' => $validated['payroll_period'],
                        'period_start_date' => $validated['period_start_date'],
                        'period_end_date' => $validated['period_end_date'],
                        'working_days' => $attendanceData['working_days'],
                        'present_days' => $attendanceData['present_days'],
                        'absent_days' => $attendanceData['absent_days'],
                        'leave_days' => $attendanceData['leave_days'],
                        'holiday_days' => $attendanceData['holiday_days'],
                        'weekend_days' => $attendanceData['weekend_days'],
                        'basic_salary' => $payrollData['basic_salary'],
                        'gross_salary' => $payrollData['gross_salary'],
                        'net_salary' => $payrollData['net_salary'],
                        'total_allowances' => $payrollData['total_allowances'],
                        'total_deductions' => $payrollData['total_deductions'],
                        'attendance_bonus' => $payrollData['attendance_bonus'],
                        'overtime_amount' => $payrollData['overtime_amount'],
                        'advance_deduction' => $payrollData['advance_deduction'],
                        'other_adjustments' => $payrollData['other_adjustments'],
                        'status' => 'draft',
                        'processed_by' => auth()->id(),
                        'processed_at' => Carbon::now(),
                    ]);

                    // Create payroll items
                    $this->createPayrollItems($payroll, $salaryStructure, $payrollData);

                    $processedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Error processing payroll for employee ID {$employeeId}: " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "Payroll processed successfully for {$processedCount} employee(s).";
            if (!empty($errors)) {
                $message .= " Errors: " . implode(', ', $errors);
            }

            return redirect()->route('employee-management.payroll')
                ->with('success', $message)
                ->with('errors', $errors);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to process payroll: ' . $e->getMessage());
        }
    }

    /**
     * Calculate attendance for a period
     */
    private function calculateAttendance($employeeId, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        $workingDays = 0;
        $presentDays = 0;
        $absentDays = 0;
        $leaveDays = 0;
        $holidayDays = 0;
        $weekendDays = 0;

        // Get holidays in the period
        $holidays = EmployeeHoliday::where('is_active', true)
            ->whereBetween('holiday_date', [$startDate, $endDate])
            ->pluck('holiday_date')
            ->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        // Get approved leaves in the period
        $leaves = EmployeeLeave::where('employee_profile_id', $employeeId)
            ->where('status', 'approved')
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('from_date', [$startDate, $endDate])
                  ->orWhereBetween('to_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('from_date', '<=', $startDate)
                        ->where('to_date', '>=', $endDate);
                  });
            })
            ->get();

        $leaveDates = [];
        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->from_date);
            $leaveEnd = Carbon::parse($leave->to_date);
            $current = $leaveStart->copy();
            while ($current->lte($leaveEnd) && $current->lte($end)) {
                if ($current->gte($start)) {
                    $leaveDates[] = $current->format('Y-m-d');
                }
                $current->addDay();
            }
        }

        // Count days
        $current = $start->copy();
        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $dayOfWeek = $current->dayOfWeek; // 0 = Sunday, 6 = Saturday

            if (in_array($dateStr, $holidays)) {
                $holidayDays++;
            } elseif ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $weekendDays++;
            } elseif (in_array($dateStr, $leaveDates)) {
                $leaveDays++;
                $workingDays++;
            } else {
                $workingDays++;
                
                // Check attendance
                $attendance = EmployeeAttendance::where('employee_profile_id', $employeeId)
                    ->where('attendance_date', $dateStr)
                    ->first();

                if ($attendance) {
                    if ($attendance->status == 'present' || $attendance->status == 'half_day') {
                        $presentDays++;
                    } else {
                        $absentDays++;
                    }
                } else {
                    $absentDays++;
                }
            }

            $current->addDay();
        }

        return [
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'holiday_days' => $holidayDays,
            'weekend_days' => $weekendDays,
        ];
    }

    /**
     * Calculate payroll amounts
     */
    private function calculatePayroll($salaryStructure, $attendanceData, $startDate, $endDate)
    {
        $workingDays = $attendanceData['working_days'];
        $presentDays = $attendanceData['present_days'];
        
        // Calculate daily salary
        $monthlyBasic = $salaryStructure->basic_salary;
        $dailyBasic = $monthlyBasic / $workingDays;
        
        // Calculate pro-rated salary based on attendance
        $attendanceRatio = $workingDays > 0 ? ($presentDays / $workingDays) : 0;
        $basicSalary = $monthlyBasic * $attendanceRatio;
        
        // Calculate allowances (pro-rated)
        $hra = ($salaryStructure->hra ?? 0) * $attendanceRatio;
        $da = ($salaryStructure->da ?? 0) * $attendanceRatio;
        $ta = ($salaryStructure->ta ?? 0) * $attendanceRatio;
        $medicalAllowance = ($salaryStructure->medical_allowance ?? 0) * $attendanceRatio;
        $otherAllowances = ($salaryStructure->other_allowances ?? 0) * $attendanceRatio;
        
        $totalAllowances = $hra + $da + $ta + $medicalAllowance + $otherAllowances;
        $grossSalary = $basicSalary + $totalAllowances;
        
        // Calculate deductions (fixed, not pro-rated)
        $pf = $salaryStructure->pf ?? 0;
        $esi = $salaryStructure->esi ?? 0;
        $tds = $salaryStructure->tds ?? 0;
        $otherDeductions = $salaryStructure->other_deductions ?? 0;
        
        $totalDeductions = $pf + $esi + $tds + $otherDeductions;
        
        // Attendance bonus (if 100% attendance)
        $attendanceBonus = ($attendanceRatio >= 1.0 && $presentDays == $workingDays) ? 500 : 0;
        
        // Overtime and other adjustments (can be added later)
        $overtimeAmount = 0;
        $advanceDeduction = 0;
        $otherAdjustments = 0;
        
        $netSalary = $grossSalary - $totalDeductions + $attendanceBonus + $overtimeAmount - $advanceDeduction + $otherAdjustments;

        return [
            'basic_salary' => round($basicSalary, 2),
            'gross_salary' => round($grossSalary, 2),
            'net_salary' => round($netSalary, 2),
            'total_allowances' => round($totalAllowances, 2),
            'total_deductions' => round($totalDeductions, 2),
            'attendance_bonus' => $attendanceBonus,
            'overtime_amount' => $overtimeAmount,
            'advance_deduction' => $advanceDeduction,
            'other_adjustments' => $otherAdjustments,
            'hra' => round($hra, 2),
            'da' => round($da, 2),
            'ta' => round($ta, 2),
            'medical_allowance' => round($medicalAllowance, 2),
            'other_allowances' => round($otherAllowances, 2),
            'pf' => $pf,
            'esi' => $esi,
            'tds' => $tds,
            'other_deductions' => $otherDeductions,
        ];
    }

    /**
     * Create payroll items
     */
    private function createPayrollItems($payroll, $salaryStructure, $payrollData)
    {
        $items = [];
        $sortOrder = 1;

        // Allowances
        if ($payrollData['hra'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'HRA',
                'amount' => $payrollData['hra'],
                'description' => 'House Rent Allowance',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['da'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'DA',
                'amount' => $payrollData['da'],
                'description' => 'Dearness Allowance',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['ta'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'TA',
                'amount' => $payrollData['ta'],
                'description' => 'Travel Allowance',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['medical_allowance'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'Medical Allowance',
                'amount' => $payrollData['medical_allowance'],
                'description' => 'Medical Allowance',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['other_allowances'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'Other Allowances',
                'amount' => $payrollData['other_allowances'],
                'description' => 'Other Allowances',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['attendance_bonus'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'allowance',
                'item_name' => 'Attendance Bonus',
                'amount' => $payrollData['attendance_bonus'],
                'description' => 'Perfect Attendance Bonus',
                'sort_order' => $sortOrder++,
            ];
        }

        // Deductions
        if ($payrollData['pf'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'deduction',
                'item_name' => 'PF',
                'amount' => $payrollData['pf'],
                'description' => 'Provident Fund',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['esi'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'deduction',
                'item_name' => 'ESI',
                'amount' => $payrollData['esi'],
                'description' => 'Employee State Insurance',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['tds'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'deduction',
                'item_name' => 'TDS',
                'amount' => $payrollData['tds'],
                'description' => 'Tax Deducted at Source',
                'sort_order' => $sortOrder++,
            ];
        }
        if ($payrollData['other_deductions'] > 0) {
            $items[] = [
                'payroll_id' => $payroll->id,
                'item_type' => 'deduction',
                'item_name' => 'Other Deductions',
                'amount' => $payrollData['other_deductions'],
                'description' => 'Other Deductions',
                'sort_order' => $sortOrder++,
            ];
        }

        if (!empty($items)) {
            PayrollItem::insert($items);
        }
    }

    /**
     * View payroll details
     */
    public function show($id)
    {
        $payroll = Payroll::with(['employeeProfile.user', 'salaryStructure', 'items', 'processedBy', 'approvedBy'])
            ->findOrFail($id);
        
        return view('employee-management.payroll.show', compact('payroll'));
    }

    /**
     * Update payroll status
     */
    public function updateStatus(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:draft,processed,approved,paid,cancelled',
            'payment_date' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        try {
            $updateData = [
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
            ];

            if ($validated['status'] == 'approved' && $payroll->status != 'approved') {
                $updateData['approved_by'] = auth()->id();
                $updateData['approved_at'] = Carbon::now();
            }

            if ($validated['status'] == 'paid' && !empty($validated['payment_date'])) {
                $updateData['payment_date'] = $validated['payment_date'];
            }

            $payroll->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Payroll status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payroll status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete payroll
     */
    public function destroy($id)
    {
        try {
            $payroll = Payroll::findOrFail($id);
            
            if ($payroll->status == 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete paid payroll.',
                ], 400);
            }

            $payroll->delete();

            return response()->json([
                'success' => true,
                'message' => 'Payroll deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payroll: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and download payslip PDF
     */
  public function downloadPayslip($id)
{
    try {
        $payroll = Payroll::with([
            'employeeProfile.user', 
            'employeeProfile.department', 
            'employeeProfile.designation', 
            'salaryStructure', 
            'items', 
            'processedBy', 
            'approvedBy'
        ])->findOrFail($id);

        // Fetch settings (header & template image)
        $settings = AppSetting::first();

        $data = [
            'payroll' => $payroll,
            'settings' => $settings,
        ];

        $pdf = Pdf::loadView('employee-management.payroll.payslip-pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $fileName = 'Payslip_' . $payroll->employeeProfile->employee_code . '_' . $payroll->payroll_period . '.pdf';

        return $pdf->download($fileName);

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to generate payslip: ' . $e->getMessage());
    }
}

    /**
     * View payslip PDF in browser
     */
    public function viewPayslip($id)
{
    try {
        $payroll = Payroll::with([
            'employeeProfile.user',
            'employeeProfile.department',
            'employeeProfile.designation',
            'salaryStructure',
            'items',
            'processedBy',
            'approvedBy'
        ])->findOrFail($id);

        $settings = AppSetting::first();

        $data = [
            'payroll'  => $payroll,
            'settings' => $settings,
        ];

        // NEW: If the user wants to view it as a web page (Preview)
        if (request('view_type') === 'html') {
            return view('employee-management.payroll.payslip-pdf', $data);
        }

        // Default: Generate PDF
        $pdf = Pdf::loadView('employee-management.payroll.payslip-pdf', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->stream(
            'Payslip_' . $payroll->employeeProfile->employee_code . '_' . $payroll->payroll_period . '.pdf'
        );

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to generate payslip: ' . $e->getMessage());
    }
}

}