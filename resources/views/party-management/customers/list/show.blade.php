@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex align-items-center gap-2" style="align-items: baseline;">
            <h1 class="page-title mb-0">Customer Details</h1>

            {{-- Back Button --}}
            <a href="{{ route('party-management.customers.list') }}" class="btn btn-sm btn-outline-secondary ms-3">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('party-management.customers.list') }}" class="text-decoration-none">Customers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $customer->full_name }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Customer Info</h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th>Name:</th>
                    <td>{{ $customer->full_name }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ $customer->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Phone:</th>
                    <td>{{ $customer->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        @if($customer->status === 'active')
                        <span class="badge bg-success">Active</span>
                        @else
                        <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Total Orders:</th>
                    <td>{{ $customer->orders->count() }}</td>
                </tr>
                <tr>
                    <th>Total Spent:</th>
                    <td>₹{{ number_format($customer->orders->sum('total_amount'), 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Addresses</h5>
        </div>
        <div class="card-body">
            @if($customer->addresses->isEmpty())
            <p class="text-muted">No addresses found.</p>
            @else
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Pincode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->addresses as $address)
                    <tr>
                        <td>{{ ucfirst($address->type) }}</td>
                        <td>{{ $address->address }}</td>
                        <td>{{ $address->city }}</td>
                        <td>{{ $address->state }}</td>
                        <td>{{ $address->pincode }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
