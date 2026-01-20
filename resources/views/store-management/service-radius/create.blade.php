@extends('layouts.app')
@section('title', 'Create Service Area')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Service Area</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store-management.service-radius') }}" class="text-decoration-none">Service Radius</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('store-management.service-radius') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Create Service Area</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <form action="{{ route('store-management.service-radius.store') }}" method="POST" id="serviceAreaForm">
                @csrf
                <div class="row row-p">
                    <div class="col-12">
                        <div class="form-group row">
                            <label for="store_id" class="form-label col-md-2">Store <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <select class="form-select @error('store_id') is-invalid @enderror" id="store_id" name="store_id" required>
                                    <option value="">Select Store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }} ({{ $store->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('store_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="area_name" class="form-label col-md-2">Area Name <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('area_name') is-invalid @enderror" id="area_name" name="area_name" value="{{ old('area_name') }}" placeholder="e.g., Downtown Zone" required>
                                @error('area_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="city" class="form-label col-md-2">City</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="Enter city name">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="state" class="form-label col-md-2">State</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" placeholder="Enter state name">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="pincode" class="form-label col-md-2">Pincode</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode') }}" placeholder="e.g., 400001" maxlength="10">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="radius_km" class="form-label col-md-2">Service Radius (KM) <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="number" class="form-control @error('radius_km') is-invalid @enderror" id="radius_km" name="radius_km" value="{{ old('radius_km', 10) }}" min="1" max="100" required>
                                @error('radius_km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="min_order_amount" class="form-label col-md-2">Min Order Amount (₹)</label>
                            <div class="col-md-4 ps-1">
                                <input type="number" step="0.01" class="form-control @error('min_order_amount') is-invalid @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" min="0">
                                @error('min_order_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="delivery_charge" class="form-label col-md-2">Delivery Charge (₹)</label>
                            <div class="col-md-4 ps-1">
                                <input type="number" step="0.01" class="form-control @error('delivery_charge') is-invalid @enderror" id="delivery_charge" name="delivery_charge" value="{{ old('delivery_charge', 0) }}" min="0">
                                @error('delivery_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="is_active" class="form-label col-md-2">Status</label>
                            <div class="col-md-4 ps-1">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                @error('is_active')
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
                <a href="{{ route('store-management.service-radius') }}" class="btn btn-sm btn-secondary">Cancel</a>
                <button type="submit" form="serviceAreaForm" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-2"></i>Save Service Area
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

