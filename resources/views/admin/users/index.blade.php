@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Users</h5>

        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            + Add User
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%">Name</th>
                        <th style="width: 20%">Email</th>
                        <th style="width: 20%">Role(s)</th>
                        <th style="width: 15%">Store</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 20%" class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <!-- Name -->
                        <td>{{ $user->name }}</td>

                        <!-- Email -->
                        <td>{{ $user->email }}</td>

                        <!-- Roles -->
                        <td>
                            @if($user->roles->count())
                            <span class="badge bg-info">
                                {{ $user->roles->pluck('name')->join(', ') }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>

                        <!-- Store -->
                        <td>
                            {{ $user->store->name ?? 'All Stores' }}
                        </td>

                        <!-- Status -->
                        <td>
                            <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                                {{ $user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No users found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
