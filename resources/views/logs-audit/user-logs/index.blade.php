@extends('layouts.app')

@section('title', 'User Logs')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">User Logs</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Logs</li>
                </ol>
            </nav>
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
            <h5 class="card-title mb-0">User Activity Logs</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-2">
                    <select id="moduleFilter" class="form-select form-select-sm">
                        <option value="">All Modules</option>
                        <option value="Products">Products</option>
                        <option value="Orders">Orders</option>
                        <option value="Inventory">Inventory</option>
                        <option value="Suppliers">Suppliers</option>
                        <option value="Customers">Customers</option>
                        <option value="Employees">Employees</option>
                        <option value="Users">Users</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="actionFilter" class="form-select form-select-sm">
                        <option value="">All Actions</option>
                        <option value="created">Created</option>
                        <option value="updated">Updated</option>
                        <option value="deleted">Deleted</option>
                        <option value="viewed">Viewed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" id="dateFromFilter" class="form-control form-control-sm" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" id="dateToFilter" class="form-control form-control-sm" placeholder="To Date">
                </div>
            </div>
            <div class="table-responsive">
                <table id="userLogsTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Details</th>
                            <th>IP Address</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
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
    const table = $('#userLogsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("logs-audit.user-logs.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.module = $('#moduleFilter').val();
                d.action = $('#actionFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
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
            { data: 'user' },
            { data: 'action', orderable: false },
            { data: 'module' },
            { data: 'details' },
            { data: 'ip_address' },
            { data: 'date_time' }
        ],
        order: [[6, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    $('#moduleFilter, #actionFilter, #dateFromFilter, #dateToFilter').on('change', function() {
        table.draw();
    });
});
</script>
@endsection
