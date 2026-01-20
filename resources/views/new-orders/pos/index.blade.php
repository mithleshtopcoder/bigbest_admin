@extends('layouts.app')

@section('title', 'POS Orders')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">POS Orders</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active">POS Orders</li>
                </ol>
            </nav>
        </div>

        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('new-order.pos.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Order
            </a>

            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item-text">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Store</label>
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
</div>

{{-- ================= MAIN BODY ================= --}}
<div class="main-body">
    <x-order-status-cards :current-status="request()->routeIs('new-order.index') && request()->route('type') === 'pos' ? 'new-order-pos' : null" />

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">POS Orders List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="posOrdersTable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date & Time</th>
                            <th>Store</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Payment</th>
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
                    <select id="posPageLength" class="form-select form-select-sm" style="width:auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="small text-muted" id="posTableInfo"></span>
                </div>

                <div id="posOrders_paginate" class="d-flex gap-1"></div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- ================= SCRIPTS ================= --}}
@section('scripts')
<script>
    $(document).ready(function() {

        let table = $('#posOrdersTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('pos.orders.datatable') }}"
            , dom: 'rt'
            , pageLength: 10
            , pagingType: 'simple_numbers',

            columns: [{
                    data: 'id'
                    , render: data => `
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-cart-check text-primary"></i>
                        <strong>#${data}</strong>
                    </div>`
                }
                , {
                    data: null
                    , render: row => `
                    <div>${row.date}</div>
                    <small class="text-muted">${row.time}</small>`
                }
                , {
                    data: 'store'
                }
                , {
                    data: 'customer'
                }
                , {
                    data: 'items'
                    , render: d => `<span class="badge bg-secondary">${d} items</span>`
                }
                , {
                    data: 'amount'
                    , render: d => `<strong>₹${d}</strong>`
                }
                , {
                    data: 'payment'
                    , render: d => `<span class="badge bg-soft-info text-info">${d}</span>`
                }
                , {
                    data: 'status'
                    , render: d => `<span class="badge bg-soft-success text-success">${d}</span>`
                }
                , {
                    data: null
                    , orderable: false
                    , render: function(row) {
                        let buttons = '';

                        // Resume icon button for held orders
                        if (row.status_raw && row.status_raw.toLowerCase() === 'hold') {
                            buttons += `
                <a href="/new-order/pos/resume/${row.id}" class="btn btn-sm btn-primary me-1" title="Resume">
                    <i class="bi bi-play-fill"></i>
                </a>
            `;
                        }

                        // View icon button
                        buttons += `
            <a href="/new-order/pos/${row.id}/edit" class="btn btn-sm btn-secondary">
    <i class="bi bi-eye"></i>
</a>
        `;

                        return `<div class="d-flex justify-content-end gap-2">${buttons}</div>`;
                    }
                }


            ],

            drawCallback: function() {
                let info = this.api().page.info();
                $('#posTableInfo').html(
                    `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`
                );
                updateCustomPagination(this.api());
            }
        });

        $('#posPageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updateCustomPagination(api) {
            let info = api.page.info();
            let html = '';

            html += `<button class="btn btn-sm btn-outline-secondary"
                 ${info.page === 0 ? 'disabled' : 'data-page="prev"'}>Previous</button>`;

            for (let i = 0; i < info.pages; i++) {
                html += `<button class="btn btn-sm ${i === info.page ? 'btn-primary' : 'btn-outline-secondary'}"
                     data-page="${i}">${i + 1}</button>`;
            }

            html += `<button class="btn btn-sm btn-outline-secondary"
                 ${info.page + 1 >= info.pages ? 'disabled' : 'data-page="next"'}>Next</button>`;

            $('#posOrders_paginate').html(html);
        }

        $('#posOrders_paginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });

    });

</script>
@endsection

{{-- ================= STYLES ================= --}}
@section('styles')
<style>
    #posOrders_paginate button {
        min-width: 2.5rem;
    }

</style>
@endsection
