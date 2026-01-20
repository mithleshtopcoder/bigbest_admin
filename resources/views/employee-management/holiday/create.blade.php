@extends('layouts.app')

@section('title', 'Create Holiday')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Holiday</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.holiday') }}" class="text-decoration-none">Holiday</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.holiday') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Holiday</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee-management.holiday.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Title <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control" placeholder="Enter holiday title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Holiday Date <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="date" name="holiday_date" class="form-control" value="{{ old('holiday_date') }}" required>
                                @error('holiday_date')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Type <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-select" name="type" required>
                                    <option value="national" {{ old('type') == 'national' ? 'selected' : '' }}>National</option>
                                    <option value="regional" {{ old('type') == 'regional' ? 'selected' : '' }}>Regional</option>
                                    <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="optional" {{ old('type') == 'optional' ? 'selected' : '' }}>Optional</option>
                                </select>
                                @error('type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row mb-3">
                            <label class="col-md-4 form-label">Description</label>
                            <div class="col-md-8">
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter holiday description">{{ old('description') }}</textarea>
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
                            <i class="bi bi-save me-2"></i>Save Holiday
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

