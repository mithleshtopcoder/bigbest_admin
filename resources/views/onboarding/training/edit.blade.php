@extends('layouts.app')
@section('title', 'Edit Training Module')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Training Module</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.training') }}" class="text-decoration-none">Training Modules</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Training Module Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('onboarding.training.update', $module->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-10 mx-auto">
                        <div class="form-group row">
                            <label class="form-label col-md-2">Title <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control" name="title" value="{{ old('title', $module->title) }}" required>
                            </div>
                            <label class="form-label col-md-2">Module Type</label>
                            <div class="col-md-4 ps-1">
                                <select class="form-control" name="module_type">
                                    <option value="video" {{ old('module_type', $module->module_type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="document" {{ old('module_type', $module->module_type) == 'document' ? 'selected' : '' }}>Document</option>
                                    <option value="quiz" {{ old('module_type', $module->module_type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="assignment" {{ old('module_type', $module->module_type) == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Duration (Minutes)</label>
                            <div class="col-md-4 ps-1">
                                <input type="number" class="form-control" name="duration_minutes" value="{{ old('duration_minutes', $module->duration_minutes) }}" min="0">
                            </div>
                            <label class="form-label col-md-2">File</label>
                            <div class="col-md-4 ps-1">
                                @if($module->file_path)
                                    <div class="mb-2">
                                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Current File</a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="file_path" accept=".pdf,.doc,.docx,.mp4,.avi,.mov">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Department</label>
                            <div class="col-md-4 ps-1">
                                <select class="form-control" name="department_id">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $module->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="form-label col-md-2">Designation</label>
                            <div class="col-md-4 ps-1">
                                <select class="form-control" name="designation_id">
                                    <option value="">All Designations</option>
                                    @foreach($designations as $desg)
                                        <option value="{{ $desg->id }}" {{ old('designation_id', $module->designation_id) == $desg->id ? 'selected' : '' }}>{{ $desg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2">Video URL</label>
                            <div class="col-md-4 ps-1">
                                <input type="url" class="form-control" name="video_url" value="{{ old('video_url', $module->video_url) }}" placeholder="https://...">
                            </div>
                            <label class="form-label col-md-2">Status</label>
                            <div class="col-md-4 ps-1">
                                <select class="form-control" name="status">
                                    <option value="draft" {{ old('status', $module->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $module->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="archived" {{ old('status', $module->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                </select>   
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 120px;">Description</label>
                            <div class="col-md-10 ps-1">
                                <textarea class="form-control" name="description" style="height: 120px;" rows="3">{{ old('description', $module->description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="form-label col-md-2" style="height: 140px;">Content</label>
                            <div class="col-md-10 ps-1">
                                <textarea class="form-control" name="content" style="height: 140px;" rows="5">{{ old('content', $module->content) }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('onboarding.training') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
