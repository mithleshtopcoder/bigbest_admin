<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display leave management page
     */
    public function index()
    {
        return view('employee-management.leave.index');
    }

    /**
     * Get leaves for DataTables
     */
    public function getLeaves(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $employeeId = $request->input('employee_id');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $query = EmployeeLeave::with(['employeeProfile.user', 'approvedBy', 'appliedBy']);

        if (!empty($search)) {
            $query->whereHas('employeeProfile.user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $search . '%');
            })->orWhere('leave_type', 'like', '%' . $search . '%')
              ->orWhere('reason', 'like', '%' . $search . '%');
        }

        if (!empty($employeeId)) {
            $query->where('employee_profile_id', $employeeId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($dateFrom)) {
            $query->where('from_date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->where('to_date', '<=', $dateTo);
        }

        $totalRecords = EmployeeLeave::count();
        $filteredRecords = $query->count();

        $leaves = $query->orderBy('from_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $leaves->map(function ($leave) {
            $employee = $leave->employeeProfile;
            $user = $employee->user;
            
            $statusBadge = match($leave->status) {
                'pending' => '<span class="badge bg-warning">Pending</span>',
                'approved' => '<span class="badge bg-success">Approved</span>',
                'rejected' => '<span class="badge bg-danger">Rejected</span>',
                'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($leave->status) . '</span>'
            };

            return [
                'id' => $leave->id,
                'employee_code' => $employee->employee_code ?? 'N/A',
                'employee_name' => $user->name ?? 'N/A',
                'leave_type' => $leave->leave_type ?? 'N/A',
                'from_date' => $leave->from_date ? \Carbon\Carbon::parse($leave->from_date)->format('d M Y') : 'N/A',
                'to_date' => $leave->to_date ? \Carbon\Carbon::parse($leave->to_date)->format('d M Y') : 'N/A',
                'total_days' => $leave->total_days,
                'reason' => \Str::limit($leave->reason ?? 'N/A', 50),
                'status' => $statusBadge,
                'status_value' => $leave->status,
                'approved_by' => $leave->approvedBy ? $leave->approvedBy->name : '-',
                'approved_at' => $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('d M Y h:i A') : '-',
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
     * Show the form for creating leave
     */
    public function create()
    {
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        return view('employee-management.leave.create', compact('employees'));
    }

    /**
     * Store leave
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'leave_type' => 'nullable|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'nullable|string',
            'status' => 'nullable|in:pending,approved,rejected,cancelled',
        ]);

        try {
            // Calculate total days
            $fromDate = Carbon::parse($validated['from_date']);
            $toDate = Carbon::parse($validated['to_date']);
            $totalDays = $fromDate->diffInDays($toDate) + 1;

            $leave = EmployeeLeave::create([
                'employee_profile_id' => $validated['employee_profile_id'],
                'leave_type' => $validated['leave_type'] ?? null,
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
                'total_days' => $totalDays,
                'reason' => $validated['reason'] ?? null,
                'status' => $validated['status'] ?? 'pending',
                'applied_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.leave')
                ->with('success', 'Leave created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create leave: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing leave
     */
    public function edit($id)
    {
        $leave = EmployeeLeave::with(['employeeProfile.user'])->findOrFail($id);
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        return view('employee-management.leave.edit', compact('leave', 'employees'));
    }

    /**
     * Update leave
     */
    public function update(Request $request, $id)
    {
        $leave = EmployeeLeave::findOrFail($id);

        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'leave_type' => 'nullable|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'approval_remarks' => 'nullable|string',
        ]);

        try {
            // Calculate total days
            $fromDate = Carbon::parse($validated['from_date']);
            $toDate = Carbon::parse($validated['to_date']);
            $totalDays = $fromDate->diffInDays($toDate) + 1;

            $updateData = [
                'employee_profile_id' => $validated['employee_profile_id'],
                'leave_type' => $validated['leave_type'] ?? null,
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
                'total_days' => $totalDays,
                'reason' => $validated['reason'] ?? null,
                'status' => $validated['status'],
                'approval_remarks' => $validated['approval_remarks'] ?? null,
            ];

            // If status is being changed to approved/rejected, set approved_by and approved_at
            if (in_array($validated['status'], ['approved', 'rejected']) && $leave->status !== $validated['status']) {
                $updateData['approved_by'] = auth()->id();
                $updateData['approved_at'] = Carbon::now();
            }

            $leave->update($updateData);

            return redirect()->route('employee-management.leave')
                ->with('success', 'Leave updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update leave: ' . $e->getMessage());
        }
    }

    /**
     * Update leave status
     */
    public function updateStatus(Request $request, $id)
    {
        $leave = EmployeeLeave::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'approval_remarks' => 'nullable|string',
        ]);

        try {
            $updateData = [
                'status' => $validated['status'],
                'approval_remarks' => $validated['approval_remarks'] ?? null,
            ];

            // If status is being changed to approved/rejected, set approved_by and approved_at
            if (in_array($validated['status'], ['approved', 'rejected']) && $leave->status !== $validated['status']) {
                $updateData['approved_by'] = auth()->id();
                $updateData['approved_at'] = Carbon::now();
            }

            $leave->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Leave status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove leave
     */
    public function destroy($id)
    {
        try {
            $leave = EmployeeLeave::findOrFail($id);
            $leave->delete();

            return response()->json([
                'success' => true,
                'message' => 'Leave deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave: ' . $e->getMessage(),
            ], 500);
        }
    }
}
