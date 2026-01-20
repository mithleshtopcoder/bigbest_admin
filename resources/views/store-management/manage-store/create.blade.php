@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create New</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store-management.manage-store') }}" class="text-decoration-none">Manage Store</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('store-management.manage-store') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="filterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="StoreFilter" checked>
                                <label class="form-check-label" for="StoreFilter">Store</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateFilter" checked>
                                <label class="form-check-label" for="DateFilter">Date</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="PaymentFilter" checked>
                                <label class="form-check-label" for="PaymentFilter">Payment Method</label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Bills data is now passed from controller --}}
<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Create New Store</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <form action="{{ route('store-management.manage-store.store') }}" method="POST" id="storeForm">
                @csrf
                <div class="row row-p">
                    <div class="col-12">
                        <!-- Store Basic Information -->
                        <div class="form-group row">
                            <label for="name" class="form-label col-md-2">Store Name <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="code" class="form-label col-md-2">Store Code <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="form-label col-md-2">Email</label>
                            <div class="col-md-4 ps-1">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="phone" class="form-label col-md-2">Phone</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
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

                        <!-- Location Information -->
                        <div class="form-group row">
                            <label for="latitude" class="form-label col-md-2">Latitude</label>
                            <div class="col-md-4 ps-1">
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="longitude" class="form-label col-md-2">Longitude</label>
                            <div class="col-md-4 ps-1">
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Manager Information -->
                        <div class="form-group row">
                            <label for="manager_name" class="form-label col-md-2">Manager Name</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('manager_name') is-invalid @enderror" id="manager_name" name="manager_name" value="{{ old('manager_name') }}">
                                @error('manager_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="manager_phone" class="form-label col-md-2">Manager Phone</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control @error('manager_phone') is-invalid @enderror" id="manager_phone" name="manager_phone" value="{{ old('manager_phone') }}">
                                @error('manager_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Store Settings -->
                        <div class="form-group row">
                            <label for="status" class="form-label col-md-2">Status <span class="text-danger">*</span></label>
                            <div class="col-md-4 ps-1">
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="is_online" class="form-label col-md-2">Online Store</label>
                            <div class="col-md-4 ps-1">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input @error('is_online') is-invalid @enderror" type="checkbox" id="is_online" name="is_online" value="1" {{ old('is_online', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_online">Enable online ordering</label>
                                </div>
                                @error('is_online')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="opening_time" class="form-label col-md-2">Opening Time</label>
                            <div class="col-md-4 ps-1">
                                <input type="time" class="form-control @error('opening_time') is-invalid @enderror" id="opening_time" name="opening_time" value="{{ old('opening_time') }}">
                                @error('opening_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <label for="closing_time" class="form-label col-md-2">Closing Time</label>
                            <div class="col-md-4 ps-1">
                                <input type="time" class="form-control @error('closing_time') is-invalid @enderror" id="closing_time" name="closing_time" value="{{ old('closing_time') }}">
                                @error('closing_time')
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
                <a href="{{ route('store-management.manage-store') }}" class="btn btn-sm btn-secondary">Cancel</a>
                <button type="submit" form="storeForm" class="btn btn-sm btn-primary">
                    <i class="bi bi-save me-2"></i>Save Store
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
