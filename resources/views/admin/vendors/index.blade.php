@extends('layouts.app')
@section('title', 'Manage Vendors')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Manage Vendor</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item">Vendor Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Manage Vendor</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New
            </a>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Manage Vendor List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Company Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $loop->iteration + ($vendors->currentPage()-1) * $vendors->perPage() }}</td>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>{{ $vendor->mobile_number ?? '-' }}</td>
                        <td>{{ $vendor->vendorDocument->company_name ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $vendor->status == 1 ? 'bg-success' : 'bg-warning' }}">
                                {{ $vendor->status == 1 ? 'Approved' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <!-- View Vendor -->
                            <a href="{{ route('vendors.show', $vendor->id) }}" class="btn btn-sm btn-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            <!-- Delete Vendor -->
                            <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure to delete this vendor?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No vendors found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
