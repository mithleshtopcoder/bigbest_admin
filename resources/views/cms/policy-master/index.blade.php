@extends('layouts.app')

@section('title', 'Policy Master')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Policy Master - {{ ucfirst($type) }}</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">CMS</li>
            <li class="breadcrumb-item">Policy Master</li>
            <li class="breadcrumb-item">{{ ucfirst($type) }}</li>
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
        <div class="d-md-none d-flex align-items-center">
            <a href="javascript:void(0)" class="page-header-right-open-toggle">
                <i class="feather-align-right fs-20"></i>
            </a>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">{{ ucfirst($type) }} Policy</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <input type="hidden" name="policy_type" value="{{ $type }}">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ ucfirst($type) }} Policy" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label">Policy Content <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="content" name="content" rows="15" required>This is the {{ $type }} policy content. Please update this with your actual policy information.

Add your policy details here...</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-2"></i>Save Policy
                            </button>
                            <button type="reset" class="btn btn-light">
                                <i class="feather-refresh-cw me-2"></i>Reset
                            </button>
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

