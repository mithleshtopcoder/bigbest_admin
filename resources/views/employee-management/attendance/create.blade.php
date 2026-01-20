@extends('layouts.app')

@section('title', 'Create Attendance')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Attendance</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.attendance') }}" class="text-decoration-none">Attendance</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.attendance') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Manual Attendance</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.attendance.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Employee <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="employee_profile_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_profile_id') == $emp->id ? 'selected' : '' }}>
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
                            <label class="col-md-4 form-label">Attendance Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                                @error('attendance_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Punch In Time</label>
                            <div class="col-md-8">
                                <input type="time" name="punch_in" class="form-control" value="{{ old('punch_in') }}">
                                @error('punch_in')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Punch Out Time</label>
                            <div class="col-md-8">
                                <input type="time" name="punch_out" class="form-control" value="{{ old('punch_out') }}">
                                @error('punch_out')
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
                                    <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="half_day" {{ old('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                    <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                    <option value="holiday" {{ old('status') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="weekend" {{ old('status') == 'weekend' ? 'selected' : '' }}>Weekend</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Remarks</label>
                            <div class="col-md-8">
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Attendance
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
