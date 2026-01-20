@extends('layouts.app')
@section('title', 'Edit Store')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Store</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store-management.manage-store') }}" class="text-decoration-none">Manage Store</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Store</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('store-management.manage-store') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Edit Store - {{ $store->name }}</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-3" id="storeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                        <i class="bi bi-info-circle me-2"></i>Store Details
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                        <i class="bi bi-people me-2"></i>Assigned Users
                        @if($assignedUsers->count() > 0)
                        <span class="badge bg-primary ms-2">{{ $assignedUsers->count() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="assign-tab" data-bs-toggle="tab" data-bs-target="#assign" type="button" role="tab">
                        <i class="bi bi-person-plus me-2"></i>Assign Users
                    </button>
                </li>
            </ul>

            <!-- Tabs Content -->
            <div class="tab-content" id="storeTabsContent">
                <!-- Tab 1: Store Details -->
                <div class="tab-pane fade show active" id="details" role="tabpanel">
                    <form action="{{ route('store-management.manage-store.update', $store->id) }}" method="POST" id="storeForm">
                        @csrf
                        @method('PUT')
                        <div class="row row-p">
                            <div class="col-12">
                                <!-- Store Basic Information -->
                                <div class="form-group row">
                                    <label for="name" class="form-label col-md-2">Store Name <span class="text-danger">*</span></label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                                        @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="code" class="form-label col-md-2">Store Code <span class="text-danger">*</span></label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $store->code) }}" required>
                                        @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="form-label col-md-2">Email</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $store->email) }}">
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="phone" class="form-label col-md-2">Phone</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $store->phone) }}">
                                        @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="address" class="form-label col-md-2">Address</label>
                                    <div class="col-md-10 ps-1">
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $store->address) }}</textarea>
                                        @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="city" class="form-label col-md-2">City</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $store->city) }}">
                                        @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="state" class="form-label col-md-2">State</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state', $store->state) }}">
                                        @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="pincode" class="form-label col-md-2">Pincode</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('pincode') is-invalid @enderror" id="pincode" name="pincode" value="{{ old('pincode', $store->pincode) }}">
                                        @error('pincode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="country" class="form-label col-md-2">Country</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $store->country ?? 'India') }}">
                                        @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Location Information -->
                                <div class="form-group row">
                                    <label for="latitude" class="form-label col-md-2">Latitude</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $store->latitude) }}" readonly>
                                        @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="longitude" class="form-label col-md-2">Longitude</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $store->longitude) }}" readonly>
                                        @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Manager Information -->
                                <div class="form-group row">
                                    <label for="manager_name" class="form-label col-md-2">Manager Name</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('manager_name') is-invalid @enderror" id="manager_name" name="manager_name" value="{{ old('manager_name', $store->manager_name) }}">
                                        @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="manager_phone" class="form-label col-md-2">Manager Phone</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="text" class="form-control @error('manager_phone') is-invalid @enderror" id="manager_phone" name="manager_phone" value="{{ old('manager_phone', $store->manager_phone) }}">
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
                                            <option value="active" {{ old('status', $store->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $store->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="maintenance" {{ old('status', $store->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                        @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="is_online" class="form-label col-md-2">Online Store</label>
                                    <div class="col-md-4 ps-1">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input @error('is_online') is-invalid @enderror" type="checkbox" id="is_online" name="is_online" value="1" {{ old('is_online', $store->is_online) ? 'checked' : '' }}>
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
                                        <input type="time" class="form-control @error('opening_time') is-invalid @enderror" id="opening_time" name="opening_time" value="{{ old('opening_time', $store->opening_time ? \Carbon\Carbon::parse($store->opening_time)->format('H:i') : '') }}">
                                        @error('opening_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label for="closing_time" class="form-label col-md-2">Closing Time</label>
                                    <div class="col-md-4 ps-1">
                                        <input type="time" class="form-control @error('closing_time') is-invalid @enderror" id="closing_time" name="closing_time" value="{{ old('closing_time', $store->closing_time ? \Carbon\Carbon::parse($store->closing_time)->format('H:i') : '') }}">
                                        @error('closing_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('store-management.manage-store') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" form="storeForm" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Update Store
                        </button>
                    </div>
                </div>

                <!-- Tab 2: Assigned Users List -->
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    {{-- <th class="text-end">Actions</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignedUsers as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_number ?? '-' }}</td>
                                    <td>
                                        @if($user->status)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    {{-- <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Remove from Store" onclick="removeUser({{ $store->id }}, {{ $user->id }})">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </td> --}}
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">No users assigned to this store.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab 3: Assign Users -->
                <div class="tab-pane fade" id="assign" role="tabpanel">
                    <!-- Add User Section -->
                    <div class="row mb-3 ps-2">
                            <label class="form-label col-md-2">Select User to Assign</label>
                        <div class="col-md-6">
                            <select id="userSelect" class="form-control select2" style="width: 100%;">
                                <option value="">-- Select a user --</option>
                                @foreach($allUsers as $user)
                                @php
                                $isAssigned = $assignedUsers->contains('id', $user->id);
                                @endphp
                                @if(!$isAssigned)
                                <option value="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-mobile="{{ $user->mobile_number ?? '-' }}" data-status="{{ $user->status }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 align-items-end">
                            <button type="button" class="btn btn-primary btn-sm" id="addUserBtn">
                                <i class="bi bi-person-plus me-2"></i>Add User to Store
                            </button>
                        </div>
                    </div>

                    <!-- Assigned Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="assignedUsersTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assignedUsers as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile_number ?? '-' }}</td>
                                    <td>
                                        @if($user->status)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Remove from Store" onclick="removeUser({{ $store->id }}, {{ $user->id }})">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr id="emptyRow">
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-muted mb-0">No users assigned to this store.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Select2 for user dropdown
        $('#userSelect').select2({
            theme: 'bootstrap-5',
            placeholder: 'Search and select a user...',
            allowClear: true
        });
    });

    // Add user to store
    $('#addUserBtn').on('click', function() {
        const userId = $('#userSelect').val();
        const selectedOption = $('#userSelect option:selected');
        
        if (!userId) {
            alert('Please select a user to add.');
            return;
        }

        const userName = selectedOption.data('name');
        const userEmail = selectedOption.data('email');
        const userMobile = selectedOption.data('mobile');
        const userStatus = selectedOption.data('status');

        $.ajax({
            url: '{{ route("store-management.manage-store.add-user", $store->id) }}',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    // Remove empty row if exists
                    $('#emptyRow').remove();
                    
                    // Add new row to table
                    const statusBadge = response.user.status 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                    
                    const newRow = `
                        <tr data-user-id="${response.user.id}">
                            <td>${response.user.id}</td>
                            <td>${response.user.name}</td>
                            <td>${response.user.email}</td>
                            <td>${response.user.mobile_number || '-'}</td>
                            <td>${statusBadge}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-link text-danger p-1" title="Remove from Store" onclick="removeUser({{ $store->id }}, ${response.user.id})">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    $('#assignedUsersTable tbody').append(newRow);
                    
                    // Remove option from dropdown
                    selectedOption.remove();
                    $('#userSelect').val(null).trigger('change');
                    
                    // Update badge count in tab
                    const currentCount = parseInt($('#users-tab .badge').text()) || 0;
                    $('#users-tab .badge').text(currentCount + 1).show();
                    
                    // Show success message
                    alert(response.message || 'User added successfully.');
                } else {
                    alert(response.message || 'Error adding user.');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Error adding user. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    function removeUser(storeId, userId) {
        if (!confirm('Are you sure you want to remove this user from the store?')) {
            return;
        }

        $.ajax({
            url: '{{ route("store-management.manage-store.remove-user", [":storeId", ":userId"]) }}'
                .replace(':storeId', storeId)
                .replace(':userId', userId),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Remove row from table
                    $(`tr[data-user-id="${userId}"]`).remove();
                    
                    // If table is empty, add empty row
                    if ($('#assignedUsersTable tbody tr').length === 0) {
                        $('#assignedUsersTable tbody').html(`
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-muted mb-0">No users assigned to this store.</p>
                                </td>
                            </tr>
                        `);
                    }
                    
                    // Reload page to refresh dropdown options
                    location.reload();
                } else {
                    alert(response.message || 'Error removing user.');
                }
            },
            error: function(xhr) {
                alert('Error removing user. Please try again.');
            }
        });
    }

</script>
@endsection
