@extends('layouts.app')

@section('title', 'Create Salary')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Salary</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employee-management.salary') }}">Salary</a></li>
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
                    <form action="{{ route('employee-management.salary') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Salary Information</h5>
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
                                    <label class="form-label text-md col-md-3 custom-label">Month <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <input type="month" name="salary_month" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Basic Salary</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="basic_salary" class="form-control form-control-sm" placeholder="Enter basic salary" step="0.01">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Allowances</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="allowances" class="form-control form-control-sm" placeholder="Enter allowances" step="0.01" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Deductions</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="deductions" class="form-control form-control-sm" placeholder="Enter deductions" step="0.01" value="0">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Net Salary</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="net_salary" class="form-control form-control-sm" placeholder="Auto-calculated" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Payment Date</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="date" name="payment_date" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Payment Method</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="payment_method" class="form-select form-control-sm">
                                            <option value="">Select Payment Method</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Status</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="status" class="form-select form-control-sm">
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Remarks</label>
                                    <div class="col-md-9 pl-1">
                                        <textarea name="remarks" class="form-control form-control-sm" rows="3" placeholder="Enter remarks"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Save Salary
                                    </button>
                                    <a href="{{ route('employee-management.salary') }}" class="btn btn-light">
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

