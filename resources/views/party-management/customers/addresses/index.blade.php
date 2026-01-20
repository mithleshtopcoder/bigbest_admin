@extends('layouts.app')

@section('title', 'Customer Addresses')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
        <h5 class="mb-0">Customer Addresses</h5>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Party Management</li>
                <li class="breadcrumb-item">Customers</li>
                <li class="breadcrumb-item active">Addresses</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('party-management.customers.addresses.create') }}" class="btn btn-sm btn-primary" title="Create Address">
            <i class="bi bi-plus-circle"></i> Create Address
        </a>
        <a href="javascript:void(0)" class="btn btn-sm btn-warning" title="Refresh"><i class="bi bi-arrow-clockwise"></i></a>
        <a href="javascript:void(0)" class="btn btn-sm btn-success" title="Fullscreen"><i class="bi bi-arrows-fullscreen"></i></a>
    </div>
</div>

<div class="main-body mt-3">
    <div class="card">
        <div class="card-header py-2">
            <h5 class="card-title mb-0">Customer Addresses List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Address Type</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Pincode</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($addresses as $address)
                        <tr>
                            <td>#ADD-{{ str_pad($address->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $address->customer?->full_name ?? 'N/A' }}</td>
                            <td>
                                @if($address->type === 'home')
                                <span class="badge bg-primary">Home</span>
                                @elseif($address->type === 'office')
                                <span class="badge bg-info text-dark">Office</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($address->type) }}</span>
                                @endif
                            </td>
                            <td>
                                {{ $address->address }}
                                @if($address->landmark)
                                <br><small class="text-muted">{{ $address->landmark }}</small>
                                @endif
                            </td>
                            <td>{{ $address->city }}</td>
                            <td>{{ $address->state }}</td>
                            <td>{{ $address->pincode }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('party-management.customers.addresses.view', $address->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('party-management.customers.addresses.delete', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No addresses found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            {{ $addresses->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection

@section('styles')
<style>
    .table td .btn {
        padding: 0.375rem 0.5rem;
    }

</style>
@endsection
