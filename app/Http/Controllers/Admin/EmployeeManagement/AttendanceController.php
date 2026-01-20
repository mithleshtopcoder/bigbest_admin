<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display attendance management page
     */
    public function index()
    {
        return view('employee-management.attendance.index');
    }

    /**
     * Get attendances for DataTables
     */
    public function getAttendances(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $employeeId = $request->input('employee_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $status = $request->input('status');

        $query = EmployeeAttendance::with(['employeeProfile.user', 'markedBy']);

        if (!empty($search)) {
            $query->whereHas('employeeProfile.user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('mobile_number', 'like', '%' . $search . '%');
            });
        }

        if (!empty($employeeId)) {
            $query->where('employee_profile_id', $employeeId);
        }

        if (!empty($dateFrom)) {
            $query->where('attendance_date', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->where('attendance_date', '<=', $dateTo);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $totalRecords = EmployeeAttendance::count();
        $filteredRecords = $query->count();

        $attendances = $query->orderBy('attendance_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $attendances->map(function ($attendance) {
            $employee = $attendance->employeeProfile;
            $user = $employee->user;
            
            $statusBadge = match($attendance->status) {
                'present' => '<span class="badge bg-success">Present</span>',
                'absent' => '<span class="badge bg-danger">Absent</span>',
                'half_day' => '<span class="badge bg-warning">Half Day</span>',
                'leave' => '<span class="badge bg-info">Leave</span>',
                'holiday' => '<span class="badge bg-secondary">Holiday</span>',
                'weekend' => '<span class="badge bg-secondary">Weekend</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($attendance->status) . '</span>'
            };

            return [
                'id' => $attendance->id,
                'employee_code' => $employee->employee_code ?? 'N/A',
                'employee_name' => $user->name ?? 'N/A',
                'attendance_date' => $attendance->attendance_date ? \Carbon\Carbon::parse($attendance->attendance_date)->format('d M Y') : 'N/A',
                'punch_in' => $attendance->punch_in ? Carbon::parse($attendance->punch_in)->format('h:i A') : '-',
                'punch_out' => $attendance->punch_out ? Carbon::parse($attendance->punch_out)->format('h:i A') : '-',
                'total_hours' => $attendance->total_hours ? number_format($attendance->total_hours, 2) . ' hrs' : '-',
                'status' => $statusBadge,
                'status_value' => $attendance->status,
                'attendance_type' => $attendance->attendance_type,
                'remarks' => $attendance->remarks ?? '-',
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
     * Show the form for creating manual attendance
     */
    public function create()
    {
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        return view('employee-management.attendance.create', compact('employees'));
    }

    /**
     * Store manual attendance
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'attendance_date' => 'required|date',
            'punch_in' => 'nullable|date_format:H:i',
            'punch_out' => 'nullable|date_format:H:i|after:punch_in',
            'status' => 'required|in:present,absent,half_day,leave,holiday,weekend',
            'remarks' => 'nullable|string',
        ]);

        try {
            // Check if attendance already exists for this date
            $existing = EmployeeAttendance::where('employee_profile_id', $validated['employee_profile_id'])
                ->where('attendance_date', $validated['attendance_date'])
                ->first();

            if ($existing) {
                return back()->withInput()->with('error', 'Attendance already exists for this date. Please edit instead.');
            }

            // Calculate total hours if both punch in and out are provided
            $totalHours = null;
            if (!empty($validated['punch_in']) && !empty($validated['punch_out'])) {
                $punchIn = Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_in']);
                $punchOut = Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_out']);
                $totalHours = $punchIn->diffInHours($punchOut, true);
            }

            $attendance = EmployeeAttendance::create([
                'employee_profile_id' => $validated['employee_profile_id'],
                'attendance_date' => $validated['attendance_date'],
                'punch_in' => $validated['punch_in'] ? Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_in']) : null,
                'punch_out' => $validated['punch_out'] ? Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_out']) : null,
                'total_hours' => $totalHours,
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
                'attendance_type' => 'manual',
                'marked_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.attendance')
                ->with('success', 'Attendance created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create attendance: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing attendance
     */
    public function edit($id)
    {
        $attendance = EmployeeAttendance::with(['employeeProfile.user'])->findOrFail($id);
        $employees = EmployeeProfile::with('user')
            ->whereHas('user', function($q) {
                $q->where('status', 1);
            })
            ->get();
        
        return view('employee-management.attendance.edit', compact('attendance', 'employees'));
    }

    /**
     * Update attendance
     */
    public function update(Request $request, $id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);

        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'attendance_date' => 'required|date',
            'punch_in' => 'nullable|date_format:H:i',
            'punch_out' => 'nullable|date_format:H:i|after:punch_in',
            'status' => 'required|in:present,absent,half_day,leave,holiday,weekend',
            'remarks' => 'nullable|string',
        ]);

        try {
            // Check if attendance already exists for this date (excluding current record)
            $existing = EmployeeAttendance::where('employee_profile_id', $validated['employee_profile_id'])
                ->where('attendance_date', $validated['attendance_date'])
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return back()->withInput()->with('error', 'Attendance already exists for this date.');
            }

            // Calculate total hours if both punch in and out are provided
            $totalHours = null;
            if (!empty($validated['punch_in']) && !empty($validated['punch_out'])) {
                $punchIn = Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_in']);
                $punchOut = Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_out']);
                $totalHours = $punchIn->diffInHours($punchOut, true);
            }

            $attendance->update([
                'employee_profile_id' => $validated['employee_profile_id'],
                'attendance_date' => $validated['attendance_date'],
                'punch_in' => $validated['punch_in'] ? Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_in']) : null,
                'punch_out' => $validated['punch_out'] ? Carbon::parse($validated['attendance_date'] . ' ' . $validated['punch_out']) : null,
                'total_hours' => $totalHours,
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
                'marked_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.attendance')
                ->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove attendance
     */
    public function destroy($id)
    {
        try {
            $attendance = EmployeeAttendance::findOrFail($id);
            $attendance->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attendance deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attendance: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Punch In
     */
    public function punchIn(Request $request)
    {
        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'attendance_date' => 'nullable|date',
        ]);

        try {
            $attendanceDate = $validated['attendance_date'] ?? Carbon::today()->toDateString();
            $currentTime = Carbon::now();

            // Check if already punched in for today
            $existing = EmployeeAttendance::where('employee_profile_id', $validated['employee_profile_id'])
                ->where('attendance_date', $attendanceDate)
                ->whereNotNull('punch_in')
                ->whereNull('punch_out')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already punched in for this date. Please punch out first.',
                ], 400);
            }

            // Check if attendance record exists
            $attendance = EmployeeAttendance::where('employee_profile_id', $validated['employee_profile_id'])
                ->where('attendance_date', $attendanceDate)
                ->first();

            if ($attendance) {
                $attendance->update([
                    'punch_in' => $currentTime,
                    'status' => 'present',
                    'attendance_type' => 'auto',
                ]);
            } else {
                $attendance = EmployeeAttendance::create([
                    'employee_profile_id' => $validated['employee_profile_id'],
                    'attendance_date' => $attendanceDate,
                    'punch_in' => $currentTime,
                    'status' => 'present',
                    'attendance_type' => 'auto',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Punched in successfully.',
                'data' => [
                    'punch_in' => $currentTime->format('h:i A'),
                    'date' => $attendanceDate,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to punch in: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Punch Out
     */
    public function punchOut(Request $request)
    {
        $validated = $request->validate([
            'employee_profile_id' => 'required|exists:employee_profile,id',
            'attendance_date' => 'nullable|date',
        ]);

        try {
            $attendanceDate = $validated['attendance_date'] ?? Carbon::today()->toDateString();
            $currentTime = Carbon::now();

            // Find attendance record
            $attendance = EmployeeAttendance::where('employee_profile_id', $validated['employee_profile_id'])
                ->where('attendance_date', $attendanceDate)
                ->whereNotNull('punch_in')
                ->whereNull('punch_out')
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'No punch in found for this date. Please punch in first.',
                ], 400);
            }

            // Calculate total hours
            $punchIn = Carbon::parse($attendance->punch_in);
            $totalHours = $punchIn->diffInHours($currentTime, true);

            $attendance->update([
                'punch_out' => $currentTime,
                'total_hours' => $totalHours,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Punched out successfully.',
                'data' => [
                    'punch_out' => $currentTime->format('h:i A'),
                    'total_hours' => number_format($totalHours, 2) . ' hrs',
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to punch out: ' . $e->getMessage(),
            ], 500);
        }
    }
}
