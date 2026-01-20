@extends('layouts.app')

@section('title', 'Edit Sub Category')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Edit Sub Category</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('manage-product.sub-category.index') }}">Sub-Categories</a>
            </li>
            <li class="breadcrumb-item">Edit</li>
        </ul>
    </div>
</div>
<!-- [ page-header ] end -->

<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">

                    <form action="{{ route('manage-product.sub-category.update', $subCategory->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-8">

                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Sub Category Information</h5>
                                </div>

                                <!-- Category -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <select name="category_id" class="form-select form-control-sm" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Sub Category Name -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Sub Category Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $subCategory->name) }}" required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Description
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <textarea name="description" class="form-control form-control-sm" rows="4">{{ old('description', $subCategory->description) }}</textarea>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Image
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                </div>

                                <!-- Icon -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Icon
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="icon" class="form-control form-control-sm" value="{{ old('icon', $subCategory->icon) }}">
                                    </div>
                                </div>

                                <!-- Label -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Label
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="label" class="form-control form-control-sm" value="{{ old('label', $subCategory->label) }}">
                                    </div>
                                </div>

                                <!-- Sort Order -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Sort Order
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', $subCategory->sort_order) }}">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">
                                        Status
                                    </label>
                                    <div class="col-md-9 pl-1">
                                        <select name="is_active" class="form-select form-control-sm">
                                            <option value="1" {{ $subCategory->is_active ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ !$subCategory->is_active ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>
                                        Update Sub Category
                                    </button>
                                    <a href="{{ route('manage-product.sub-category.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection
