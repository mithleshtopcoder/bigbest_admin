@extends('layouts.app')

@section('title', 'Create Shift')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Shift</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.shift') }}" class="text-decoration-none">Shift</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.shift') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Shift</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.shift.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Shift Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" placeholder="e.g., Morning Shift, Evening Shift" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Start Time <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">End Time <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Break Duration (minutes)</label>
                            <div class="col-md-8">
                                <input type="number" name="break_duration" class="form-control" placeholder="e.g., 60" value="{{ old('break_duration', 0) }}" min="0">
                                @error('break_duration')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Working Days</label>
                            <div class="col-md-8">
                                <div class="row g-2">
                                    @php
                                        $days = [
                                            'monday' => 'Monday',
                                            'tuesday' => 'Tuesday',
                                            'wednesday' => 'Wednesday',
                                            'thursday' => 'Thursday',
                                            'friday' => 'Friday',
                                            'saturday' => 'Saturday',
                                            'sunday' => 'Sunday'
                                        ];
                                        $oldWorkingDays = old('working_days', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
                                    @endphp
                                    @foreach($days as $key => $label)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $key }}" id="day_{{ $key }}" {{ in_array($key, $oldWorkingDays) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('working_days')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Description</label>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter shift description">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Status</label>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                @error('is_active')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Save Shift
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

