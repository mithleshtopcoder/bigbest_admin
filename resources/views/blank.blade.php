@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Blank</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blank</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="#" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="filterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="StoreFilter" checked>
                                <label class="form-check-label" for="StoreFilter">Store</label>
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
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="PaymentFilter" checked>
                                <label class="form-check-label" for="PaymentFilter">Payment Method</label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
{{-- Bills data is now passed from controller --}}
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Blank Page For Table</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="billsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date & Time</th>
                            <th>Store</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Held By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer py-1">
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
                <div id="billsTable_paginate" class="d-flex align-items-center gap-1">
                    <!-- Custom pagination will be displayed here -->
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Blank Page For Form</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <form action="#" method="POST">
                @csrf
                <div class="row row-p">
                    <div class="col-12">
                        
                        <div class="form-group row">
                            <label for="name" class="form-label col-md-2">Name</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <label for="name" class="form-label col-md-2">Name</label>
                            <div class="col-md-4 ps-1">
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        
                        
                        
                    </div>
                   


                </div>
            </form>
        </div>
        <div class="card-footer py-2">
            
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#billsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('blank.bills') }}",
                type: "GET",
                data: function(d) {
                    d.draw = d.draw || 1;
                    d.start = d.start || 0;
                    d.length = d.length || 25;
                }
            },
            columns: [
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return '<div class="d-flex align-items-center gap-2">' +
                               '<i class="bi bi-pause-circle text-primary"></i>' +
                               '<a href="javascript:void(0);" class="text-decoration-none">#BILL-' + data + '</a>' +
                               '</div>';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return '<div>' + row.date + '</div>' +
                               '<small class="text-muted">' + row.time + '</small>';
                    }
                },
                { data: 'store' },
                { data: 'customer' },
                {
                    data: 'items',
                    render: function(data) {
                        return '<span class="badge bg-secondary">' + data + ' items</span>';
                    }
                },
                {
                    data: 'amount',
                    render: function(data) {
                        return '<strong>₹' + data + '</strong>';
                    }
                },
                {
                    data: 'held_by',
                    render: function(data) {
                        return '<small>' + data + '</small>';
                    }
                },
                {
                    data: 'order_id',
                    orderable: false,
                    render: function(data, type, row) {
                        var viewUrl = "{{ route('new-order.view', ['type' => 'pos', 'id' => ':id']) }}".replace(':id', data);
                        return '<div class="d-flex gap-2 justify-content-end">' +
                               '<div class="dropdown">' +
                               '<a href="#" class="btn btn-sm btn-link text-success" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></a>' +
                               '<ul class="dropdown-menu dropdown-menu-end">' +
                               '<li><a class="dropdown-item" href="#"><i class="bi bi-play me-2 text-success"></i>Resume</a></li>' +
                               '<li><a class="dropdown-item" href="#"><i class="bi bi-x me-2 text-danger"></i>Cancel</a></li>' +
                               '</ul>' +
                               '</div>' +
                               '<a href="' + viewUrl + '" class="btn btn-sm btn-link text-primary" title="View"><i class="bi bi-eye"></i></a>' +
                               '</div>';
                    }
                }
            ],
            pageLength: 5,
            lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
            pagingType: 'simple_numbers',
            dom: 'rt',
            paging: true,
            language: {
                processing: '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: '<div class="text-center py-4"><p class="text-muted mb-0">No bills found.</p></div>',
                zeroRecords: '<div class="text-center py-4"><p class="text-muted mb-0">No matching records found.</p></div>',
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
                // Update page info
                var api = this.api();
                var pageInfo = api.page.info();
                var start = pageInfo.start + 1;
                var end = pageInfo.end;
                var total = pageInfo.recordsTotal || 0;
                var filtered = pageInfo.recordsFiltered || pageInfo.recordsTotal || 0;
                
                var infoText = 'Showing ' + start + ' to ' + end + ' of ' + filtered + ' entries';
                if (filtered !== total && total > 0) {
                    infoText += ' (filtered from ' + total + ' total entries)';
                }
                
                $('#tableInfo').html(infoText);
                
                // Create custom pagination
                updateCustomPagination(table);
            },
            initComplete: function() {
                // Set initial page length
                $('#pageLength').val(this.api().page.len());
                
                // Hide default DataTables info and pagination
                var container = $(this.api().table().container());
                container.find('.dataTables_info').hide();
                container.find('.dataTables_paginate').hide();
                
                // Create custom pagination
                updateCustomPagination(table);
            }
        });

        // Handle page length change
        $('#pageLength').on('change', function() {
            table.page.len(parseInt($(this).val())).draw();
        });
        
        // Function to create custom pagination
        function updateCustomPagination(dtTable) {
            if (!dtTable || !dtTable.page) {
                return;
            }
            var pageInfo = dtTable.page.info();
            var currentPage = pageInfo.page;
            var totalPages = pageInfo.pages;
            var paginationHtml = '';
            
            // Previous button
            if (currentPage === 0) {
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" disabled><i class="bi bi-chevron-left"></i> Previous</button>';
            } else {
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="prev"><i class="bi bi-chevron-left"></i> Previous</button>';
            }
            
            // Page numbers
            var startPage = Math.max(0, currentPage - 2);
            var endPage = Math.min(totalPages - 1, currentPage + 2);
            
            if (startPage > 0) {
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="0">1</button>';
                if (startPage > 1) {
                    paginationHtml += '<span class="px-2">...</span>';
                }
            }
            
            for (var i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    paginationHtml += '<button type="button" class="btn btn-sm btn-primary paginate-btn" data-page="' + i + '">' + (i + 1) + '</button>';
                } else {
                    paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="' + i + '">' + (i + 1) + '</button>';
                }
            }
            
            if (endPage < totalPages - 1) {
                if (endPage < totalPages - 2) {
                    paginationHtml += '<span class="px-2">...</span>';
                }
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="' + (totalPages - 1) + '">' + totalPages + '</button>';
            }
            
            // Next button
            if (currentPage >= totalPages - 1) {
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" disabled>Next <i class="bi bi-chevron-right"></i></button>';
            } else {
                paginationHtml += '<button type="button" class="btn btn-sm btn-outline-secondary paginate-btn" data-page="next">Next <i class="bi bi-chevron-right"></i></button>';
            }
            
            $('#billsTable_paginate').html(paginationHtml);
        }
        
        // Bind click events using event delegation (outside the function, so it persists)
        // Use the card footer as the container for better event delegation
        $('#billsTable_paginate').on('click', '.paginate-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $btn = $(this);
            if ($btn.prop('disabled') || $btn.hasClass('disabled')) {
                return false;
            }
            
            var page = $btn.data('page');
            console.log('Pagination clicked:', page); // Debug log
            
            if (page === 'prev') {
                table.page('previous').draw('page');
            } else if (page === 'next') {
                table.page('next').draw('page');
            } else if (typeof page === 'number' || !isNaN(page)) {
                table.page(parseInt(page)).draw('page');
            }
            
            return false;
        });
    });
</script>
@endsection

@section('styles')
<style>
    #billsTable_paginate {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    #billsTable_paginate .paginate-btn {
        min-width: 2.5rem;
        cursor: pointer;
        pointer-events: auto;
        position: relative;
        z-index: 2;
    }
    #billsTable_paginate .paginate-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    #billsTable_paginate .paginate-btn:not(:disabled):hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
</style>
@endsection
