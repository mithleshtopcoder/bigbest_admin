<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShiftController extends Controller
{
    /**
     * Display shift management page
     */
    public function index()
    {
        return view('employee-management.shift.index');
    }

    /**
     * Get shifts for DataTables
     */
    public function getShifts(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $isActive = $request->input('is_active');

        $query = Shift::with('createdBy');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($isActive !== null && $isActive !== '') {
            $query->where('is_active', $isActive);
        }

        $totalRecords = Shift::count();
        $filteredRecords = $query->count();

        $shifts = $query->orderBy('start_time', 'asc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $shifts->map(function ($shift) {
            // Parse time strings (format: H:i:s)
            $startTime = Carbon::createFromFormat('H:i:s', $shift->start_time)->format('h:i A');
            $endTime = Carbon::createFromFormat('H:i:s', $shift->end_time)->format('h:i A');
            
            // Calculate duration
            $start = Carbon::createFromFormat('H:i:s', $shift->start_time);
            $end = Carbon::createFromFormat('H:i:s', $shift->end_time);
            if ($end->lessThan($start)) {
                $end->addDay(); // Handle overnight shifts
            }
            $duration = $start->diffInHours($end);
            
            // Format working days
            $workingDays = $shift->working_days ?? [];
            $dayNames = [
                'monday' => 'Mon',
                'tuesday' => 'Tue',
                'wednesday' => 'Wed',
                'thursday' => 'Thu',
                'friday' => 'Fri',
                'saturday' => 'Sat',
                'sunday' => 'Sun'
            ];
            $workingDaysDisplay = !empty($workingDays) 
                ? implode(', ', array_map(function($day) use ($dayNames) {
                    return $dayNames[$day] ?? ucfirst($day);
                }, $workingDays))
                : 'N/A';
            
            return [
                'id' => $shift->id,
                'name' => $shift->name,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $duration . ' hours',
                'break_duration' => $shift->break_duration ? $shift->break_duration . ' mins' : '-',
                'working_days' => $workingDaysDisplay,
                'description' => \Str::limit($shift->description ?? 'N/A', 50),
                'is_active' => $shift->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>',
                'is_active_value' => $shift->is_active,
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
     * Show the form for creating shift
     */
    public function create()
    {
        return view('employee-management.shift.create');
    }

    /**
     * Store shift
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            Shift::create([
                'name' => $validated['name'],
                'start_time' => $validated['start_time'] . ':00', // Add seconds
                'end_time' => $validated['end_time'] . ':00', // Add seconds
                'break_duration' => $validated['break_duration'] ?? 0,
                'working_days' => $validated['working_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.shift')
                ->with('success', 'Shift created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create shift: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing shift
     */
    public function edit($id)
    {
        $shift = Shift::findOrFail($id);
        return view('employee-management.shift.edit', compact('shift'));
    }

    /**
     * Update shift
     */
    public function update(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'break_duration' => 'nullable|integer|min:0',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $shift->update([
                'name' => $validated['name'],
                'start_time' => $validated['start_time'] . ':00', // Add seconds
                'end_time' => $validated['end_time'] . ':00', // Add seconds
                'break_duration' => $validated['break_duration'] ?? 0,
                'working_days' => $validated['working_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()->route('employee-management.shift')
                ->with('success', 'Shift updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update shift: ' . $e->getMessage());
        }
    }

    /**
     * Update shift status
     */
    public function updateStatus(Request $request, $id)
    {
        $shift = Shift::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $shift->update([
                'is_active' => $validated['is_active'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Shift status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update shift status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove shift
     */
    public function destroy($id)
    {
        try {
            $shift = Shift::findOrFail($id);
            $shift->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shift deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shift: ' . $e->getMessage(),
            ], 500);
        }
    }
}
