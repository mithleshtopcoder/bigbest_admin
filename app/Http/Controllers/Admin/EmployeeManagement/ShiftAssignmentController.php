<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\ShiftAssignment;
use App\Models\Shift;
use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShiftAssignmentController extends Controller
{
    /**
     * Display shift assignment management page
     */
    public function index()
    {
        return view('employee-management.shift-assignment.index');
    }

    /**
     * Get shift assignments for DataTables
     */
    public function getShiftAssignments(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $employeeId = $request->input('employee_id');
        $shiftId = $request->input('shift_id');
        $isActive = $request->input('is_active');

        $query = ShiftAssignment::with(['employeeProfile.user', 'shift', 'assignedBy']);

        if (!empty($search)) {
            $query->whereHas('employeeProfile.user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $search . '%');
            })->orWhereHas('shift', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if (!empty($employeeId)) {
            $query->where('employee_profile_id', $employeeId);
        }

        if (!empty($shiftId)) {
            $query->where('shift_id', $shiftId);
        }

        if ($isActive !== null && $isActive !== '') {
            $query->where('is_active', $isActive);
        }

        $totalRecords = ShiftAssignment::count();
        $filteredRecords = $query->count();

        $assignments = $query->orderBy('effective_from', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $assignments->map(function ($assignment) {
            $employee = $assignment->employeeProfile;
            $user = $employee->user;
            $shift = $assignment->shift;
            
            $statusBadge = $assignment->is_active 
                ? '<span class="badge bg-success">Active</span>' 
                : '<span class="badge bg-danger">Inactive</span>';

            // Check if assignment is currently active
            $today = Carbon::today();
            $effectiveFrom = Carbon::parse($assignment->effective_from);
            $effectiveTo = $assignment->effective_to ? Carbon::parse($assignment->effective_to) : null;
            
            $isCurrentlyActive = $assignment->is_active 
                && $today->greaterThanOrEqualTo($effectiveFrom)
                && ($effectiveTo === null || $today->lessThanOrEqualTo($effectiveTo));

            return [
                'id' => $assignment->id,
                'employee_code' => $employee->employee_code ?? 'N/A',
                'employee_name' => $user->name ?? 'N/A',
                'shift_name' => $shift->name ?? 'N/A',
                'shift_timing' => $shift ? Carbon::createFromFormat('H:i:s', $shift->start_time)->format('h:i A') . ' - ' . Carbon::createFromFormat('H:i:s', $shift->end_time)->format('h:i A') : 'N/A',
                'effective_from' => $assignment->effective_from ? Carbon::parse($assignment->effective_from)->format('d M Y') : 'N/A',
                'effective_to' => $assignment->effective_to ? Carbon::parse($assignment->effective_to)->format('d M Y') : 'Ongoing',
                'is_active' => $statusBadge,
                'is_active_value' => $assignment->is_active,
                'is_currently_active' => $isCurrentlyActive,
                'remarks' => \Str::limit($assignment->remarks ?? 'N/A', 30),
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
     * Show the form for creating shift assignment
     */
    public function create()
    {
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        $shifts = Shift::where('is_active', true)->orderBy('start_time')->get();
        
        return view('employee-management.shift-assignment.create', compact('employees', 'shifts'));
    }

    /**
     * Store shift assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'shift_id' => 'required|exists:shifts,id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'is_active' => 'boolean',
            'remarks' => 'nullable|string',
        ]);

        try {
            ShiftAssignment::create([
                'employee_profile_id' => $validated['employee_profile_id'],
                'shift_id' => $validated['shift_id'],
                'effective_from' => $validated['effective_from'],
                'effective_to' => $validated['effective_to'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'remarks' => $validated['remarks'] ?? null,
                'assigned_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.shift-assignment')
                ->with('success', 'Shift assigned successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to assign shift: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing shift assignment
     */
    public function edit($id)
    {
        $assignment = ShiftAssignment::with(['employeeProfile.user', 'shift'])->findOrFail($id);
        
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        $shifts = Shift::where('is_active', true)->orderBy('start_time')->get();
        
        return view('employee-management.shift-assignment.edit', compact('assignment', 'employees', 'shifts'));
    }

    /**
     * Update shift assignment
     */
    public function update(Request $request, $id)
    {
        $assignment = ShiftAssignment::findOrFail($id);

        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'shift_id' => 'required|exists:shifts,id',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'is_active' => 'boolean',
            'remarks' => 'nullable|string',
        ]);

        try {
            $assignment->update([
                'employee_profile_id' => $validated['employee_profile_id'],
                'shift_id' => $validated['shift_id'],
                'effective_from' => $validated['effective_from'],
                'effective_to' => $validated['effective_to'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            return redirect()->route('employee-management.shift-assignment')
                ->with('success', 'Shift assignment updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update shift assignment: ' . $e->getMessage());
        }
    }

    /**
     * Update shift assignment status
     */
    public function updateStatus(Request $request, $id)
    {
        $assignment = ShiftAssignment::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $assignment->update([
                'is_active' => $validated['is_active'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift assignment status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shift assignment status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove shift assignment
     */
    public function destroy($id)
    {
        try {
            $assignment = ShiftAssignment::findOrFail($id);
            $assignment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shift assignment deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shift assignment: ' . $e->getMessage(),
            ], 500);
        }
    }
}
