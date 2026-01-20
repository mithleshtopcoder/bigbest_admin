@extends('layouts.app')

@section('title', 'Salary Structure - Employee List')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Salary Structure</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Salary Structure</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="">
    <div class="col-12">
        <div class="card">
            <div class="card-header py-3">
                <h5 class="card-title mb-0">Employee List</h5>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, code, phone...">
                    </div>
                </div>

                <!-- DataTable -->
                <div class="table-responsive">
                    <table id="employeesTable" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Employee Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Store</th>
                                <th>Phone</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-management.salary-structure.list.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
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
            { data: 'employee_code' },
            { data: 'name' },
            { data: 'email' },
            { data: 'department' },
            { data: 'designation' },
            { data: 'store' },
            { data: 'phone' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!row || !row.uuid) return '<span class="text-muted">-</span>';
                    
                    const uuid = row.uuid;
                    const url = '{{ route("employee-management.salary-structure.index", ":uuid") }}'.replace(':uuid', uuid);
                    
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="' + url + '" class="btn btn-sm btn-link text-primary p-1" title="Manage Salary Structure">' +
                           '<i class="bi bi-plus-circle"></i>' +
                           '</a>' +
                           '</div>';
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: '<i class="bi bi-hourglass-split"></i> Loading...',
            emptyTable: 'No employees found',
            zeroRecords: 'No matching employees found'
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.draw();
    });
});
</script>
@endsection

