@extends('layouts.app')

@section('title', 'Create Unit & Attribute')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create Unit & Attribute</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('manage-product.unit-attribute.index') }}">Units & Attributes</a></li>
            <li class="breadcrumb-item">Create</li>
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <div class="d-flex d-md-none">
                <a href="javascript:void(0)" class="page-header-right-close-toggle py-2">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('manage-product.unit-attribute.index') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Unit & Attribute Information</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Type <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <select name="type" class="form-select form-control-sm" required>
                                            <option value="">Select Type</option>
                                            <option value="unit">Unit</option>
                                            <option value="attribute">Attribute</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Name <span class="text-danger">*</span></label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Code</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="code" class="form-control form-control-sm" placeholder="Enter code">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Symbol</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="text" name="symbol" class="form-control form-control-sm" placeholder="Enter symbol (e.g., kg, g, pcs)">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Conversion Factor</label>
                                    <div class="col-md-9 pl-1">
                                        <input type="number" name="conversion_factor" class="form-control form-control-sm" placeholder="Enter conversion factor" step="0.0001" value="1">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Base Unit</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="base_unit_id" class="form-select form-control-sm">
                                            <option value="">Select Base Unit</option>
                                            <option value="1">Gram</option>
                                            <option value="2">Kilogram</option>
                                            <option value="3">Piece</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Description</label>
                                    <div class="col-md-9 pl-1">
                                        <textarea name="description" class="form-control form-control-sm" rows="4" placeholder="Enter description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-3 custom-label">Status</label>
                                    <div class="col-md-9 pl-1">
                                        <select name="status" class="form-select form-control-sm">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Save Unit & Attribute
                                    </button>
                                    <a href="{{ route('manage-product.unit-attribute.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Cancel
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

