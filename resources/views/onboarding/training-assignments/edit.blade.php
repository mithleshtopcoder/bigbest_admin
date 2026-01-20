@extends('layouts.app')

@section('title', 'Edit Training Assignment')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Training Assignment</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.training-assignments') }}" class="text-decoration-none">Training Assignments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Training Assignment Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('onboarding.training-assignments.update', $assignment->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="col-md-8 mx-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Onboarding Process <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select form-control" name="onboarding_process_id" id="onboarding_process_id" required>
                                        <option value="">Select Process</option>
                                        @foreach($processes as $process)
                                            @php
                                                $name = 'N/A';
                                                if ($process->candidate) {
                                                    $name = $process->candidate->full_name;
                                                } elseif ($process->employeeProfile && $process->employeeProfile->user) {
                                                    $name = $process->employeeProfile->user->name;
                                                } elseif ($process->user) {
                                                    $name = $process->user->name;
                                                }
                                            @endphp
                                            <option value="{{ $process->id }}" {{ old('onboarding_process_id', $assignment->onboarding_process_id) == $process->id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Training Module <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select form-control" name="training_module_id" required>
                                        <option value="">Select Training Module</option>
                                        @foreach($trainingModules as $module)
                                            <option value="{{ $module->id }}" {{ old('training_module_id', $assignment->training_module_id) == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Assigned Date <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control" name="assigned_date" value="{{ old('assigned_date', $assignment->assigned_date?->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Due Date</label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control" name="due_date" value="{{ old('due_date', $assignment->due_date?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Status</label>
                                <div class="col-md-7">
                                    <select class="form-select form-control" name="status">
                                        <option value="assigned" {{ old('status', $assignment->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="in-progress" {{ old('status', $assignment->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status', $assignment->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="overdue" {{ old('status', $assignment->status) == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Progress (%)</label>
                                <div class="col-md-7">
                                    <input type="number" class="form-control" name="progress_percentage" value="{{ old('progress_percentage', $assignment->progress_percentage) }}" min="0" max="100">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Completion Date</label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control" name="completed_date" value="{{ old('completed_date', $assignment->completed_date?->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="form-label col-md-3" style="height: 70px;">Completion Notes</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" style="height: 70px;" name="completion_notes" rows="3">{{ old('completion_notes', $assignment->completion_notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-2">
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
                        <a href="{{ route('onboarding.training-assignments') }}" class="btn btn-light">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
