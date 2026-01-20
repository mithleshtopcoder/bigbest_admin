@extends('layouts.app')

@section('title', 'Employee Master')

@section('content')

<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Employee Master</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Employee Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Employee Master</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.manage-employee.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New
            </a>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-1">
                <h5 class="card-title mb-0">Manage Employee</h5>
                <div class="d-flex gap-1">
                    <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                    <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                    <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
                </div>
            </div>
            <div class="card-body custom-card-action p-0">
                <!-- Search and Filter Form -->
                <div class="p-3 border-bottom bg-light" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-12 d-flex gap-2">
                            <label class="form-label small w-132px">Store</label>
                            <select id="storeFilter" class="form-select form-control">
                                <option value="">All Stores</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>

                            <label class="form-label small w-132px">Search</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Name, email, code, phone...">

                            <label class="form-label small w-132px">Department</label>
                            <select id="departmentFilter" class="form-select form-control">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>

                            <label class="form-label small w-132px">Status</label>
                            <select id="statusFilter" class="form-select form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>

                            <button type="button" id="searchBtn" style="height: 28px;padding: 1px 8px;" class="btn btn-sm btn-primary">
                                <i class="feather-search me-1"></i>Search
                            </button>
                            <button type="button" id="clearBtn" style="height: 28px;padding: 1px 8px;" class="btn btn-sm btn-outline-secondary">
                                <i class="feather-x me-1"></i>Clear
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="employeesTable">
                        <thead>
                            <tr class="border-b">
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Store</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <label for="pageLength" class="mb-0 small text-muted">Show:</label>
                        <select id="pageLength" class="form-select form-control" style="width: auto;">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="small text-muted">entries</span>
                        <span class="small text-muted ms-2" id="tableInfo">
                            <!-- Page info will be displayed here -->
                        </span>
                    </div>
                    <div id="employeesTable_paginate" class="d-flex align-items-center gap-1">
                        <!-- Custom pagination will be displayed here -->
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
        var table = $('#employeesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('employee-management.manage-employee.data') }}",
                type: "GET",
                data: function(d) {
                    d.draw = d.draw || 1;
                    d.start = d.start || 0;
                    d.length = d.length || 25;
                    d.search = { value: $('#searchInput').val() };
                    d.department_id = $('#departmentFilter').val();
                    d.store_id = $('#storeFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                {
                    data: 'employee_code',
                    render: function(data, type, row) {
                        var editUrl = "{{ route('employee-management.manage-employee.edit', ':uuid') }}".replace(':uuid', row.uuid);
                        return '<div class="d-flex align-items-center gap-3">' +
                               '<div class="avatar-text"><i class="feather-user"></i></div>' +
                               '<a href="' + editUrl + '" class="text-decoration-none">' +
                               '<span class="d-block fw-medium">#' + (data || 'N/A') + '</span>' +
                               '</a></div>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        var nameHtml = '<span class="fw-medium">' + (row.name || 'N/A') + '</span>';
                        if (row.email) {
                            nameHtml += '<br><small class="text-muted">' + row.email + '</small>';
                        }
                        return nameHtml;
                    }
                },
                {
                    data: 'department_id',
                    render: function(data, type, row) {
                        if (row.department_id && data !== 'N/A') {
                            return '<span class="badge bg-soft-info text-info">' + row.department_id + '</span>';
                        }
                        return '<span class="text-muted">N/A</span>';
                    }
                },
                { data: 'designation' },
                { data: 'store' },
                {
                    data: 'phone',
                    render: function(data) {
                        if (data && data !== 'N/A') {
                            return '<a href="tel:' + data + '" class="text-decoration-none">' + data + '</a>';
                        }
                        return '<span class="text-muted">N/A</span>';
                    }
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data === 'active') {
                            return '<span class="badge bg-soft-success text-success">Active</span>';
                        }
                        return '<span class="badge bg-soft-danger text-danger">Inactive</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        if (!row || !row.uuid) return '<span class="text-muted">-</span>';
                        
                        var uuid = row.uuid;
                        var status = row.status || 'inactive';
                        var editUrl = "{{ route('employee-management.manage-employee.edit', ':uuid') }}".replace(':uuid', uuid);
                        var deleteUrl = "{{ route('employee-management.manage-employee.destroy', ':uuid') }}".replace(':uuid', uuid);
                        var csrfToken = '{{ csrf_token() }}';
                        var newStatus = status === 'active' ? 'inactive' : 'active';
                        var statusText = status === 'active' ? 'Deactivate' : 'Activate';
                        var statusClass = status === 'active' ? 'text-warning' : 'text-success';
                        
                        return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                               '<a href="' + editUrl + '" class="btn btn-sm btn-link text-primary p-1" title="Edit"><i class="bi bi-pencil"></i></a>' +
                               '<a href="javascript:void(0);" class="btn btn-sm btn-link ' + statusClass + ' p-1" onclick="changeStatus(\'' + uuid + '\', \'' + newStatus + '\')" title="' + statusText + '"><i class="bi ' + (status === 'active' ? 'bi-x-circle' : 'bi-check-circle') + '"></i></a>' +
                               '<form action="' + deleteUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">' +
                               '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                               '<input type="hidden" name="_method" value="DELETE">' +
                               '<button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete"><i class="bi bi-trash"></i></button>' +
                               '</form>' +
                               '</div>';
                    }
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            pagingType: 'simple_numbers',
            dom: 'rt',
            paging: true,
            language: {
                processing: '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: '<div class="text-center py-4"><div class="text-muted"><i class="feather-inbox fs-1 d-block mb-2"></i>No employees found</div></div>',
                zeroRecords: '<div class="text-center py-4"><div class="text-muted"><i class="feather-inbox fs-1 d-block mb-2"></i>No matching records found</div></div>',
                info: '',
                infoEmpty: '',
                infoFiltered: '',
                paginate: {
                    first: 'First',
                    previous: 'Previous',
                    next: 'Next',
                    last: 'Last'
                }
            },
            drawCallback: function(settings) {
                var api = this.api();
                var pageInfo = api.page.info();
                var start = pageInfo.start + 1;
                var end = pageInfo.end;
                var filtered = pageInfo.recordsFiltered || 0;
                
                $('#tableInfo').html('Showing ' + start + ' to ' + end + ' of ' + filtered + ' entries');
                updateCustomPagination(table);
            },
            initComplete: function() {
                $('#pageLength').val(this.api().page.len());
                $(this.api().table().container()).find('.dataTables_info, .dataTables_paginate').hide();
                updateCustomPagination(table);
            }
        });

        // Handle page length change
        $('#pageLength').on('change', function() {
            table.page.len(parseInt($(this).val())).draw();
        });

        // Handle search button
        $('#searchBtn').on('click', function() {
            table.draw();
        });

        // Handle clear button
        $('#clearBtn').on('click', function() {
            $('#searchInput').val('');
            $('#departmentFilter').val('');
            $('#storeFilter').val('');
            $('#statusFilter').val('');
            table.draw();
        });

        // Handle Enter key in search input
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                table.draw();
            }
        });

        // Handle filter changes
        $('#departmentFilter, #storeFilter, #statusFilter').on('change', function() {
            table.draw();
        });

        function updateCustomPagination(dtTable) {
            if (!dtTable || !dtTable.page) return;
            
            var pageInfo = dtTable.page.info();
            var currentPage = pageInfo.page;
            var totalPages = pageInfo.pages;
            var html = '';
            
            html += currentPage === 0 
                ? '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" disabled><i class="feather-chevron-left"></i> Previous</button>'
                : '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="prev"><i class="feather-chevron-left"></i> Previous</button>';
            
            var startPage = Math.max(0, currentPage - 2);
            var endPage = Math.min(totalPages - 1, currentPage + 2);
            
            if (startPage > 0) {
                html += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="0">1</button>';
                if (startPage > 1) html += '<span class="px-2">...</span>';
            }
            
            for (var i = startPage; i <= endPage; i++) {
                html += '<button type="button" class="btn btn-sm ' + (i === currentPage ? 'btn-primary' : 'btn-outline-secondary') + ' paginate-btn" data-page="' + i + '">' + (i + 1) + '</button>';
            }
            
            if (endPage < totalPages - 1) {
                if (endPage < totalPages - 2) html += '<span class="px-2">...</span>';
                html += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="' + (totalPages - 1) + '">' + totalPages + '</button>';
            }
            
            html += currentPage >= totalPages - 1
                ? '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" disabled>Next <i class="feather-chevron-right"></i></button>'
                : '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="next">Next <i class="feather-chevron-right"></i></button>';
            
            $('#employeesTable_paginate').html(html);
        }
        
        $('#employeesTable_paginate').on('click', '.paginate-btn', function(e) {
            e.preventDefault();
            if ($(this).prop('disabled')) return false;
            
            var page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(parseInt(page)).draw('page');
            return false;
        });

        window.changeStatus = function(uuid, newStatus) {
            if (!confirm('Are you sure you want to ' + (newStatus === 'active' ? 'activate' : 'deactivate') + ' this employee?')) {
                return;
            }

            $.ajax({
                url: "{{ url('employee-management/manage-employee') }}/" + uuid + "/status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus === 'active' ? 1 : 0
                },
                success: function(response) {
                    if (response.success) {
                        table.draw();
                    }
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Failed to update status');
                }
            });
        };
    });
</script>
@endsection

@section('styles')
<style>
    #employeesTable_paginate {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    #employeesTable_paginate .paginate-btn {
        min-width: 2.5rem;
        cursor: pointer;
        pointer-events: auto;
        position: relative;
        z-index: 2;
    }
    #employeesTable_paginate .paginate-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    #employeesTable_paginate .paginate-btn:not(:disabled):hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    .w-132px{
       min-width: 132px;
    align-items: center;
    display: flex;
    padding-left: 11px;
    }
</style>
@endsection
