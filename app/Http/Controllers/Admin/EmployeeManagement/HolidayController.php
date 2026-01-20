<?php

namespace App\Http\Controllers\Admin\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\EmployeeHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display holiday management page
     */
    public function index()
    {
        return view('employee-management.holiday.index');
    }

    /**
     * Get holidays for DataTables
     */
    public function getHolidays(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 25);
        $search = $request->input('search.value', '');
        $type = $request->input('type');
        $year = $request->input('year');
        $isActive = $request->input('is_active');

        $query = EmployeeHoliday::with('createdBy');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if (!empty($type)) {
            $query->where('type', $type);
        }

        if (!empty($year)) {
            $query->whereYear('holiday_date', $year);
        }

        if ($isActive !== null && $isActive !== '') {
            $query->where('is_active', $isActive);
        }

        $totalRecords = EmployeeHoliday::count();
        $filteredRecords = $query->count();

        $holidays = $query->orderBy('holiday_date', 'desc')
            ->skip($start)
            ->take($length)
            ->get();

        $data = $holidays->map(function ($holiday) {
            $typeBadge = match($holiday->type) {
                'national' => '<span class="badge bg-primary">National</span>',
                'regional' => '<span class="badge bg-info">Regional</span>',
                'company' => '<span class="badge bg-success">Company</span>',
                'optional' => '<span class="badge bg-warning">Optional</span>',
                default => '<span class="badge bg-secondary">' . ucfirst($holiday->type) . '</span>'
            };

            return [
                'id' => $holiday->id,
                'title' => $holiday->title,
                'holiday_date' => $holiday->holiday_date ? \Carbon\Carbon::parse($holiday->holiday_date)->format('d M Y') : 'N/A',
                'description' => \Str::limit($holiday->description ?? 'N/A', 50),
                'type' => $typeBadge,
                'type_value' => $holiday->type,
                'is_active' => $holiday->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>',
                'is_active_value' => $holiday->is_active,
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
     * Show the form for creating holiday
     */
    public function create()
    {
        return view('employee-management.holiday.create');
    }

    /**
     * Store holiday
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'description' => 'nullable|string',
            'type' => 'required|in:national,regional,company,optional',
            'is_active' => 'boolean',
        ]);

        try {
            EmployeeHoliday::create([
                'title' => $validated['title'],
                'holiday_date' => $validated['holiday_date'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('employee-management.holiday')
                ->with('success', 'Holiday created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create holiday: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing holiday
     */
    public function edit($id)
    {
        $holiday = EmployeeHoliday::findOrFail($id);
        return view('employee-management.holiday.edit', compact('holiday'));
    }

    /**
     * Update holiday
     */
    public function update(Request $request, $id)
    {
        $holiday = EmployeeHoliday::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'description' => 'nullable|string',
            'type' => 'required|in:national,regional,company,optional',
            'is_active' => 'boolean',
        ]);

        try {
            $holiday->update([
                'title' => $validated['title'],
                'holiday_date' => $validated['holiday_date'],
                'description' => $validated['description'] ?? null,
                'type' => $validated['type'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return redirect()->route('employee-management.holiday')
                ->with('success', 'Holiday updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update holiday: ' . $e->getMessage());
        }
    }

    /**
     * Update holiday status
     */
    public function updateStatus(Request $request, $id)
    {
        $holiday = EmployeeHoliday::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        try {
            $holiday->update([
                'is_active' => $validated['is_active'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Holiday status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update holiday status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove holiday
     */
    public function destroy($id)
    {
        try {
            $holiday = EmployeeHoliday::findOrFail($id);
            $holiday->delete();

            return response()->json([
                'success' => true,
                'message' => 'Holiday deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete holiday: ' . $e->getMessage(),
            ], 500);
        }
    }
}
