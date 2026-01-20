@extends('layouts.app')

@section('title', 'Vendor List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Vendor List</h4>
        <a href="{{ route('vendors.create') }}" class="btn btn-primary">Add Vendor</a>
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
                    <th>Store Name</th>
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
                    <td>{{ $vendor->phone ?? '-' }}</td>
                    <td>{{ $vendor->store_name ?? '-' }}</td>
                    <td>
                        <span class="badge 
                                {{ $vendor->status == 'pending' ? 'bg-warning' : ($vendor->status == 'approved' ? 'bg-success' : 'bg-danger') }}">
                            {{ ucfirst($vendor->status) }}
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
@endsection
