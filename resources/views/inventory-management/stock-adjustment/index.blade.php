@extends('layouts.app')

@section('title', 'Stock Adjustment')

@section('content')
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="page-header-left d-flex align-items-baseline">
        <h1 class="page-title mb-0">Stock Adjustment</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Stock Adjustment</li>
            </ol>
        </nav>
    </div>
    <div class="page-header-right d-flex align-items-center gap-2">
        <a href="{{ route('inventory-management.stock-adjustment.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Adjustment
        </a>
    </div>
</div>

{{-- Alerts --}}
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

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Stock Adjustments</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-warning btn-card-refresh" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" title="Fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-2">
            {{-- Filters --}}
            <div class="row mb-3 g-2">
                <div class="col-md-3">
                    <select id="storeFilter" class="form-select form-select-sm">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="typeFilter" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="addition">Addition</option>
                        <option value="reduction">Reduction</option>
                        <option value="correction">Correction</option>
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="stockAdjustmentsTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Adjustment #</th>
                            <th>Store</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Total Items</th>
                            <th>Total Value</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer py-2 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <label class="small text-muted">Show:</label>
                <select id="pageLength" class="form-select form-select-sm" style="width:auto">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                </select>
                <span class="small text-muted" id="tableInfo"></span>
            </div>
            <div id="customPaginate" class="d-flex gap-1"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#stockAdjustmentsTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: '{{ route("inventory-management.stock-adjustment") }}'
                , data: function(d) {
                    d.store_id = $('#storeFilter').val();
                    d.status = $('#statusFilter').val();
                    d.type = $('#typeFilter').val();
                }
            }
            , columns: [{
                    data: null
                    , orderable: false
                    , searchable: false
                    , render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                }
                , {
                    data: 'adjustment_number'
                    , name: 'adjustment_number'
                }
                , {
                    data: 'store_name'
                    , name: 'store_name'
                }
                , {
                    data: 'type_badge'
                    , name: 'type'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'reason'
                    , name: 'reason'
                }
                , {
                    data: 'adjustment_date'
                    , name: 'adjustment_date'
                }
                , {
                    data: 'total_items'
                    , name: 'total_items'
                }
                , {
                    data: 'total_value'
                    , name: 'total_value'
                    , render: data => '₹' + parseFloat(data).toFixed(2)
                }
                , {
                    data: 'status_badge'
                    , name: 'status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'created_by_name'
                    , name: 'created_by_name'
                }
                , {
                    data: null
                    , orderable: false
                    , searchable: false
                    , className: 'text-end'
                    , render: function(data, row) {
                        let viewUrl = `/inventory-management/stock-adjustment/${row.id}`;
                        let approveBtn = row.status === 'pending' ? `<button class="btn btn-sm btn-success" onclick="approveAdjustment(${row.id})" title="Approve"><i class="bi bi-check-circle-fill"></i></button>` : '';
                        let rejectBtn = row.status === 'pending' ? `<button class="btn btn-sm btn-danger" onclick="rejectAdjustment(${row.id})" title="Reject"><i class="bi bi-x-circle-fill"></i></button>` : '';
                        return `
                        <div class="d-flex justify-content-end gap-1">
                            <a href="${viewUrl}" class="btn btn-sm btn-primary" title="View"><i class="bi bi-eye-fill"></i></a>
                            ${approveBtn}
                            ${rejectBtn}
                        </div>
                    `;
                    }
                }
            ]
            , order: [
                [5, 'desc']
            ]
            , pageLength: 25
            , dom: 'rt'
            , drawCallback: function() {
                let info = this.api().page.info();
                $('#tableInfo').html(`Showing ${info.start+1} to ${info.end} of ${info.recordsTotal}`);
                updateCustomPagination(this.api());
            }
        });

        $('#storeFilter, #statusFilter, #typeFilter').on('change', function() {
            table.ajax.reload();
        });
        $('#pageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updateCustomPagination(api) {
            let info = api.page.info();
            let html = '';
            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page===0?'disabled':'data-page="prev"'}>Previous</button>`;
            for (let i = 0; i < info.pages; i++) {
                html += `<button class="btn btn-sm ${i===info.page?'btn-primary':'btn-outline-secondary'}" data-page="${i}">${i+1}</button>`;
            }
            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page+1>=info.pages?'disabled':'data-page="next"'}>Next</button>`;
            $('#customPaginate').html(html);
        }

        $('#customPaginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });
    });

    // Approve / Reject functions remain unchanged
    function approveAdjustment(id) {
        if (confirm('Are you sure you want to approve this stock adjustment?')) {
            $.post(`/inventory-management/stock-adjustment/${id}/approve`, {
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function(resp) {
                location.reload();
            });
        }
    }

    function rejectAdjustment(id) {
        const reason = prompt('Please enter rejection reason:');
        if (reason) {
            $.post(`/inventory-management/stock-adjustment/${id}/reject`, {
                _token: $('meta[name="csrf-token"]').attr('content')
                , rejection_reason: reason
            }, function(resp) {
                location.reload();
            });
        }
    }

</script>
@endsection

@section('styles')
<style>
    #customPaginate button {
        min-width: 2.5rem;
    }

</style>
@endsection
