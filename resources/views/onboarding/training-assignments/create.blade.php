@extends('layouts.app')

@section('title', 'Assign Training')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Assign Training</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.training-assignments') }}" class="text-decoration-none">Training Assignments</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assign</li>
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
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <form action="{{ route('onboarding.training-assignments.store') }}" method="POST">
                @csrf
                <div class="col-md-8 mx-auto">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Onboarding Process <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select form-control @error('onboarding_process_id') is-invalid @enderror" name="onboarding_process_id" id="onboarding_process_id" required>
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
                                            <option value="{{ $process->id }}" {{ old('onboarding_process_id') == $process->id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('onboarding_process_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Training Module <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <select class="form-select form-control @error('training_module_id') is-invalid @enderror" name="training_module_id" required>
                                        <option value="">Select Training Module</option>
                                        @foreach($trainingModules as $module)
                                            <option value="{{ $module->id }}" {{ old('training_module_id') == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('training_module_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Assigned Date <span class="text-danger">*</span></label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control @error('assigned_date') is-invalid @enderror" name="assigned_date" value="{{ old('assigned_date', date('Y-m-d')) }}" required>
                                    @error('assigned_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Due Date</label>
                                <div class="col-md-7">
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Assigned By</label>
                                <div class="col-md-7">
                                    <select class="form-select form-control" name="assigned_by">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_by', auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-2">
                        <button type="submit" class="btn btn-primary">Assign Training</button>
                        <a href="{{ route('onboarding.training-assignments') }}" class="btn btn-light">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
