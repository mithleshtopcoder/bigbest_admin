@extends('layouts.app')

@section('title', 'Create Checklist Template')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Checklist Template</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('onboarding.checklist-templates') }}" class="text-decoration-none">Checklist Templates</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Template Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('onboarding.checklist-templates.store') }}" method="POST">
                @csrf
                <div class="col-md-8 mx-auto">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="form-label col-md-3">Task Name <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="task_name" value="{{ old('task_name') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="form-label col-md-3" style="height: 100px;">Description</label>
                                <div class="col-md-9">
                                    <textarea class="form-control" name="description" style="height: 100px;" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Task Category</label>
                                <div class="col-md-7">
                                    <input type="text" class="form-control" name="task_category" value="{{ old('task_category') }}" placeholder="e.g., HR, IT, Admin">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Sort Order</label>
                                <div class="col-md-7">
                                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Is Mandatory</label>
                                <div class="col-md-7">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_mandatory" value="1" {{ old('is_mandatory', true) ? 'checked' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Status</label>
                                <div class="col-md-7">
                                    <select class="form-select form-control" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-2">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ route('onboarding.checklist-templates') }}" class="btn btn-light">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
