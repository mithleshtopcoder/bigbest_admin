@extends('layouts.app')

@section('title', 'Online Orders')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="page-header-left d-flex" style="align-items: baseline;">
        <h1 class="page-title mb-0">Online Orders</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active">Online Orders</li>
            </ol>
        </nav>
    </div>

    <div class="page-header-right d-flex align-items-center gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                <i class="bi bi-funnel me-2"></i>Filter
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li class="dropdown-item-text">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Status</label>
                    </div>
                </li>
                <li class="dropdown-item-text">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Date</label>
                    </div>
                </li>
                <li class="dropdown-item-text">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked>
                        <label class="form-check-label">Payment</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<div class="main-body">
    <x-order-status-cards />

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Online Orders List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 400px;">
                <table class="table table-hover mb-0" id="onlineOrdersTable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date & Time</th>
                            <th>Customer</th>
                            <th>Payment</th>
                            <th>Pay Status</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="card-footer py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted">Show:</label>
                    <select id="onlinePageLength" class="form-select form-select-sm" style="width:auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="small text-muted" id="onlineTableInfo"></span>
                </div>

                <div id="onlineOrders_paginate" class="d-flex gap-1"></div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        let table = $('#onlineOrdersTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('online.orders.datatable') }}"
            , dom: 'rt'
            , pageLength: 25
            , columns: [{
                    data: 'order'
                    , name: 'order'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'date_time'
                    , name: 'date_time'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'customer'
                    , name: 'customer'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'payment_method'
                    , name: 'payment_method'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'payment_status'
                    , name: 'payment_status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'amount'
                    , name: 'amount'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'status'
                    , name: 'status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'action'
                    , name: 'action'
                    , orderable: false
                    , searchable: false
                }
            ]
            , drawCallback: function() {
                let info = this.api().page.info();
                $('#onlineTableInfo').html(`Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`);
                updateCustomPagination(this.api());
            }
        });

        $('#onlinePageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updateCustomPagination(api) {
            let info = api.page.info();
            let html = '';
            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page === 0 ? 'disabled' : 'data-page="prev"'}>Previous</button>`;
            for (let i = 0; i < info.pages; i++) {
                html += `<button class="btn btn-sm ${i === info.page ? 'btn-primary' : 'btn-outline-secondary'}" data-page="${i}">${i + 1}</button>`;
            }
            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page + 1 >= info.pages ? 'disabled' : 'data-page="next"'}>Next</button>`;
            $('#onlineOrders_paginate').html(html);
        }

        $('#onlineOrders_paginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });
    });

</script>
@endsection

@section('styles')
<style>
    #onlineOrders_paginate button {
        min-width: 2.5rem;
    }

    .table-responsive {
        max-height: 400px;
    }

</style>
@endsection
