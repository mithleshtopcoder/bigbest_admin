@extends('layouts.app')

@section('title', 'Edit Leave')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Leave</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.leave') }}" class="text-decoration-none">Leave</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.leave') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Leave</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.leave.update', $leave->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Employee <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="employee_profile_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_profile_id', $leave->employee_profile_id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->user->name ?? 'N/A' }} ({{ $emp->employee_code ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_profile_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Leave Type</label>
                            <div class="col-md-8">
                                <input type="text" name="leave_type" class="form-control" placeholder="e.g., Casual, Sick, Annual" value="{{ old('leave_type', $leave->leave_type) }}">
                                @error('leave_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">From Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="from_date" class="form-control" value="{{ old('from_date', $leave->from_date ? \Carbon\Carbon::parse($leave->from_date)->format('Y-m-d') : '') }}" required>
                                @error('from_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">To Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="to_date" class="form-control" value="{{ old('to_date', $leave->to_date ? \Carbon\Carbon::parse($leave->to_date)->format('Y-m-d') : '') }}" required>
                                @error('to_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Status <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="status" required>
                                    <option value="pending" {{ old('status', $leave->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $leave->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $leave->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ old('status', $leave->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Reason</label>
                            <div class="col-md-8">
                                <textarea name="reason" class="form-control" rows="4" placeholder="Enter reason for leave">{{ old('reason', $leave->reason) }}</textarea>
                                @error('reason')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Approval Remarks</label>
                            <div class="col-md-8">
                                <textarea name="approval_remarks" class="form-control" rows="3" placeholder="Enter approval remarks">{{ old('approval_remarks', $leave->approval_remarks) }}</textarea>
                                @error('approval_remarks')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Leave
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

