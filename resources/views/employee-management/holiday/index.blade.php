@extends('layouts.app')

@section('title', 'Holiday Management')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Holiday Management</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Holiday</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.holiday.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Holiday
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Holiday List</h5>
        </div>
        <div class="card-body p-2">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="typeFilter">
                        <option value="">All Types</option>
                        <option value="national">National</option>
                        <option value="regional">Regional</option>
                        <option value="company">Company</option>
                        <option value="optional">Optional</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control form-control-sm" id="yearFilter" placeholder="Year" value="{{ date('Y') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="holidayTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">No</th>
                            <th scope="col">Title</th>
                            <th scope="col">Date</th>
                            <th scope="col">Description</th>
                            <th scope="col">Type</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const table = $('#holidayTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-management.holiday.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.type = $('#typeFilter').val();
                d.year = $('#yearFilter').val();
                d.is_active = $('#statusFilter').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'title' },
            { data: 'holiday_date' },
            { data: 'description' },
            { data: 'type', orderable: false },
            { data: 'is_active', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!row || !row.id) return '<span class="text-muted">-</span>';
                    
                    const id = row.id;
                    const isActive = row.is_active_value;
                    const editUrl = '{{ route("employee-management.holiday.edit", ":id") }}'.replace(':id', id);
                    const deleteUrl = '{{ route("employee-management.holiday.destroy", ":id") }}'.replace(':id', id);
                    const statusUrl = '{{ route("employee-management.holiday.status", ":id") }}'.replace(':id', id);
                    const csrfToken = '{{ csrf_token() }}';
                    const newStatus = isActive ? 0 : 1;
                    const statusClass = isActive ? 'text-warning' : 'text-success';
                    const statusIcon = isActive ? 'bi-x-circle' : 'bi-check-circle';
                    const statusTitle = isActive ? 'Deactivate' : 'Activate';
                    
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="' + editUrl + '" class="btn btn-sm btn-link text-primary p-1" title="Edit"><i class="bi bi-pencil"></i></a>' +
                           '<a href="javascript:void(0);" class="btn btn-sm btn-link ' + statusClass + ' p-1" onclick="changeStatus(\'' + id + '\', ' + newStatus + ', \'' + statusUrl + '\')" title="' + statusTitle + '"><i class="bi ' + statusIcon + '"></i></a>' +
                           '<form action="' + deleteUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">' +
                           '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                           '<input type="hidden" name="_method" value="DELETE">' +
                           '<button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete"><i class="bi bi-trash"></i></button>' +
                           '</form>' +
                           '</div>';
                }
            }
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Search and filter handlers
    $('#searchInput, #typeFilter, #yearFilter, #statusFilter').on('change keyup', function() {
        table.draw();
    });
});

function changeStatus(id, status, url) {
    if (!confirm('Are you sure you want to change the status?')) {
        return;
    }
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            is_active: status
        },
        success: function(response) {
            if (response.success) {
                $('#holidayTable').DataTable().draw();
                alert('Holiday status updated successfully');
            } else {
                alert(response.message || 'Failed to update status');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            alert(response.message || 'Failed to update status');
        }
    });
}
</script>
@endsection

