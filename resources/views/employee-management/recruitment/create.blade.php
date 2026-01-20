@extends('layouts.app')

@section('title', 'Create Recruitment')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Recruitment</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employee-management.recruitment') }}">Recruitment & Onboarding</a></li>
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
                    <form action="{{ route('employee-management.recruitment') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Recruitment Information</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Candidate Name <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="candidate_name" class="form-control form-control-sm" placeholder="Enter candidate name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Email <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Enter email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Phone <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Enter phone number" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Position Applied <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <select name="position" class="form-select form-control-sm" required>
                                            <option value="">Select Position</option>
                                            <option value="sales_executive">Sales Executive</option>
                                            <option value="cashier">Cashier</option>
                                            <option value="store_manager">Store Manager</option>
                                            <option value="inventory_manager">Inventory Manager</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Department</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="department" class="form-select form-control-sm">
                                            <option value="">Select Department</option>
                                            <option value="sales">Sales</option>
                                            <option value="inventory">Inventory</option>
                                            <option value="finance">Finance</option>
                                            <option value="hr">HR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Interview Date</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="datetime-local" name="interview_date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Status</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="status" class="form-select form-control-sm">
                                            <option value="applied">Applied</option>
                                            <option value="screening">Screening</option>
                                            <option value="interview">Interview</option>
                                            <option value="offered">Offered</option>
                                            <option value="hired">Hired</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Experience (Years)</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="experience" class="form-control form-control-sm" placeholder="Enter years of experience" step="0.1">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Expected Salary</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="expected_salary" class="form-control form-control-sm" placeholder="Enter expected salary" step="0.01">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Notes</label>
                                    <div class="col-md-9 pl-1">
                                        <textarea name="notes" class="form-control form-control-sm" rows="4" placeholder="Enter notes"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Save Recruitment
                                    </button>
                                    <a href="{{ route('employee-management.recruitment') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

