@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Customers</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item">Party Management</li>
                    <li class="breadcrumb-item active" aria-current="page">Customers</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="filterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="StatusFilter" checked>
                                <label class="form-check-label" for="StatusFilter">Status</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateFilter" checked>
                                <label class="form-check-label" for="DateFilter">Date</label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card" id="customersTableCard">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Customers List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="customersTable">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Orders</th>
                            <th>Total Spent</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <label for="pageLength" class="mb-0 small text-muted">Show:</label>
                    <select id="pageLength" class="form-select form-select-sm" style="width: auto;">
                        <option value="5">5</option>
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
                <div id="customersTable_paginate" class="d-flex align-items-center gap-1">
                    <!-- Custom pagination -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#customersTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('customers.get') }}"
            , columns: [{
                    data: 'id'
                    , render: data => '#CUS-' + data
                }
                , {
                    data: 'full_name'
                }
                , {
                    data: 'email'
                }
                , {
                    data: 'phone'
                }
                , {
                    data: 'orders_count'
                    , render: data => `<span class="badge bg-secondary">${data}</span>`
                }
                , {
                    data: 'total_spent'
                    , render: data => `₹${data}`
                }
                , {
                    data: 'status'
                    , render: status => status === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'
                }
                , {
                    data: 'customer_id'
                    , orderable: false
                    , className: 'text-end'
                    , render: function(data) {
                        // Only change: View button now links to show page
                        return `<a href="/party-management/customers/${data}" class="btn btn-sm btn-link text-primary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>`;
                    }
                }
            ]
            , pageLength: 25
            , lengthMenu: [
                [5, 10, 25, 50, 100]
                , [5, 10, 25, 50, 100]
            ]
            , dom: 'rt'
            , paging: true
            , drawCallback: function() {
                // Update page info and custom pagination
                var api = this.api();
                var info = api.page.info();
                var infoText = 'Showing ' + (info.start + 1) + ' to ' + info.end + ' of ' + info.recordsFiltered + ' entries';
                if (info.recordsFiltered !== info.recordsTotal) {
                    infoText += ' (filtered from ' + info.recordsTotal + ' total entries)';
                }
                $('#tableInfo').html(infoText);
                updateCustomPagination(table);
            }
        });

        $('#pageLength').on('change', function() {
            table.page.len(parseInt($(this).val())).draw();
        });

        function updateCustomPagination(dtTable) {
            var pageInfo = dtTable.page.info();
            var currentPage = pageInfo.page;
            var totalPages = pageInfo.pages;
            var paginationHtml = '';

            if (currentPage > 0) paginationHtml += `<button class="btn btn-sm btn-outline-secondary paginate-btn" data-page="prev">Prev</button>`;
            else paginationHtml += `<button class="btn btn-sm btn-outline-secondary paginate-btn" disabled>Prev</button>`;

            for (var i = 0; i < totalPages; i++) {
                paginationHtml += `<button class="btn btn-sm ${i===currentPage?'btn-primary':'btn-outline-secondary'} paginate-btn" data-page="${i}">${i+1}</button>`;
            }

            if (currentPage < totalPages - 1) paginationHtml += `<button class="btn btn-sm btn-outline-secondary paginate-btn" data-page="next">Next</button>`;
            else paginationHtml += `<button class="btn btn-sm btn-outline-secondary paginate-btn" disabled>Next</button>`;

            $('#customersTable_paginate').html(paginationHtml);
        }

        $('#customersTable_paginate').on('click', '.paginate-btn', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else table.page(parseInt(page)).draw('page');
        });
    });

</script>
@endsection

@section('styles')
<style>
    #customersTable_paginate {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    #customersTable_paginate .paginate-btn {
        min-width: 2.5rem;
        cursor: pointer;
    }

    #customersTable_paginate .paginate-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    #customersTable_paginate .paginate-btn:not(:disabled):hover {
        background-color: #e9ecef;
    }

</style>
@endsection
