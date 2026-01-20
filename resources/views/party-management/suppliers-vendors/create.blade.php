@extends('layouts.app')
@section('title', 'Create ' . ucfirst($type))
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create New {{ ucfirst($type) }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('party-management.suppliers-vendors.index', ['type' => $type]) }}" class="text-decoration-none">{{ ucfirst($type) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('party-management.suppliers-vendors.index', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Create New {{ ucfirst($type) }}</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <form action="{{ route('party-management.suppliers-vendors.store', ['type' => $type]) }}" method="POST" id="supplierForm">
                @csrf
                <div class="row row-p">
                    <div class="col-12">
                        <!-- Basic Information -->
                        <div class="form-group row">
                            <label for="name" class="form-label col-md-2">{{ ucfirst($type) }} Name <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="code" class="form-label col-md-2">{{ ucfirst($type) }} Code</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Auto-generated if left empty">
                                <small class="text-muted">Leave empty to auto-generate</small>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="contact_person" class="form-label col-md-2">Contact Person</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="email" class="form-label col-md-2">Email</label>
                            <div class="col-md-4 ps-1">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="phone" class="form-label col-md-2">Phone</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="status" class="form-label col-md-2">Status <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="address" class="form-label col-md-2">Address</label>
                            <div class="col-md-10 ps-1">
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="form-label col-md-2">City</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}">
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="state" class="form-label col-md-2">State</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}">
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pincode" class="form-label col-md-2">Pincode</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}">
                                @error('pincode')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="country" class="form-label col-md-2">Country</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', 'India') }}">
                                @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tax Information -->
                        <div class="form-group row">
                            <label for="gst_number" class="form-label col-md-2">GST Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('gst_number') is-invalid @enderror" id="gst_number" name="gst_number" value="{{ old('gst_number') }}" maxlength="15">
                                @error('gst_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="pan_number" class="form-label col-md-2">PAN Number</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('pan_number') is-invalid @enderror" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" maxlength="10">
                                @error('pan_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="payment_terms" class="form-label col-md-2">Payment Terms</label>
                            <div class="col-md-10 ps-1">
                                <textarea class="form-control @error('payment_terms') is-invalid @enderror" id="payment_terms" name="payment_terms" rows="3" placeholder="e.g., Net 30, COD, Advance payment required">{{ old('payment_terms') }}</textarea>
                                @error('payment_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer py-2">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('party-management.suppliers-vendors.index', ['type' => $type]) }}" class="btn btn-sm btn-secondary">Cancel</a>
                <button type="submit" form="supplierForm" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-2"></i>Save {{ ucfirst($type) }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
