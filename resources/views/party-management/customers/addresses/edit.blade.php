@extends('layouts.app')

@section('title', 'View Customer Address')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div class="page-header-left d-flex align-items-center">
            <h1 class="page-title mb-0">View Customer Address</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('party-management.customers.addresses') }}">Customer Addresses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Address</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer</label>
                <input type="text" class="form-control" value="{{ $address->customer->full_name }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Address Type</label>
                <input type="text" class="form-control" value="{{ ucfirst($address->type) }}" readonly>
            </div>

            <div class="col-12 mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" rows="3" readonly>{{ $address->address }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">City</label>
                <input type="text" class="form-control" value="{{ $address->city }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">State</label>
                <input type="text" class="form-control" value="{{ $address->state }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Pincode</label>
                <input type="text" class="form-control" value="{{ $address->pincode }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Landmark</label>
                <input type="text" class="form-control" value="{{ $address->landmark }}" readonly>
            </div>
        </div>

        <div class="d-flex justify-content-start mt-4 pt-2 border-top">
            <a href="{{ route('party-management.customers.addresses') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
