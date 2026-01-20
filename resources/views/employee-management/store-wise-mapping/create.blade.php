@extends('layouts.app')

@section('title', 'Create Store-Wise Mapping')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Store-Wise Mapping</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employee-management.store-wise-mapping') }}">Store-Wise Mapping</a></li>
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
                    <form action="{{ route('employee-management.store-wise-mapping') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Store-Wise Employee Mapping</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Employee <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <select name="employee_id" class="form-select form-control-sm" required>
                                            <option value="">Select Employee</option>
                                            <option value="1">Rajesh Kumar</option>
                                            <option value="2">Priya Sharma</option>
                                            <option value="3">Amit Patel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Store <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <select name="store_id" class="form-select form-control-sm" required>
                                            <option value="">Select Store</option>
                                            <option value="1">Main Store - Mumbai</option>
                                            <option value="2">Branch Store - Delhi</option>
                                            <option value="3">Branch Store - Bangalore</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Role</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="role" class="form-select form-control-sm">
                                            <option value="">Select Role</option>
                                            <option value="manager">Store Manager</option>
                                            <option value="cashier">Cashier</option>
                                            <option value="sales">Sales Executive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Start Date</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="date" name="start_date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">End Date</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="date" name="end_date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Status</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="status" class="form-select form-control-sm">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Save Mapping
                                    </button>
                                    <a href="{{ route('employee-management.store-wise-mapping') }}" class="btn btn-light">
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

