@extends('layouts.app')

@section('title', 'Edit Onboarding Process')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Onboarding Process</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.process') }}" class="text-decoration-none">Onboarding Process</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Onboarding Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('onboarding.process.update', $process->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="col-md-8 mx-auto">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Candidate</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="candidate_id" id="candidate_id">
                                    <option value="">Select Candidate</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ old('candidate_id', $process->candidate_id) == $candidate->id ? 'selected' : '' }}>{{ $candidate->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Employee Profile</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="employee_profile_id" id="employee_profile_id">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_profile_id', $process->employee_profile_id) == $employee->id ? 'selected' : '' }}>{{ $employee->user->name ?? 'N/A' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">User</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="user_id" id="user_id">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $process->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Joining Date <span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="joining_date" value="{{ old('joining_date', $process->joining_date?->format('Y-m-d')) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Completion Date</label>
                            <div class="col-md-7">
                                <input type="date" class="form-control" name="completion_date" value="{{ old('completion_date', $process->completion_date?->format('Y-m-d')) }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Status</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="status">
                                    <option value="pending" {{ old('status', $process->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in-progress" {{ old('status', $process->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status', $process->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on-hold" {{ old('status', $process->status) == 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="cancelled" {{ old('status', $process->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="form-label col-md-5">Assigned To</label>
                            <div class="col-md-7">
                                <select class="form-select form-control" name="assigned_to">
                                    <option value="">Select</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('assigned_to', $process->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-3" style="height: 70px;">Notes</label>
                            <div class="col-md-9">
                                <textarea class="form-control" style="height: 70px;" name="notes" rows="3">{{ old('notes', $process->notes) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="form-label col-md-3">Checklist Templates</label>
                            <div class="col-md-9">
                                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                    @if($checklistTemplates->count() > 0)
                                        @foreach($checklistTemplates as $template)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="checklist_templates[]" value="{{ $template->id }}" id="template_{{ $template->id }}" {{ in_array($template->id, old('checklist_templates', $selectedTemplates)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="template_{{ $template->id }}">
                                                    {{ $template->task_name }}
                                                    @if($template->is_mandatory)
                                                        <span class="badge bg-danger ms-2">Mandatory</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted mb-0">No active checklist templates available.</p>
                                    @endif
                                </div>
                                <small class="text-muted">Select which checklist templates to include. Note: Adding new templates will create new checklist items.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('onboarding.process') }}" class="btn btn-light">Cancel</a>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
