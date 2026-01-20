<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EmployeeDocument;
use App\Models\EmployeeEducationalInfo;
use App\Models\EmployeeExperience;
use App\Models\EmployeeProfile;
use App\Models\Option;
use App\Models\OptionMaster;
use App\Models\EmployeeSalaryStructure;
use App\Models\Store;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\MyHelper;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('status', true)->orderBy('name')->get();
        $stores = Store::where('status', 'active')->orderBy('name')->get();

        return view('employee-management.manage-employee.index', compact('departments', 'stores'));
    }

    /**
     * AJAX endpoint for fetching employees with pagination (DataTables)
     */
    public function getEmployees(Request $request)
{
    $draw = intval($request->input('draw'));
    $start = intval($request->input('start', 0));
    $length = intval($request->input('length', 25));
    $search = $request->input('search.value');
    $departmentId = $request->input('department_id');
    $storeId = $request->input('store_id');
    $status = $request->input('status');

    $query = EmployeeProfile::with(['user', 'department', 'designation', 'store']);

    // 🔍 Search
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('employee_code', 'like', "%{$search}%")
              ->orWhereHas('user', function ($uq) use ($search) {
                  $uq->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%")
                     ->orWhere('mobile_number', 'like', "%{$search}%");
              });
        });
    }

    // 🎯 Filters
    if ($departmentId) {
        $query->where('department_id', $departmentId);
    }

    if ($storeId) {
        $query->where('store_id', $storeId);
    }

    if ($status) {
        $query->whereHas('user', function ($q) use ($status) {
            $q->where('status', $status === 'active' ? 1 : 0);
        });
    }

    // ✅ Correct counts
    $totalRecords = EmployeeProfile::count();
    $filteredRecords = (clone $query)->count();

    // 📄 Data
    $employees = $query
        ->orderByDesc('created_at')
        ->skip($start)
        ->take($length)
        ->get();

    $data = $employees->map(function ($employee) {
        return [
            'employee_code' => $employee->employee_code ?? 'N/A',
            'name' => $employee->user->name ?? 'N/A',
            'email' => $employee->user->email ?? '',
             'department_id' => $employee->department->name ?? 'N/A',
            'department' => $employee->department->name ?? 'N/A',
            'designation' => $employee->designation->name ?? 'N/A',
            'store' => $employee->store->name ?? 'N/A',
            'phone' => $employee->user->mobile_number ?? 'N/A',
            'status' => $employee->user && $employee->user->status ? 'active' : 'inactive',
            'uuid' => $employee->user->uuid ?? '',
        ];
    });

    return response()->json([
        'draw' => $draw,
        'recordsTotal' => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data' => $data,
    ]);
}

    
    public function create()
    {
        $departments = Department::where('status', true)->orderBy('name')->get();
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        $managers = User::whereHas('employeeProfile')->with('employeeProfile')->get();
        // Get options for employee type, marital status, and blood group
        $employeeTypeMaster = OptionMaster::where('name', 'Employee Type')->first();
        $maritalStatusMaster = OptionMaster::where('name', 'Marital Status')->first();
        $bloodGroupMaster = OptionMaster::where('name', 'Blood Group')->first();
        
        $employeeTypes = $employeeTypeMaster ? Option::where('option_master_id', $employeeTypeMaster->id)->where('status', true)->get() : collect();
        $maritalStatuses = $maritalStatusMaster ? Option::where('option_master_id', $maritalStatusMaster->id)->where('status', true)->get() : collect();
        $bloodGroups = $bloodGroupMaster ? Option::where('option_master_id', $bloodGroupMaster->id)->where('status', true)->get() : collect();

        $roles = Role::where('is_active', true)
        ->orderBy('name')
        ->get();
        // Generate next employee code
        $lastEmployee = EmployeeProfile::orderBy('id', 'desc')->first();
        $nextCode = 'EMP-' . str_pad(($lastEmployee ? $lastEmployee->id : 0) + 1, 4, '0', STR_PAD_LEFT);

        return view('employee-management.manage-employee.create', compact(
            'departments',
            'stores',
            'managers',
            'employeeTypes',
            'maritalStatuses',
            'bloodGroups',
            'roles',
            'nextCode'
        ));
    }
    
   public function store(Request $request)
{
    $validated = $request->validate([
        'employee_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'mobile_number' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'user_name' => 'nullable|string|max:255|unique:users,username',
        'employee_code' => 'nullable|string|unique:employee_profile,employee_code',
        'employee_type_id' => 'nullable|exists:options,id',
        'department_id' => 'nullable|exists:departments,id',
        'designation_id' => 'nullable|exists:designations,id',
        'store_id' => 'nullable|exists:stores,id',
        'joining_date' => 'nullable|date',
        'report_to' => 'nullable|exists:users,id',
        'resign_date' => 'nullable|date|after_or_equal:joining_date',
        'resign_status' => 'nullable|boolean',
        'expresnce' => 'nullable|string|max:255',
        'date_brith' => 'nullable|date',
        'marital_status_id' => 'nullable|exists:options,id',
        'blood_group_id' => 'nullable|exists:options,id',
        'emergency_contact' => 'nullable|string|max:20',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_relation' => 'nullable|string|max:255',
        'emergency_contact_relation_name' => 'nullable|string|max:255',
        'pan_no' => 'nullable|string|max:20',
        'aadhar_no' => 'nullable|string|max:20',
        'passport_no' => 'nullable|string|max:50',

        // New address fields
        'address_line_1' => 'nullable|string|max:255',
        'address_line_2' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:6',
        'role_id' => 'required|exists:roles,id',
    ]);

    DB::beginTransaction();
    try {
        // Generate employee code if not provided
        if (empty($validated['employee_code'])) {
            $lastEmployee = EmployeeProfile::orderBy('id', 'desc')->first();
            $validated['employee_code'] = 'EMP-' . str_pad(($lastEmployee ? $lastEmployee->id : 0) + 1, 4, '0', STR_PAD_LEFT);
        }

        // Generate password if not provided
        $password = $request->password ?? Str::random(12);

        // Create user
        $user = User::create([
            'name' => $validated['employee_name'],
            'email' => $validated['email'],
            'username' => $validated['user_name'] ?? null,
            'mobile_number' => $validated['mobile_number'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($password),
            'status' => true,
            'store_id' => $validated['store_id'] ?? null,
        ]);

        // Create employee profile
        $employeeProfile = EmployeeProfile::create([
            'user_id' => $user->id,
            'employee_code' => $validated['employee_code'],
            'employee_type_id' => $validated['employee_type_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'designation_id' => $validated['designation_id'] ?? null,
            'store_id' => $validated['store_id'] ?? null,
            'joining_date' => $validated['joining_date'] ?? null,
            'report_to' => $validated['report_to'] ?? null,
            'resign_date' => $validated['resign_date'] ?? null,
            'resign_status' => $validated['resign_status'] ?? false,
            'expresnce' => $validated['expresnce'] ?? null,
            'date_brith' => $validated['date_brith'] ?? null,
            'marital_status_id' => $validated['marital_status_id'] ?? null,
            'blood_group_id' => $validated['blood_group_id'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_relation' => $validated['emergency_contact_relation'] ?? null,
            'emergency_contact_relation_name' => $validated['emergency_contact_relation_name'] ?? null,
            'pan_no' => $validated['pan_no'] ?? null,
            'aadhar_no' => $validated['aadhar_no'] ?? null,
            'passport_no' => $validated['passport_no'] ?? null,

            // Save new address fields
            'address_line_1' => $validated['address_line_1'] ?? null,
            'address_line_2' => $validated['address_line_2'] ?? null,
            'country' => $validated['country'] ?? null,
            'state' => $validated['state'] ?? null,
            'city' => $validated['city'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'username' => $validated['user_name'] ?? null,

        ]);

        DB::commit();
$user->roles()->attach($request->role_id);
        return redirect()->route('employee-management.manage-employee')
            ->with('success', 'Employee created successfully. Password: ' . $password);
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to create employee: ' . $e->getMessage());
    }
}

    
    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::with(['user', 'department', 'designation', 'store', 'employeeType', 'maritalStatus', 'bloodGroup', 'reportingTo', 'educationalInfos', 'experiences', 'documents'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $departments = Department::where('status', true)->orderBy('name')->get();
        $designations = Designation::where('status', true)
            ->when($employee->department_id, function ($q) use ($employee) {
                $q->where('department_id', $employee->department_id);
            })
            ->orderBy('name')
            ->get();
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        $managers = User::whereHas('employeeProfile')
            ->where('id', '!=', $employee->user_id)
            ->with('employeeProfile')
            ->get();

        $salary = EmployeeSalaryStructure::where('employee_profile_id', $employee->id)
            ->latest('effective_date')
            ->first();
$roles = Role::where('is_active', true)
        ->orderBy('name')
        ->get();
        $employee = EmployeeProfile::whereHas('user', fn($q) => $q->where('uuid', $uuid))
    ->with('user.roles')
    ->firstOrFail();

$user = $employee->user;

// Assuming single role per user
$currentRoleId = $user->roles->first()?->id;
        // Get options
        $employeeTypeMaster = OptionMaster::where('name', 'Employee Type')->first();
        $maritalStatusMaster = OptionMaster::where('name', 'Marital Status')->first();
        $bloodGroupMaster = OptionMaster::where('name', 'Blood Group')->first();
        
        $employeeTypes = $employeeTypeMaster ? Option::where('option_master_id', $employeeTypeMaster->id)->where('status', true)->get() : collect();
        $maritalStatuses = $maritalStatusMaster ? Option::where('option_master_id', $maritalStatusMaster->id)->where('status', true)->get() : collect();
        $bloodGroups = $bloodGroupMaster ? Option::where('option_master_id', $bloodGroupMaster->id)->where('status', true)->get() : collect();

        return view('employee-management.manage-employee.edit', compact(
            'employee',
            'departments',
            'designations',
            'stores',
            'managers',
            'employeeTypes',
            'maritalStatuses',
            'bloodGroups',
            'salary',
            'roles',
    'currentRoleId'
        ));
    }

  public function update(Request $request, $uuid)
{
    $employee = EmployeeProfile::whereHas('user', fn($q) => $q->where('uuid', $uuid))->firstOrFail();
    $user = $employee->user;

    $validated = $request->validate([
        'employee_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'mobile_number' => 'nullable|string|max:20',
        'phone' => 'nullable|string|max:20',
        'employee_code' => 'nullable|string|unique:employee_profile,employee_code,' . $employee->id,
        'employee_type_id' => 'nullable|exists:options,id',
        'department_id' => 'nullable|exists:departments,id',
        'designation_id' => 'nullable|exists:designations,id',
        'store_id' => 'nullable|exists:stores,id',
        'joining_date' => 'nullable|date',
        'report_to' => 'nullable|exists:users,id',
        'resign_date' => 'nullable|date|after_or_equal:joining_date',
        'resign_status' => 'nullable|boolean',
        'expresnce' => 'nullable|string|max:255',
        'date_brith' => 'nullable|date',
        'marital_status_id' => 'nullable|exists:options,id',
        'blood_group_id' => 'nullable|exists:options,id',
        'emergency_contact' => 'nullable|string|max:20',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_relation' => 'nullable|string|max:255',
        'emergency_contact_relation_name' => 'nullable|string|max:255',
        'pan_no' => 'nullable|string|max:20',
        'aadhar_no' => 'nullable|string|max:20',
        'passport_no' => 'nullable|string|max:50',
        'address_line_1' => 'nullable|string|max:255',
        'address_line_2' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'country' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:10',
        'gender' => 'nullable|in:male,female',
        'password' => 'nullable|string|min:8',
        'role_id' => 'required|exists:roles,id',
    
        // Add other fields from your tabs if needed
    ]);

    DB::beginTransaction();
    try {
        // Update user table
        $user->update([
            'name' => $validated['employee_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'] ?? null,
            'password' => !empty($validated['password']) ? Hash::make($validated['password']) : $user->password,
        ]);

        // Update employee profile table
        $employee->update([
            'phone' => $validated['phone'] ?? null,
            'employee_code' => $validated['employee_code'] ?? $employee->employee_code,
            'employee_type_id' => $validated['employee_type_id'] ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'designation_id' => $validated['designation_id'] ?? null,
            'store_id' => $validated['store_id'] ?? null,
            'joining_date' => $validated['joining_date'] ?? null,
            'report_to' => $validated['report_to'] ?? null,
            'resign_date' => $validated['resign_date'] ?? null,
            'resign_status' => $validated['resign_status'] ?? false,
            'expresnce' => $validated['expresnce'] ?? null,
            'date_brith' => $validated['date_brith'] ?? null,
            'marital_status_id' => $validated['marital_status_id'] ?? null,
            'blood_group_id' => $validated['blood_group_id'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_relation' => $validated['emergency_contact_relation'] ?? null,
            'emergency_contact_relation_name' => $validated['emergency_contact_relation_name'] ?? null,
            'pan_no' => $validated['pan_no'] ?? null,
            'aadhar_no' => $validated['aadhar_no'] ?? null,
            'passport_no' => $validated['passport_no'] ?? null,
            'address_line_1' => $validated['address_line_1'] ?? null,
            'address_line_2' => $validated['address_line_2'] ?? null,
            'city' => $validated['city'] ?? null,
            'state' => $validated['state'] ?? null,
            'country' => $validated['country'] ?? null,
            'pincode' => $validated['pincode'] ?? null,
            'gender' => $validated['gender'] ?? null,
        ]);

        DB::commit();
 $user->roles()->sync([$validated['role_id']]);
        return redirect()->route('employee-management.manage-employee')
            ->with('success', 'Employee updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Failed to update employee: ' . $e->getMessage());
    }
}


    public function updateStatus(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        
        $request->validate([
            'status' => 'required|boolean',
        ]);

        try {
            $user->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee status updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();
        
        DB::beginTransaction();
        try {
            // Soft delete employee profile (user will be handled by cascade or manually)
            $employee->delete();
            
            DB::commit();
            
            return redirect()->route('employee-management.manage-employee')
                ->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete employee: ' . $e->getMessage());
        }
    }

    public function getDesignations(Request $request)
    {
        $designations = Designation::where('status', true)
            ->when($request->department_id, function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            })
            ->orderBy('name')
            ->get();

        return response()->json($designations);
    }

    public function storeEducationalInfo(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'grade' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        try {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = MyHelper::uploadImage(
                    $request->file('attachment'),
                    'employee-documents'
                );
            }

            $maxSortOrder = EmployeeEducationalInfo::where('employee_profile_id', $employee->id)->max('sort_order') ?? 0;

            $educationalInfo = EmployeeEducationalInfo::create([
                'employee_profile_id' => $employee->id,
                'degree' => $validated['degree'] ?? null,
                'institution' => $validated['institution'] ?? null,
                'year' => $validated['year'] ?? null,
                'grade' => $validated['grade'] ?? null,
                'attachment' => $attachmentPath,
                'sort_order' => $maxSortOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Educational information added successfully.',
                'data' => $educationalInfo->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add educational information: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateEducationalInfo(Request $request, $employee_uuid, $id)
    {
        $educationalInfo = EmployeeEducationalInfo::findOrFail($id);

        $validated = $request->validate([
            'degree' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:10',
            'grade' => 'nullable|string|max:50',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        try {
            if ($request->hasFile('attachment')) {
                // Delete old attachment if exists
                if ($educationalInfo->attachment) {
                    $attachmentPath = MyHelper::removeImage( $educationalInfo->attachment,'employee-documents');
                }
                $validated['attachment'] = MyHelper::uploadImage($request->file('attachment'),'employee-documents');
            }

            $educationalInfo->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Educational information updated successfully.',
                'data' => $educationalInfo->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update educational information: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyEducationalInfo($employee_uuid, $id)
    {
        try {
            $educationalInfo = EmployeeEducationalInfo::findOrFail($id);
            
            // Delete attachment if exists
            if ($educationalInfo->attachment) {
                MyHelper::removeImage($educationalInfo->attachment, 'employee-documents');
            }
            
            $educationalInfo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Educational information deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete educational information: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeExperience(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'description' => 'nullable|string',
        ]);

        try {
            $maxSortOrder = EmployeeExperience::where('employee_profile_id', $employee->id)->max('sort_order') ?? 0;

            $experience = EmployeeExperience::create([
                'employee_profile_id' => $employee->id,
                'company' => $validated['company'] ?? null,
                'position' => $validated['position'] ?? null,
                'from_date' => $validated['from_date'] ?? null,
                'to_date' => $validated['to_date'] ?? null,
                'description' => $validated['description'] ?? null,
                'sort_order' => $maxSortOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Experience added successfully.',
                'data' => $experience->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add experience: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateExperience(Request $request, $employee_uuid, $id)
    {
        $experience = EmployeeExperience::findOrFail($id);

        $validated = $request->validate([
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'description' => 'nullable|string',
        ]);

        try {
            $experience->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Experience updated successfully.',
                'data' => $experience->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update experience: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyExperience($employee_uuid, $id)
    {
        try {
            $experience = EmployeeExperience::findOrFail($id);
            $experience->delete();

            return response()->json([
                'success' => true,
                'message' => 'Experience deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete experience: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeDocument(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = MyHelper::uploadImage(
                    $request->file('file'),
                    'employee-documents'
                );
            }

            $maxSortOrder = EmployeeDocument::where('employee_profile_id', $employee->id)->max('sort_order') ?? 0;

            $document = EmployeeDocument::create([
                'employee_profile_id' => $employee->id,
                'title' => $validated['title'] ?? null,
                'file' => $filePath,
                'notes' => $validated['notes'] ?? null,
                'sort_order' => $maxSortOrder + 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document added successfully.',
                'data' => $document->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add document: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateDocument(Request $request, $employee_uuid, $id)
    {
        $document = EmployeeDocument::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ]);

        try {
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($document->file) {
                    MyHelper::removeImage($document->file, 'employee-documents');
                }
                $validated['file'] = MyHelper::uploadImage($request->file('file'), 'employee-documents');
            }

            $document->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Document updated successfully.',
                'data' => $document->load('employeeProfile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update document: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroyDocument($employee_uuid, $id)
    {
        try {
            $document = EmployeeDocument::findOrFail($id);
            
            // Delete file if exists
            if ($document->file) {
                MyHelper::removeImage($document->file, 'employee-documents');
            }
            
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete document: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateMemo(Request $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $employee = EmployeeProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'memo' => 'nullable|string',
        ]);

        try {
            $employee->update([
                'memo' => $validated['memo'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Memo updated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update memo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function storeWiseMapping()
    {
        return view('employee-management.store-wise-mapping.index');
    }

    public function createStoreWiseMapping()
    {
        return view('employee-management.store-wise-mapping.create');
    }

    public function attendance()
    {
        return view('employee-management.attendance.index');
    }

    public function createAttendance()
    {
        return view('employee-management.attendance.create');
    }

    public function leave()
    {
        return view('employee-management.leave.index');
    }

    public function createLeave()
    {
        return view('employee-management.leave.create');
    }

    public function salary()
    {
        return view('employee-management.salary.index');
    }

    public function createSalary()
    {
        return view('employee-management.salary.create');
    }

    public function recruitment()
    {
        return view('employee-management.recruitment.index');
    }

    public function createRecruitment()
    {
        return view('employee-management.recruitment.create');
    }

    public function training()
    {
        return view('employee-management.training.index');
    }

    public function createTraining()
    {
        return view('employee-management.training.create');
    }
}