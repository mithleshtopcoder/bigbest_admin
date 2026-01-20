@extends('layouts.app')

@section('title', 'Create Employee')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Employee</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employee-management.manage-employee') }}">Manage Employee</a></li>
            <li class="breadcrumb-item">Create</li>
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <div class="d-flex d-md-none">
                <a href="javascript:void(0)" class="page-header-right-close-toggle py-2">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">

                    <div class="row pl-2">
                        <ul class="nav nav-tabs nav-tabs-custom-style mb-2" id="employeeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="employee-info-tab" data-bs-toggle="tab" data-bs-target="#employee-info" type="button" role="tab" aria-controls="employee-info" aria-selected="true">
                                    Employee Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" type="button" style="cursor: no-drop;" role="tab" aria-selected="false">
                                    Personal Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" type="button" style="cursor: no-drop;" role="tab" aria-selected="false">
                                    Educational Info
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" type="button" style="cursor: no-drop;" role="tab" aria-selected="false">
                                    Experience
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" type="button" style="cursor: no-drop;" role="tab" aria-selected="false">
                                    Employee Documents
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" type="button" style="cursor: no-drop;" role="tab" aria-selected="false">
                                    Memo
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="employeeTabsContent">
                            <!-- Employee Info Tab -->
                            <div class="tab-pane fade show active" id="employee-info" role="tabpanel" aria-labelledby="employee-info-tab">

                                <form action="{{ route('employee-management.manage-employee.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

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
                                    <div class="row">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Employee Name <span class="text-danger">*</span></label>
                                                <div class="col-md-8">
                                                    <input type="text" name="employee_name" class="form-control form-control" placeholder="Enter employee name" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Gender</label>
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
                                                <label class="form-label text-md col-md-4 custom-label">Address Line-1</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="address_line_1" class="form-control form-control" placeholder="Enter address line 1">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Address Line-2</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="address_line_2" class="form-control form-control" placeholder="Enter address line 2">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Country</label>
                                                <div class="col-md-8">
                                                    <select name="country" class="form-select form-control">
                                                        <option value="">Select Country</option>
                                                        <option value="india" selected>India</option>
                                                        <option value="usa">USA</option>
                                                        <option value="uk">UK</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">State</label>
                                                <div class="col-md-8">
                                                    <select name="state" class="form-select form-control">
                                                        <option value="">Select State</option>
                                                        <option value="bihar">Bihar</option>
                                                        <option value="up">Uttar Pradesh</option>
                                                        <option value="delhi">Delhi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">City</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="city" class="form-control form-control" placeholder="Enter city">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Pincode</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="pincode" class="form-control form-control" placeholder="Enter pincode" pattern="[0-9]{6}" maxlength="6">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Mobile No.</label>
                                                <div class="col-md-8">
                                                    <input type="tel" name="mobile_number" class="form-control form-control" placeholder="Enter mobile number">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Email ID</label>
                                                <div class="col-md-8">
                                                    <input type="email" name="email" class="form-control form-control" placeholder="Enter email" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Phone</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="phone" class="form-control form-control" placeholder="Enter phone number">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Role</label>
                                                <div class="col-md-8">
                                                    <select name="role_id" id="role_id" class="form-control">
                                                        <option value="">Select Role</option>
                                                        @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">
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
                                                <label class="form-label text-md col-md-4 custom-label">Employee Code</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="employee_code" class="form-control form-control" value="{{ $nextCode }}" placeholder="Auto-generated">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Employee Type</label>
                                                <div class="col-md-8">
                                                    <select name="employee_type_id" class="form-select form-control">
                                                        <option value="">Select Employee Type</option>
                                                        @foreach($employeeTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Date of Joining</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="joining_date" class="form-control form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Reporting To</label>
                                                <div class="col-md-8">
                                                    <select name="report_to" class="form-select form-control">
                                                        <option value="">Select Manager</option>
                                                        @foreach($managers as $manager)
                                                        <option value="{{ $manager->id }}">{{ $manager->name }} ({{ $manager->employeeProfile->employee_code ?? 'N/A' }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Department</label>
                                                <div class="col-md-8">
                                                    <select name="department_id" id="department_id" class="form-select form-control">
                                                        <option value="">Select Department</option>
                                                        @foreach($departments as $dept)
                                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Designation</label>
                                                <div class="col-md-8">
                                                    <select name="designation_id" id="designation_id" class="form-select form-control">
                                                        <option value="">Select Designation</option>
                                                        {{-- Designations will be loaded dynamically via JS --}}
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Store</label>
                                                <div class="col-md-8">
                                                    <select name="store_id" class="form-select form-control">
                                                        <option value="">Select Store</option>
                                                        @foreach($stores as $store)
                                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">User Name</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="username" class="form-control form-control" placeholder="Enter username">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Experience</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="expresnce" class="form-control form-control" placeholder="Enter experience">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Password</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="password" class="form-control form-control" placeholder="Auto-generated if left blank">
                                                    <small class="text-muted">Leave blank to auto-generate</small>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Resign Date</label>
                                                <div class="col-md-8">
                                                    <input type="date" name="resign_date" class="form-control form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="form-label text-md col-md-4 custom-label">Resign Status</label>
                                                <div class="col-md-8">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="resign_status" id="resign_status" value="1">
                                                        <label class="form-check-label" for="resign_status">Resigned</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <!-- Form Actions -->
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="feather-save me-2"></i>Submit
                                                        </button>
                                                        <a href="{{ route('employee-management.manage-employee') }}" class="btn btn-light">
                                                            <i class="feather-x me-2"></i>Cancel
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>



                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('department_id');
        const designationSelect = document.getElementById('designation_id');

        if (departmentSelect && designationSelect) {
            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
                designationSelect.innerHTML = '<option value="">Select Designation</option>';

                if (!departmentId) return;

                fetch(`{{ route('employee-management.manage-employee.designations') }}?department_id=${departmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(designation => {
                            const option = document.createElement('option');
                            option.value = designation.id;
                            option.textContent = designation.name;
                            designationSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading designations:', error));
            });
        }
    });

</script>
@endsection


@section('styles')

@endsection
