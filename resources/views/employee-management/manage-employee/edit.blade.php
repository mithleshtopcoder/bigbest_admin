@extends('layouts.app')

@section('title', 'Edit Employee')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 px-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Employee</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Employee</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.manage-employee') }}" class="btn btn-xs btn-light">
                <i class="feather-x me-2"></i>Cancel
            </a>
        </div>
    </div>
</div>


<div class="">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-custom-style" id="employeeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="employee-info-tab" data-bs-toggle="tab" data-bs-target="#employee-info" type="button" role="tab" aria-controls="employee-info" aria-selected="true">
                        Employee Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="personal-info-tab" data-bs-toggle="tab" data-bs-target="#personal-info" type="button" role="tab" aria-controls="personal-info" aria-selected="false">
                        Personal Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="educational-info-tab" data-bs-toggle="tab" data-bs-target="#educational-info" type="button" role="tab" aria-controls="educational-info" aria-selected="false">
                        Educational Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience" type="button" role="tab" aria-controls="experience" aria-selected="false">
                        Experience
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                        Employee Documents
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="memo-tab" data-bs-toggle="tab" data-bs-target="#memo" type="button" role="tab" aria-controls="memo" aria-selected="false">
                        Memo
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="employment-details-tab" data-bs-toggle="tab" data-bs-target="#employment-details" type="button" role="tab" aria-controls="employment-details" aria-selected="false">
                        <i class="feather-dollar-sign me-2"></i>Salary Structure
                    </button>
                </li>
            </ul>
            <!-- Tab content -->
            <div class="tab-content p-2" id="employeeTabsContent" style="border: 1px solid #228c62;">
                <!-- Employee Info Tab -->
                <div class="tab-pane fade show active" id="employee-info" role="tabpanel" aria-labelledby="employee-info-tab">

                    <form action="{{ route('employee-management.manage-employee.update', $employee->user->uuid ?? '') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="row ps-2">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Employee Name <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" name="employee_name" class="form-control" placeholder="Enter employee name" value="{{ old('employee_name', $employee->user->name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Gender</label>
                                    <div class="col-md-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" checked>
                                            <label class="form-check-label" for="gender_male">Male</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female">
                                            <label class="form-check-label" for="gender_female">Female</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Address Line-1</label>
                                    <div class="col-md-8">
                                        <input type="text" name="address_line_1" class="form-control" placeholder="Enter address line 1" value="{{ old('address_line_1', $employee->address_line_1 ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Address Line-2</label>
                                    <div class="col-md-8">
                                        <input type="text" name="address_line_2" class="form-control" placeholder="Enter address line 2" value="{{ old('address_line_2', $employee->address_line_2 ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Country</label>
                                    <div class="col-md-8">
                                        <select name="country" class="form-select">
                                            <option value="">Select Country</option>
                                            <option value="india" selected>India</option>
                                            <option value="usa">USA</option>
                                            <option value="uk">UK</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">State</label>
                                    <div class="col-md-8">
                                        <select name="state" class="form-select">
                                            <option value="">Select State</option>
                                            <option value="bihar" {{ old('state', $employee->state ?? '') == 'bihar' ? 'selected' : '' }}>Bihar</option>
                                            <option value="up" {{ old('state', $employee->state ?? '') == 'up' ? 'selected' : '' }}>Uttar Pradesh</option>
                                            <option value="delhi" {{ old('state', $employee->state ?? '') == 'delhi' ? 'selected' : '' }}>Delhi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">City</label>
                                    <div class="col-md-8">
                                        <input type="text" name="city" class="form-control" placeholder="Enter city" value="{{ old('city', $employee->city ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Pincode</label>
                                    <div class="col-md-8">
                                        <input type="text" name="pincode" class="form-control" placeholder="Enter pincode" pattern="[0-9]{6}" maxlength="6" value="{{ old('pincode', $employee->pincode ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Mobile No.</label>
                                    <div class="col-md-8">
                                        <input type="tel" name="mobile_number" class="form-control" placeholder="Enter mobile number" pattern="[0-9]{10}" maxlength="10" value="{{ old('mobile_number', $employee->user->mobile_number ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Email ID</label>
                                    <div class="col-md-8">
                                        <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email', $employee->user->email ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Phone</label>
                                    <div class="col-md-8">
                                        <input type="text" name="phone" class="form-control" placeholder="Enter phone number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-4 custom-label">Role</label>
                                    <div class="col-md-8">
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option value="">Select Role</option>

                                            @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id', $currentRoleId) == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Employee Code</label>
                                    <div class="col-md-8">
                                        <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code ?? '') }}" placeholder="Employee Code">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Employee Type</label>
                                    <div class="col-md-8">
                                        <select name="employee_type_id" class="form-select">
                                            <option value="">Select Employee Type</option>
                                            @foreach($employeeTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('employee_type_id', $employee->employee_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Date of Joining</label>
                                    <div class="col-md-8">
                                        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Reporting To</label>
                                    <div class="col-md-8">
                                        <select name="report_to" class="form-select">
                                            <option value="">Select Manager</option>
                                            @foreach($managers as $manager)
                                            <option value="{{ $manager->id }}" {{ old('report_to', $employee->report_to) == $manager->id ? 'selected' : '' }}>{{ $manager->name }} ({{ $manager->employeeProfile->employee_code ?? 'N/A' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Department</label>
                                    <div class="col-md-8">
                                        <select name="department_id" id="department_id" class="form-select">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Designation</label>
                                    <div class="col-md-8">
                                        <select name="designation_id" id="designation_id" class="form-select">
                                            <option value="">Select Designation</option>
                                            @foreach($designations as $desg)
                                            <option value="{{ $desg->id }}" {{ old('designation_id', $employee->designation_id) == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Store</label>
                                    <div class="col-md-8">
                                        <select name="store_id" class="form-select">
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id', $employee->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">User Name</label>
                                    <div class="col-md-8">
                                        <input type="text" name="username" class="form-control" placeholder="Enter username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Experience</label>
                                    <div class="col-md-8">
                                        <input type="text" name="expresnce" class="form-control" placeholder="Enter experience" value="{{ old('expresnce', $employee->expresnce ?? '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Password</label>
                                    <div class="col-md-8">
                                        <input type="text" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                        <small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Resign Date</label>
                                    <div class="col-md-8">
                                        <input type="date" name="resign_date" class="form-control" value="{{ old('resign_date', $employee->resign_date ? $employee->resign_date->format('Y-m-d') : '') }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">Resign Status</label>
                                    <div class="col-md-8">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" name="resign_status" id="resign_status" value="1" {{ old('resign_status', $employee->resign_status) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="resign_status">Resigned</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('employee-management.manage-employee') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Update
                                    </button>
                                </div>
                            </div>
                        </div>


                </div>

                <!-- Personal Info Tab -->
                <div class="tab-pane fade" id="personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Date of Birth</label>
                                <div class="col-md-8">
                                    <input type="date" name="date_brith" class="form-control" value="{{ old('date_brith', $employee->date_brith ? $employee->date_brith->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Marital Status</label>
                                <div class="col-md-8">
                                    <select name="marital_status_id" class="form-select">
                                        <option value="">Select Status</option>
                                        @foreach($maritalStatuses as $status)
                                        <option value="{{ $status->id }}" {{ old('marital_status_id', $employee->marital_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Blood Group</label>
                                <div class="col-md-8">
                                    <select name="blood_group_id" class="form-select">
                                        <option value="">Select Blood Group</option>
                                        @foreach($bloodGroups as $bg)
                                        <option value="{{ $bg->id }}" {{ old('blood_group_id', $employee->blood_group_id) == $bg->id ? 'selected' : '' }}>{{ $bg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Emergency Contact</label>
                                <div class="col-md-8">
                                    <input type="text" name="emergency_contact" class="form-control" placeholder="Enter emergency contact" value="{{ old('emergency_contact', $employee->emergency_contact ?? '') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Emerg.Contact Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="emergency_contact_name" class="form-control" placeholder="Enter contact name" value="{{ old('emergency_contact_name', $employee->emergency_contact_name ?? '') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-4 form-label">Emerg.Contact Relation</label>
                                <div class="col-md-8">
                                    <input type="text" name="emergency_contact_relation" class="form-control" placeholder="Enter relation" value="{{ old('emergency_contact_relation', $employee->emergency_contact_relation ?? '') }}">
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <label class="col-md-4 form-label">Emergency Contact Relation Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="emergency_contact_relation_name" class="form-control" placeholder="Enter relation name" value="{{ old('emergency_contact_relation_name', $employee->emergency_contact_relation_name ?? '') }}">
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4 form-label">PAN Number</label>
                        <div class="col-md-8">
                            <input type="text" name="pan_no" class="form-control" placeholder="Enter PAN number" value="{{ old('pan_no', $employee->pan_no ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 form-label">Aadhar Number</label>
                        <div class="col-md-8">
                            <input type="text" name="aadhar_no" class="form-control" placeholder="Enter Aadhar number" value="{{ old('aadhar_no', $employee->aadhar_no ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 form-label">Passport Number</label>
                        <div class="col-md-8">
                            <input type="text" name="passport_no" class="form-control" placeholder="Enter passport number" value="{{ old('passport_no', $employee->passport_no ?? '') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 form-label">Nationality</label>
                        <div class="col-md-8">
                            <input type="text" name="nationality" class="form-control" placeholder="Enter nationality" value="Indian">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('employee-management.manage-employee') }}" class="btn btn-light">
                            <i class="feather-x me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update
                        </button>
                    </div>
                </div>
            </div>
            </form>

        </div>

        <!-- Educational Info Tab -->
        <div class="tab-pane fade" id="educational-info" role="tabpanel" aria-labelledby="educational-info-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Educational Qualifications</h6>
                    <button type="button" class="btn btn-xs btn-primary" id="add-education-row" data-bs-toggle="modal" data-bs-target="#educationModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Education
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-bordered">
                            <thead>
                                <tr class="border-b">
                                    <th scope="col" style="width: 50px;">No</th>
                                    <th scope="col">Degree</th>
                                    <th scope="col">Institution</th>
                                    <th scope="col">Year</th>
                                    <th scope="col">Grade</th>
                                    <th scope="col">Attachments</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="educational-info-table-body">
                                @forelse($employee->educationalInfos as $index => $edu)
                                <tr data-id="{{ $edu->id }}" class="education-row">
                                    <td class="row-number">{{ $index + 1 }}</td>
                                    <td class="degree-cell">{{ $edu->degree ?? 'N/A' }}</td>
                                    <td class="institution-cell">{{ $edu->institution ?? 'N/A' }}</td>
                                    <td class="year-cell">{{ $edu->year ?? 'N/A' }}</td>
                                    <td class="grade-cell">{{ $edu->grade ?? 'N/A' }}</td>
                                    <td class="attachment-cell">
                                        @if($edu->attachment)
                                        <a href="{{ asset('images/employee-documents/' . $edu->attachment) }}" target="_blank" class="btn btn-sm btn-link p-0 view-attachment-link">
                                            <i class="bi bi-download text-primary"></i> View
                                        </a>
                                        <span class="attachment-path d-none">{{ $edu->attachment }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end action-buttons">
                                            <button type="button" class="btn btn-sm btn-light edit-education-modal" data-id="{{ $edu->id }}" data-bs-toggle="modal" data-bs-target="#educationModal" data-degree="{{ $edu->degree ?? '' }}" data-institution="{{ $edu->institution ?? '' }}" data-year="{{ $edu->year ?? '' }}" data-grade="{{ $edu->grade ?? '' }}" data-attachment="{{ $edu->attachment ?? '' }}">
                                                <i class="bi bi-pencil text-primary"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light delete-education" data-id="{{ $edu->id }}">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="no-data-row">
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <div class="fw-bold">No results found</div>
                                            <div class="small">No data available</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Experience Tab -->
        <div class="tab-pane fade" id="experience" role="tabpanel" aria-labelledby="experience-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Work Experience</h6>
                    <button type="button" class="btn btn-xs btn-primary" id="add-experience-row" data-bs-toggle="modal" data-bs-target="#experienceModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Experience
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-bordered">
                            <thead>
                                <tr class="border-b">
                                    <th scope="col" style="width: 50px;">No</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">From Date</th>
                                    <th scope="col">To Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="experience-table-body">
                                @forelse($employee->experiences as $index => $exp)
                                <tr data-id="{{ $exp->id }}" class="experience-row">
                                    <td class="row-number">{{ $index + 1 }}</td>
                                    <td class="company-cell">{{ $exp->company ?? 'N/A' }}</td>
                                    <td class="position-cell">{{ $exp->position ?? 'N/A' }}</td>
                                    <td class="from-date-cell">{{ $exp->from_date ? \Carbon\Carbon::parse($exp->from_date)->format('d M Y') : 'N/A' }}</td>
                                    <td class="to-date-cell">{{ $exp->to_date ? \Carbon\Carbon::parse($exp->to_date)->format('d M Y') : 'N/A' }}</td>
                                    <td class="description-cell">{{ $exp->description ? (strlen($exp->description) > 50 ? substr($exp->description, 0, 50) . '...' : $exp->description) : 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end action-buttons">
                                            <button type="button" class="btn btn-sm btn-light edit-experience-modal" data-id="{{ $exp->id }}" data-bs-toggle="modal" data-bs-target="#experienceModal" data-company="{{ $exp->company ?? '' }}" data-position="{{ $exp->position ?? '' }}" data-from-date="{{ $exp->from_date ? \Carbon\Carbon::parse($exp->from_date)->format('Y-m-d') : '' }}" data-to-date="{{ $exp->to_date ? \Carbon\Carbon::parse($exp->to_date)->format('Y-m-d') : '' }}" data-description="{{ $exp->description ?? '' }}">
                                                <i class="bi bi-pencil text-primary"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light delete-experience" data-id="{{ $exp->id }}">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="no-data-row">
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <div class="fw-bold">No results found</div>
                                            <div class="small">No data available</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Employee Documents</h6>
                    <button type="button" class="btn btn-xs btn-primary" id="add-document-row" data-bs-toggle="modal" data-bs-target="#documentModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Document
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 table-bordered">
                            <thead>
                                <tr class="border-b">
                                    <th scope="col" style="width: 50px;">No</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">File</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="documents-table-body">
                                @forelse($employee->documents as $index => $doc)
                                <tr data-id="{{ $doc->id }}" class="document-row">
                                    <td class="row-number">{{ $index + 1 }}</td>
                                    <td class="title-cell">{{ $doc->title ?? 'N/A' }}</td>
                                    <td class="file-cell">
                                        @if($doc->file)
                                        <a href="{{ asset('images/employee-documents/' . $doc->file) }}" target="_blank" class="btn btn-sm btn-link p-0 view-file-link">
                                            <i class="bi bi-download text-primary"></i> View
                                        </a>
                                        <span class="file-path d-none">{{ $doc->file }}</span>
                                        @else
                                        <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="notes-cell">{{ $doc->notes ? (strlen($doc->notes) > 50 ? substr($doc->notes, 0, 50) . '...' : $doc->notes) : 'N/A' }}</td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end action-buttons">
                                            <button type="button" class="btn btn-sm btn-light edit-document-modal" data-id="{{ $doc->id }}" data-bs-toggle="modal" data-bs-target="#documentModal" data-title="{{ $doc->title ?? '' }}" data-file="{{ $doc->file ?? '' }}" data-notes="{{ $doc->notes ?? '' }}">
                                                <i class="bi bi-pencil text-primary"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light delete-document" data-id="{{ $doc->id }}">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="no-data-row">
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                            <div class="fw-bold">No results found</div>
                                            <div class="small">No data available</div>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Memo Tab -->
        <div class="tab-pane fade" id="memo" role="tabpanel" aria-labelledby="memo-tab">
            <div class="form-group mb-3">
                <textarea id="memo-editor" name="memo" class="form-control" rows="15" placeholder="Enter any additional notes or memos about the employee...">{{ $employee->memo ?? '' }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-xs btn-primary" id="save-memo-btn">
                        <i class="bi bi-save me-2"></i>Save Memo
                    </button>
                </div>
            </div>
        </div>

        <!-- Salary Structure Tab -->
        <div class="tab-pane fade" id="employment-details" role="tabpanel" aria-labelledby="employment-details-tab">
            <div class="row">

                {{-- LEFT COLUMN --}}
                <div class="col-md-6">

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Basic Salary</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->basic_salary ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">HRA</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->hra ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">DA</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->da ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">TA</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->ta ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Medical Allowance</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->medical_allowance ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Other Allowances</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->other_allowances ?? '0.00' }}" readonly>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-md-6">

                    <div class="form-group row">
                        <label class="col-md-4 form-label">PF</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->pf ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">ESI</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->esi ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">TDS</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->tds ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Other Deductions</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control" value="{{ $salary->other_deductions ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Gross Salary</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control fw-bold text-success" value="{{ $salary->gross_salary ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Net Salary</label>
                        <div class="col-md-8">
                            <input type="number" class="form-control fw-bold text-primary" value="{{ $salary->net_salary ?? '0.00' }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Effective Date</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control" value="{{ optional($salary->effective_date)->format('Y-m-d') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 form-label">Currency</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" value="{{ $salary->currency ?? 'INR' }}" readonly>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>
</div>
</div>
<!-- [ Main Content ] end -->

<!-- Educational Info Modal -->
<div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="educationModalLabel">Add Educational Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="educationForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="education_id" name="education_id">
                    <div class="row ps-2">
                        <div class="col-md-12 mb-3">
                            <div class="form-group row">
                                <label for="degree" class="form-label col-md-2">Degree <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="degree" name="degree" placeholder="Enter degree" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <label for="institution" class="form-label col-md-2">Institution <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="institution" name="institution" placeholder="Enter institution name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="year" class="form-label col-md-2">Year</label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="year" name="year" placeholder="Enter year (e.g., 2020)" maxlength="10">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <label for="grade" class="form-label col-md-2">Grade</label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="grade" name="grade" placeholder="Enter grade (e.g., A+, First Class)" maxlength="50">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="attachment" class="form-label col-md-2">Attachment</label>
                                <div class="col-md-4 ps-1">
                                    <input type="file" class="form-control" id="attachment" name="attachment" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                                    <div class="invalid-feedback"></div>
                                    <div id="current-attachment" class="mt-2 d-none">
                                        <small class="text-muted">Current attachment: <span id="attachment-name"></span></small>
                                        <a href="#" id="view-attachment-link" target="_blank" class="ms-2">
                                            <i class="bi bi-download"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Experience Modal -->
<div class="modal fade" id="experienceModal" tabindex="-1" aria-labelledby="experienceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="experienceModalLabel">Add Work Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="experienceForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="experience_id" name="experience_id">
                    <div class="row ps-2">
                        <div class="col-md-12 mb-3">
                            <div class="form-group row">
                                <label for="company" class="form-label col-md-2">Company <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="company" name="company" placeholder="Enter company name" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <label for="position" class="form-label col-md-2">Position <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" class="form-control" id="position" name="position" placeholder="Enter position" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="from_date" class="form-label col-md-2">From Date</label>
                                <div class="col-md-4 ps-1">
                                    <input type="date" class="form-control" id="from_date" name="from_date">
                                    <div class="invalid-feedback"></div>
                                </div>
                                <label for="to_date" class="form-label col-md-2">To Date</label>
                                <div class="col-md-4 ps-1">
                                    <input type="date" class="form-control" id="to_date" name="to_date">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="form-label col-md-2">Description</label>
                                <div class="col-md-10 ps-1">
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter job description"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Add Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="document_id" name="document_id">
                    <div class="row ps-2">
                        <div class="col-md-12 mb-3">
                            <div class="form-group row">
                                <label for="doc_title" class="form-label col-md-2">Title <span class="text-danger">*</span></label>
                                <div class="col-md-10 ps-1">
                                    <input type="text" class="form-control" id="doc_title" name="title" placeholder="Enter document title" required>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doc_file" class="form-label col-md-2">File</label>
                                <div class="col-md-10 ps-1">
                                    <input type="file" class="form-control" id="doc_file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG (Max: 10MB)</small>
                                    <div class="invalid-feedback"></div>
                                    <div id="current-file" class="mt-2 d-none">
                                        <small class="text-muted">Current file: <span id="file-name"></span></small>
                                        <a href="#" id="view-file-link" target="_blank" class="ms-2">
                                            <i class="bi bi-download"></i> View
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doc_notes" class="form-label col-md-2">Notes</label>
                                <div class="col-md-10 ps-1">
                                    <textarea class="form-control" id="doc_notes" name="notes" rows="3" placeholder="Enter notes about this document"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const employeeUuid = '{{ $employee->user->uuid ?? '
        ' }}';
        const educationModal = new bootstrap.Modal(document.getElementById('educationModal'));
        const educationForm = document.getElementById('educationForm');
        const modalTitle = document.getElementById('educationModalLabel');
        const educationIdInput = document.getElementById('education_id');
        const currentAttachmentDiv = document.getElementById('current-attachment');
        const attachmentNameSpan = document.getElementById('attachment-name');
        const viewAttachmentLink = document.getElementById('view-attachment-link');

        // Store route URLs - construct manually to avoid Blade syntax issues
        const baseUrl = '{{ url("/employee-management/educational-info") }}';
        const storeUrl = baseUrl + '/' + employeeUuid + '/store';

        // Add Education button click
        document.getElementById('add-education-row').addEventListener('click', function() {
            resetForm();
            modalTitle.textContent = 'Add Educational Information';
            educationIdInput.value = '';
            currentAttachmentDiv.classList.add('d-none');
            educationForm.reset();
        });

        // Edit Education button click
        document.querySelectorAll('.edit-education-modal').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const degree = this.getAttribute('data-degree') || '';
                const institution = this.getAttribute('data-institution') || '';
                const year = this.getAttribute('data-year') || '';
                const grade = this.getAttribute('data-grade') || '';
                const attachment = this.getAttribute('data-attachment') || '';

                resetForm();
                modalTitle.textContent = 'Edit Educational Information';
                educationIdInput.value = id;
                document.getElementById('degree').value = degree;
                document.getElementById('institution').value = institution;
                document.getElementById('year').value = year;
                document.getElementById('grade').value = grade;

                if (attachment) {
                    const attachmentUrl = '{{ asset("storage") }}/' + attachment;
                    attachmentNameSpan.textContent = attachment.split('/').pop();
                    viewAttachmentLink.href = attachmentUrl;
                    currentAttachmentDiv.classList.remove('d-none');
                } else {
                    currentAttachmentDiv.classList.add('d-none');
                }
            });
        });

        // Form submission
        educationForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const educationId = educationIdInput.value;
            let url, method;

            if (educationId) {
                // Update
                url = baseUrl + '/' + employeeUuid + '/update/' + educationId;
                method = 'PUT';
            } else {
                // Create
                url = storeUrl;
                method = 'POST';
            }

            // Add _method for PUT request
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';

            fetch(url, {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        , 'Accept': 'application/json'
                    }
                    , body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showAlert('success', data.message);

                        // Close modal
                        educationModal.hide();

                        // Reload page to refresh table
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        showAlert('danger', data.message || 'An error occurred');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('danger', 'An error occurred while saving. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Delete Education
        document.querySelectorAll('.delete-education').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                if (!confirm('Are you sure you want to delete this educational information?')) {
                    return;
                }

                const url = baseUrl + '/' + employeeUuid + '/delete/' + id;

                fetch(url, {
                        method: 'DELETE'
                        , headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            , 'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showAlert('danger', data.message || 'Failed to delete educational information');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('danger', 'An error occurred while deleting. Please try again.');
                    });
            });
        });

        // Reset form validation
        function resetForm() {
            const inputs = educationForm.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                }
            });
        }

        // Show alert message
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

            const container = document.querySelector('.main-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }

        // Reset form when modal is closed
        document.getElementById('educationModal').addEventListener('hidden.bs.modal', function() {
            resetForm();
            educationForm.reset();
            educationIdInput.value = '';
            currentAttachmentDiv.classList.add('d-none');
        });
    });

    // Experience Management
    document.addEventListener('DOMContentLoaded', function() {
        const employeeUuid = '{{ $employee->user->uuid ?? '
        ' }}';
        const experienceModal = new bootstrap.Modal(document.getElementById('experienceModal'));
        const experienceForm = document.getElementById('experienceForm');
        const modalTitle = document.getElementById('experienceModalLabel');
        const experienceIdInput = document.getElementById('experience_id');

        // Store route URLs - construct manually to avoid Blade syntax issues
        const experienceBaseUrl = '{{ url("/employee-management/experience") }}';
        const experienceStoreUrl = experienceBaseUrl + '/' + employeeUuid + '/store';

        // Add Experience button click
        document.getElementById('add-experience-row').addEventListener('click', function() {
            resetExperienceForm();
            modalTitle.textContent = 'Add Work Experience';
            experienceIdInput.value = '';
            experienceForm.reset();
        });

        // Edit Experience button click
        document.querySelectorAll('.edit-experience-modal').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const company = this.getAttribute('data-company') || '';
                const position = this.getAttribute('data-position') || '';
                const fromDate = this.getAttribute('data-from-date') || '';
                const toDate = this.getAttribute('data-to-date') || '';
                const description = this.getAttribute('data-description') || '';

                resetExperienceForm();
                modalTitle.textContent = 'Edit Work Experience';
                experienceIdInput.value = id;
                document.getElementById('company').value = company;
                document.getElementById('position').value = position;
                document.getElementById('from_date').value = fromDate;
                document.getElementById('to_date').value = toDate;
                document.getElementById('description').value = description;
            });
        });

        // Form submission
        experienceForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const experienceId = experienceIdInput.value;
            let url, method;

            if (experienceId) {
                // Update
                url = experienceBaseUrl + '/' + employeeUuid + '/update/' + experienceId;
                method = 'PUT';
            } else {
                // Create
                url = experienceStoreUrl;
                method = 'POST';
            }

            // Add _method for PUT request
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';

            fetch(url, {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        , 'Accept': 'application/json'
                    }
                    , body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showExperienceAlert('success', data.message);

                        // Close modal
                        experienceModal.hide();

                        // Reload page to refresh table
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        showExperienceAlert('danger', data.message || 'An error occurred');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showExperienceAlert('danger', 'An error occurred while saving. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Delete Experience
        document.querySelectorAll('.delete-experience').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                if (!confirm('Are you sure you want to delete this experience?')) {
                    return;
                }

                const url = experienceBaseUrl + '/' + employeeUuid + '/delete/' + id;

                fetch(url, {
                        method: 'DELETE'
                        , headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            , 'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showExperienceAlert('success', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showExperienceAlert('danger', data.message || 'Failed to delete experience');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showExperienceAlert('danger', 'An error occurred while deleting. Please try again.');
                    });
            });
        });

        // Reset form validation
        function resetExperienceForm() {
            const inputs = experienceForm.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                }
            });
        }

        // Show alert message
        function showExperienceAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

            const container = document.querySelector('.main-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }

        // Reset form when modal is closed
        document.getElementById('experienceModal').addEventListener('hidden.bs.modal', function() {
            resetExperienceForm();
            experienceForm.reset();
            experienceIdInput.value = '';
        });
    });

    // Document Management
    document.addEventListener('DOMContentLoaded', function() {
        const employeeUuid = '{{ $employee->user->uuid ?? '
        ' }}';
        const documentModal = new bootstrap.Modal(document.getElementById('documentModal'));
        const documentForm = document.getElementById('documentForm');
        const modalTitle = document.getElementById('documentModalLabel');
        const documentIdInput = document.getElementById('document_id');
        const currentFileDiv = document.getElementById('current-file');
        const fileNameSpan = document.getElementById('file-name');
        const viewFileLink = document.getElementById('view-file-link');

        // Store route URLs - construct manually to avoid Blade syntax issues
        const documentBaseUrl = '{{ url("/employee-management/documents") }}';
        const documentStoreUrl = documentBaseUrl + '/' + employeeUuid + '/store';

        // Add Document button click
        document.getElementById('add-document-row').addEventListener('click', function() {
            resetDocumentForm();
            modalTitle.textContent = 'Add Document';
            documentIdInput.value = '';
            currentFileDiv.classList.add('d-none');
            documentForm.reset();
        });

        // Edit Document button click
        document.querySelectorAll('.edit-document-modal').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title') || '';
                const file = this.getAttribute('data-file') || '';
                const notes = this.getAttribute('data-notes') || '';

                resetDocumentForm();
                modalTitle.textContent = 'Edit Document';
                documentIdInput.value = id;
                document.getElementById('doc_title').value = title;
                document.getElementById('doc_notes').value = notes;

                if (file) {
                    const fileUrl = '{{ asset("images/employee-documents") }}/' + file;
                    fileNameSpan.textContent = file.split('/').pop();
                    viewFileLink.href = fileUrl;
                    currentFileDiv.classList.remove('d-none');
                } else {
                    currentFileDiv.classList.add('d-none');
                }
            });
        });

        // Form submission
        documentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const documentId = documentIdInput.value;
            let url, method;

            if (documentId) {
                // Update
                url = documentBaseUrl + '/' + employeeUuid + '/update/' + documentId;
                method = 'PUT';
            } else {
                // Create
                url = documentStoreUrl;
                method = 'POST';
            }

            // Add _method for PUT request
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';

            fetch(url, {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        , 'Accept': 'application/json'
                    }
                    , body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showDocumentAlert('success', data.message);

                        // Close modal
                        documentModal.hide();

                        // Reload page to refresh table
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Show error message
                        showDocumentAlert('danger', data.message || 'An error occurred');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showDocumentAlert('danger', 'An error occurred while saving. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });

        // Delete Document
        document.querySelectorAll('.delete-document').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');

                if (!confirm('Are you sure you want to delete this document?')) {
                    return;
                }

                const url = documentBaseUrl + '/' + employeeUuid + '/delete/' + id;

                fetch(url, {
                        method: 'DELETE'
                        , headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            , 'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showDocumentAlert('success', data.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            showDocumentAlert('danger', data.message || 'Failed to delete document');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showDocumentAlert('danger', 'An error occurred while deleting. Please try again.');
                    });
            });
        });

        // Reset form validation
        function resetDocumentForm() {
            const inputs = documentForm.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentElement.querySelector('.invalid-feedback');
                if (feedback) {
                    feedback.textContent = '';
                }
            });
        }

        // Show alert message
        function showDocumentAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

            const container = document.querySelector('.main-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }

        // Reset form when modal is closed
        document.getElementById('documentModal').addEventListener('hidden.bs.modal', function() {
            resetDocumentForm();
            documentForm.reset();
            documentIdInput.value = '';
            currentFileDiv.classList.add('d-none');
        });
    });

    // Memo Management with Summernote
    document.addEventListener('DOMContentLoaded', function() {
        const employeeUuid = '{{ $employee->user->uuid ?? '
        ' }}';

        // Initialize Summernote
        if ($('#memo-editor').length) {
            $('#memo-editor').summernote({
                height: 400
                , toolbar: [
                    ['style', ['style']]
                    , ['font', ['bold', 'italic', 'underline', 'clear']]
                    , ['fontname', ['fontname']]
                    , ['fontsize', ['fontsize']]
                    , ['color', ['color']]
                    , ['para', ['ul', 'ol', 'paragraph']]
                    , ['table', ['table']]
                    , ['insert', ['link', 'picture', 'video']]
                    , ['view', ['fullscreen', 'codeview', 'help']]
                ]
                , placeholder: 'Enter any additional notes or memos about the employee...'
            });
        }

        // Save Memo button click
        document.getElementById('save-memo-btn').addEventListener('click', function() {
            const memoContent = $('#memo-editor').summernote('code');
            const saveBtn = this;
            const originalText = saveBtn.innerHTML;

            // Show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Saving...';

            const url = '{{ route("employee-management.manage-employee.memo", ":uuid") }}'.replace(':uuid', employeeUuid);

            fetch(url, {
                    method: 'POST'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        , 'Accept': 'application/json'
                        , 'Content-Type': 'application/json'
                    }
                    , body: JSON.stringify({
                        memo: memoContent
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMemoAlert('success', data.message);
                    } else {
                        showMemoAlert('danger', data.message || 'An error occurred');
                    }
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMemoAlert('danger', 'An error occurred while saving. Please try again.');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                });
        });

        // Show alert message
        function showMemoAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            const container = document.querySelector('.main-body');
            if (container) {
                container.insertBefore(alertDiv, container.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        }
    });

</script>
@endsection

@section('styles')

@endsection
