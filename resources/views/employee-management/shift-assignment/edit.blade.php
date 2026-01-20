@extends('layouts.app')

@section('title', 'Edit Shift Assignment')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Shift Assignment</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.shift-assignment') }}" class="text-decoration-none">Shift Assignment</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.shift-assignment') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Shift Assignment</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.shift-assignment.update', $assignment->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Employee <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="employee_profile_id" id="employeeSelect" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_profile_id', $assignment->employee_profile_id) == $emp->id ? 'selected' : '' }}>
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
                            <label class="col-md-4 form-label">Shift <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="shift_id" id="shiftSelect" required>
                                    <option value="">Select Shift</option>
                                    @foreach($shifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('shift_id', $assignment->shift_id) == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }} ({{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $shift->end_time)->format('h:i A') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Effective From <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="effective_from" class="form-control" value="{{ old('effective_from', $assignment->effective_from ? \Carbon\Carbon::parse($assignment->effective_from)->format('Y-m-d') : '') }}" required>
                                @error('effective_from')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Effective To</label>
                            <div class="col-md-8">
                                <input type="date" name="effective_to" class="form-control" value="{{ old('effective_to', $assignment->effective_to ? \Carbon\Carbon::parse($assignment->effective_to)->format('Y-m-d') : '') }}" placeholder="Leave empty for ongoing">
                                <small class="text-muted">Leave empty if assignment is ongoing</small>
                                @error('effective_to')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Status</label>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $assignment->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                @error('is_active')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Remarks</label>
                            <div class="col-md-8">
                                <textarea name="remarks" class="form-control" rows="4" placeholder="Enter any remarks">{{ old('remarks', $assignment->remarks) }}</textarea>
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
                            <i class="bi bi-save me-2"></i>Update Assignment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

