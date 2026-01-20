@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Roles</h5>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            Add Role
        </a>
    </div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Role Name</th>
                    <th>Total Users</th>
                    <th>Total Permissions</th>
                    <th>Status</th>
                    <th width="160">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>

                    <td>
                        {{ $role->users->count() }}
                    </td>

                    <td>
                        {{ $role->permissions->count() }}
                    </td>

                    <td>
                        <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }}">
                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
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
                    <td colspan="5" class="text-center text-muted">
                        No roles found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
