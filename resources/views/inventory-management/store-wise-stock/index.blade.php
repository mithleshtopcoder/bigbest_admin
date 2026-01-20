@extends('layouts.app')

@section('title', 'Store-Wise Stock')

@section('content')
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="page-header-left d-flex align-items-baseline">
        <h1 class="page-title mb-0">Store-Wise Stock</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Store-Wise Stock</li>
            </ol>
        </nav>
    </div>
</div>

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
            <h5 class="card-title mb-0">Store-Wise Stock</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Fullscreen">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-2">

            {{-- Filters --}}
            <div class="row mb-3 g-2">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search by product, SKU, barcode...">
                </div>
                <div class="col-md-3">
                    <select id="storeFilter" class="form-select form-select-sm">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="storeWiseStockTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Variants</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>

        {{-- Pagination Info --}}
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
    let table = $('#storeWiseStockTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("inventory-management.store-wise-stock") }}',
            data: function(d) {
                d.store_id = $('#storeFilter').val();
            }
        },
        columns: [
            { data: null, orderable: false, searchable: false, render: function(data, type, row, meta) { return meta.row + meta.settings._iDisplayStart + 1; }},
            { data: 'product_name', name: 'product_name' },
            { data: 'sku', name: 'sku' },
            { data: 'variants', name: 'variants', orderable: false, searchable: false, render: function(data, type, row) {
                const list = (data || []).map(v => `${v.name} (${v.sku || '-'})`).join('<br>');
                const infoBtn = `<button type="button" class="btn btn-link p-0 ms-2 text-primary view-stock-btn" data-product-id="${row.product_id}" data-product-name="${row.product_name}">
                    <i class="bi bi-info-circle"></i>
                </button>`;
                return `<div class="d-flex align-items-start justify-content-between"><div>${list || '-'}</div>${infoBtn}</div>`;
            }},
            { data: 'quantity', name: 'quantity' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        dom: 'rt',
        drawCallback: function() {
            let info = this.api().page.info();
            $('#tableInfo').html(`Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`);
            updateCustomPagination(this.api());
        }
    });

    $('#storeFilter').on('change', function() {
        table.ajax.reload();
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
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

    $('#storeWiseStockTable').on('click', '.view-stock-btn', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name') || 'Product';
        const storeId = $('#storeFilter').val();

        $('#stockDetailsModalLabel').text(`${productName} - Stock Details`);
        const tbody = $('#stockDetailsBody');
        tbody.html('<tr><td colspan="4" class="text-center text-muted">Loading...</td></tr>');

        $.ajax({
            url: `/inventory-management/store-wise-stock/${productId}/stocks`,
            method: 'GET',
            data: { store_id: storeId },
            success: function(response) {
                if (!response.success || !response.data.length) {
                    tbody.html('<tr><td colspan="4" class="text-center text-muted">No stock records found.</td></tr>');
                    return;
                }

                const rows = response.data.map(item => `
                    <tr>
                        <td>${item.variant_name}</td>
                        <td>${item.sku}</td>
                        <td>${item.store_name}</td>
                        <td>${item.quantity}</td>
                    </tr>
                `).join('');
                tbody.html(rows);
            },
            error: function() {
                tbody.html('<tr><td colspan="4" class="text-center text-muted">Failed to load stock records.</td></tr>');
            }
        });

        const modal = new bootstrap.Modal(document.getElementById('stockDetailsModal'));
        modal.show();
    });
});
</script>
@endsection

<div class="modal fade" id="stockDetailsModal" tabindex="-1" aria-labelledby="stockDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockDetailsModalLabel">Stock Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Variant</th>
                                <th>SKU</th>
                                <th>Store</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="stockDetailsBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
    #customPaginate button {
        min-width: 2.5rem;
    }
</style>
@endsection
