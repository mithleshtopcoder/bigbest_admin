@extends('layouts.app')

@section('title', 'Failed Login History')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Failed Login History</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Failed Login History</li>
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
            <h5 class="card-title mb-0">Failed Login History</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-3">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-2">
                    <select id="reasonFilter" class="form-select form-select-sm">
                        <option value="">All Reasons</option>
                        <option value="Invalid Password">Invalid Password</option>
                        <option value="Invalid Credentials">Invalid Credentials</option>
                        <option value="Account Locked">Account Locked</option>
                        <option value="User Not Found">User Not Found</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" id="dateFromFilter" class="form-control form-control-sm" placeholder="From Date">
                </div>
                <div class="col-md-3">
                    <input type="date" id="dateToFilter" class="form-control form-control-sm" placeholder="To Date">
                </div>
            </div>
            <div class="table-responsive">
                <table id="failedLoginHistoryTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Email/Username</th>
                            <th>IP Address</th>
                            <th>Device</th>
                            <th>Reason</th>
                            <th>Attempt Time</th>
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
    const table = $('#failedLoginHistoryTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("logs-audit.failed-login-history.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.reason = $('#reasonFilter').val();
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
            { data: 'email' },
            { data: 'ip_address' },
            { data: 'device' },
            { data: 'reason', orderable: false },
            { data: 'attempt_time' }
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    $('#reasonFilter, #dateFromFilter, #dateToFilter').on('change', function() {
        table.draw();
    });
});
</script>
@endsection
