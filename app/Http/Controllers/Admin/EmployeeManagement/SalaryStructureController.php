<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeeProfile;
use App\Models\EmployeeSalaryStructure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryStructureController extends Controller
{
    /**
     * Display employee list for salary structure management
     */
    public function employeeList()
    {
        return view('employee-management.salary-structure.employee-list');
    }

    /**
     * Get employees for DataTables
     */
    public function getEmployees(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');

        $query = EmployeeProfile::with(['user', 'department', 'designation', 'store']);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('employee_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->orWhere('mobile_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $totalRecords = EmployeeProfile::count();
        $filteredRecords = $query->count();

        $employees = $query->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $employees->map(function ($employee) {
            $user = $employee->user;
            return [
                'id' => $employee->id,
                'employee_code' => $employee->employee_code ?? 'N/A',
                'name' => $user->name ?? 'N/A',
                'email' => $user->email ?? '',
                'department' => $employee->department->name ?? 'N/A',
                'designation' => $employee->designation->name ?? 'N/A',
                'store' => $employee->store->name ?? 'N/A',
                'phone' => $user->mobile_number ?? 'N/A',
                'uuid' => $user->uuid ?? $user->id ?? '',
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
     * Display salary structures for a specific employee
     */
    public function index($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::with(['user', 'salaryStructures'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('employee-management.salary-structure.index', compact('employee'));
    }

    /**
     * Get salary structures for DataTables
     */
    public function getSalaryStructures(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $status = $request->input('status');

        $query = EmployeeSalaryStructure::where('employee_profile_id', $employee->id);

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('effective_date', 'like', '%' . $search . '%');
            });
        }

        if (!empty($status)) {
            $query->where('status', $status === 'active' ? 1 : 0);
        }

        $totalRecords = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)->count();
        $filteredRecords = $query->count();

        $structures = $query->orderBy('effective_date', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $structures->map(function ($structure) {
            $statusBadge = $structure->status 
                ? '<span class="badge bg-success">Active</span>' 
                : '<span class="badge bg-danger">Inactive</span>';

            return [
                'id' => $structure->id,
                'basic_salary' => number_format($structure->basic_salary, 2),
                'gross_salary' => number_format($structure->gross_salary, 2),
                'net_salary' => number_format($structure->net_salary, 2),
                'effective_date' => $structure->effective_date ? \Carbon\Carbon::parse($structure->effective_date)->format('d M Y') : 'N/A',
                'status' => $statusBadge,
                'status_value' => $structure->status ? 'active' : 'inactive',
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
     * Show the form for creating a new salary structure
     */
    public function create($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('employee-management.salary-structure.create', compact('employee'));
    }

    /**
     * Store a newly created salary structure
     */
    public function storeSalaryStructure(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'ta' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pf' => 'nullable|numeric|min:0',
            'esi' => 'nullable|numeric|min:0',
            'tds' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'effective_date' => 'nullable|date',
            'currency' => 'nullable|string|max:10',
            'status' => 'nullable|boolean',
        ]);

        try {
            // Calculate gross salary (basic + all allowances)
            $grossSalary = $validated['basic_salary'] 
                + ($validated['hra'] ?? 0)
                + ($validated['da'] ?? 0)
                + ($validated['ta'] ?? 0)
                + ($validated['medical_allowance'] ?? 0)
                + ($validated['other_allowances'] ?? 0);

            // Calculate net salary (gross - all deductions)
            $netSalary = $grossSalary
                - ($validated['pf'] ?? 0)
                - ($validated['esi'] ?? 0)
                - ($validated['tds'] ?? 0)
                - ($validated['other_deductions'] ?? 0);

            $salaryStructure = EmployeeSalaryStructure::create([
                'employee_profile_id' => $employee->id,
                'basic_salary' => $validated['basic_salary'],
                'hra' => $validated['hra'] ?? 0,
                'da' => $validated['da'] ?? 0,
                'ta' => $validated['ta'] ?? 0,
                'medical_allowance' => $validated['medical_allowance'] ?? 0,
                'other_allowances' => $validated['other_allowances'] ?? 0,
                'pf' => $validated['pf'] ?? 0,
                'esi' => $validated['esi'] ?? 0,
                'tds' => $validated['tds'] ?? 0,
                'other_deductions' => $validated['other_deductions'] ?? 0,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'effective_date' => $validated['effective_date'] ?? null,
                'currency' => $validated['currency'] ?? 'INR',
                'status' => $validated['status'] ?? true,
            ]);

            return redirect()->route('employee-management.salary-structure.index', $uuid)
                ->with('success', 'Salary structure created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create salary structure: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a salary structure
     */
    public function editSalaryStructure($uuid, $id)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::with('user')
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        $salaryStructure = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)
            ->findOrFail($id);

        return view('employee-management.salary-structure.edit', compact('employee', 'salaryStructure'));
    }

    /**
     * Update the specified salary structure
     */
    public function updateSalaryStructure(Request $request, $uuid, $id)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();
        
        $salaryStructure = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'da' => 'nullable|numeric|min:0',
            'ta' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'pf' => 'nullable|numeric|min:0',
            'esi' => 'nullable|numeric|min:0',
            'tds' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'effective_date' => 'nullable|date',
            'currency' => 'nullable|string|max:10',
            'status' => 'nullable|boolean',
        ]);

        try {
            // Calculate gross salary (basic + all allowances)
            $grossSalary = $validated['basic_salary'] 
                + ($validated['hra'] ?? 0)
                + ($validated['da'] ?? 0)
                + ($validated['ta'] ?? 0)
                + ($validated['medical_allowance'] ?? 0)
                + ($validated['other_allowances'] ?? 0);

            // Calculate net salary (gross - all deductions)
            $netSalary = $grossSalary
                - ($validated['pf'] ?? 0)
                - ($validated['esi'] ?? 0)
                - ($validated['tds'] ?? 0)
                - ($validated['other_deductions'] ?? 0);

            $salaryStructure->update([
                'basic_salary' => $validated['basic_salary'],
                'hra' => $validated['hra'] ?? 0,
                'da' => $validated['da'] ?? 0,
                'ta' => $validated['ta'] ?? 0,
                'medical_allowance' => $validated['medical_allowance'] ?? 0,
                'other_allowances' => $validated['other_allowances'] ?? 0,
                'pf' => $validated['pf'] ?? 0,
                'esi' => $validated['esi'] ?? 0,
                'tds' => $validated['tds'] ?? 0,
                'other_deductions' => $validated['other_deductions'] ?? 0,
                'gross_salary' => $grossSalary,
                'net_salary' => $netSalary,
                'effective_date' => $validated['effective_date'] ?? null,
                'currency' => $validated['currency'] ?? 'INR',
                'status' => $validated['status'] ?? true,
            ]);

            return redirect()->route('employee-management.salary-structure.index', $uuid)
                ->with('success', 'Salary structure updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update salary structure: ' . $e->getMessage());
        }
    }

    /**
     * Update salary structure status
     */
    public function updateStatus(Request $request, $uuid, $id)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();
        
        $salaryStructure = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)
            ->findOrFail($id);

        $salaryStructure->status = $request->status;
        $salaryStructure->save();

        return response()->json([
            'success' => true,
            'message' => 'Salary structure status updated successfully.'
        ]);
    }

    /**
     * Remove the specified salary structure
     */
    public function destroySalaryStructure($uuid, $id)
    {
        try {
            $user = User::where('uuid', $uuid)->firstOrFail();
            $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();
            
            $salaryStructure = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)
                ->findOrFail($id);
            
            $salaryStructure->delete();

            return response()->json([
                'success' => true,
                'message' => 'Salary structure deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete salary structure: ' . $e->getMessage(),
            ], 500);
        }
    }
}
