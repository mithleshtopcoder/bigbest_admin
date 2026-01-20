@extends('layouts.app')

@section('title', 'Edit Job Opening')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Job Opening</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('recruitment.job-openings') }}" class="text-decoration-none">Job Openings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Job Opening Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment.job-openings.update', $jobOpening->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row ps-2">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Job Title <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="job_title" value="{{ old('job_title', $jobOpening->job_title) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 80px;">Job Description <span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" style="height: 80px;" rows="5" name="job_description" required>{{ old('job_description', $jobOpening->job_description) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-4" style="height: 90px;">Requirements</label>
                            <div class="col-md-8">
                                <textarea class="form-control" style="height: 90px;" rows="4" name="requirements">{{ old('requirements', $jobOpening->requirements) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-4" style="height: 90px;">Responsibilities</label>
                            <div class="col-md-8">
                                <textarea class="form-control" style="height: 90px;" rows="4" name="responsibilities">{{ old('responsibilities', $jobOpening->responsibilities) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Department</label>
                            <div class="col-md-8">
                                <select class="form-select form-control" name="department_id">
                                    <option value="">Select</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $jobOpening->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Designation</label>
                            <div class="col-md-8">
                                <select class="form-select form-control" name="designation_id">
                                    <option value="">Select</option>
                                    @foreach($designations as $desg)
                                        <option value="{{ $desg->id }}" {{ old('designation_id', $jobOpening->designation_id) == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Store</label>
                            <div class="col-md-8">
                                <select class="form-select form-control" name="store_id">
                                    <option value="">Select</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ old('store_id', $jobOpening->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Emp. Type</label>
                            <div class="col-md-8">
                                <select class="form-select form-control" name="employment_type">
                                    <option value="full-time" {{ old('employment_type', $jobOpening->employment_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part-time" {{ old('employment_type', $jobOpening->employment_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type', $jobOpening->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="internship" {{ old('employment_type', $jobOpening->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                    <option value="temporary" {{ old('employment_type', $jobOpening->employment_type) == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Min Salary</label>
                            <div class="col-md-8">
                                <input type="number" step="0.01" class="form-control" name="min_salary" value="{{ old('min_salary', $jobOpening->min_salary) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Max Salary</label>
                            <div class="col-md-8">
                                <input type="number" step="0.01" class="form-control" name="max_salary" value="{{ old('max_salary', $jobOpening->max_salary) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Experience</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="experience_required" value="{{ old('experience_required', $jobOpening->experience_required) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Qualification</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="qualification_required" value="{{ old('qualification_required', $jobOpening->qualification_required) }}" placeholder="e.g. Bachelor's Degree in Computer Science">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Total Vacancies <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="no_of_vacancies" min="1" value="{{ old('no_of_vacancies', $jobOpening->no_of_vacancies) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Application Deadline</label>
                            <div class="col-md-8">
                                <input type="date" class="form-control" name="application_deadline" value="{{ old('application_deadline', $jobOpening->application_deadline) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-4">Application Status</label>
                            <div class="col-md-8">
                                <select class="form-select form-control" name="status">
                                    <option value="draft" {{ old('status', $jobOpening->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $jobOpening->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="closed" {{ old('status', $jobOpening->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    <option value="cancelled" {{ old('status', $jobOpening->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('recruitment.job-openings') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
