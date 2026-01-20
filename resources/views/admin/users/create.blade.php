@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="mb-0">Create User</h5>
            <small class="text-muted">Add new system user</small>
        </div>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
            ← Back
        </a>
    </div>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <!-- Centered Compact Card -->
        <div class="row  justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-12">

                <div class="card shadow-sm border-0">
                    <div class="card-body p-8">

                        <!-- BASIC INFO -->
                        <h6 class="text-uppercase text-muted mb-3">Basic Info</h6>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Name</label>
                            <div class="col-md-8 pl-1">
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="Full name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Email</label>
                            <div class="col-md-8 pl-1">
                                <input type="email" name="email" class="form-control form-control-sm" placeholder="Email address" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Mobile</label>
                            <div class="col-md-8 pl-1">
                                <input type="text" name="mobile_number" class="form-control form-control-sm" placeholder="Mobile number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Status</label>
                            <div class="col-md-8 pl-1">
                                <select name="status" class="form-select form-select-sm" style="max-width: 300px;width: 300px;height: 29px;padding: 2px 13px;font-size: 14px;">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select> </div>
                        </div>

                        <hr>

                        <!-- ACCESS -->
                        <h6 class="text-uppercase text-muted mb-3">Access</h6>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Role</label>
                            <div class="col-md-8 pl-1">
                                <select name="role_id" class="form-select form-select-sm" style="max-width: 300px;width: 300px;height: 29px;padding: 2px 13px;font-size: 14px;" required>
                                    <option value="">Select role</option>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ $role->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Store</label>
                            <div class="col-md-8 pl-1">
                                <select name="store_id" class="form-select form-select-sm" style="max-width: 300px;width: 300px;height: 29px;padding: 2px 13px;font-size: 14px;">
                                    <option value="">All Stores</option>
                                    @foreach($stores as $store)
                                    <option value="{{ $store->id }}">
                                        {{ $store->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>

                        <!-- SECURITY -->
                        <h6 class="text-uppercase text-muted mb-3">Security</h6>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Password</label>
                            <div class="col-md-8 pl-1">
                                <input type="password" name="password" class="form-control form-control-sm" placeholder="Password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="form-label text-md col-md-4 custom-label">Confirm Password</label>
                            <div class="col-md-8 pl-1">
                                <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Confirm password" required>
                            </div>
                        </div>


                    </div>

                    <!-- FOOTER -->
                    <div class="card-footer bg-light d-flex justify-content-end gap-2 py-3 px-4">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-4" onclick="window.location.href='{{ route('users.index') }}'">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-sm btn-success px-4">
                            Save
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </form>
</div>
@endsection
