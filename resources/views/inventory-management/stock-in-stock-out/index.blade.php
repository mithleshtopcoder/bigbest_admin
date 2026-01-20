@extends('layouts.app')

@section('title', 'Stock In / Stock Out')

@section('content')
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="page-header-left d-flex align-items-baseline">
        <h1 class="page-title mb-0">Stock In / Stock Out</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Stock In / Stock Out</li>
            </ol>
        </nav>
    </div>
    <div class="page-header-right d-flex align-items-center gap-2">
        <a href="{{ route('inventory-management.stock-in-stock-out.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create Movement
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
            <h5 class="card-title mb-0">Stock Movements</h5>
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
                    <select id="typeFilter" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="adjustment">Adjustment</option>
                        <option value="damage">Damage</option>
                        <option value="expiry">Expiry</option>
                        <option value="return">Return</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" id="dateFromFilter" class="form-control form-control-sm" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" id="dateToFilter" class="form-control form-control-sm" placeholder="To Date">
                </div>
            </div>

            {{-- Table --}}
            <div class="table-responsive">
                <table id="stockMovementsTable" class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Store</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Balance After</th>
                            <th>Created By</th>
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
        let table = $('#stockMovementsTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: '{{ route("inventory-management.stock-in-stock-out") }}'
                , data: function(d) {
                    d.store_id = $('#storeFilter').val();
                    d.movement_type = $('#typeFilter').val();
                    d.date_from = $('#dateFromFilter').val();
                    d.date_to = $('#dateToFilter').val();
                }
            }
            , columns: [{
                    data: null
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                }
                , {
                    data: 'movement_date'
                    , name: 'movement_date'
                }
                , {
                    data: 'product_name'
                    , name: 'product_name'
                }
                , {
                    data: 'variant_name'
                    , name: 'variant_name'
                }
                , {
                    data: 'store_name'
                    , name: 'store_name'
                }
                , {
                    data: 'movement_type_label'
                    , name: 'movement_type'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'quantity_display'
                    , name: 'quantity'
                    , orderable: false
                }
                , {
                    data: 'balance_after'
                    , name: 'balance_after'
                }
                , {
                    data: 'created_by_name'
                    , name: 'created_by_name'
                }
            , ]
            , order: [
                [1, 'desc']
            ]
            , pageLength: 25
            , dom: 'rt'
            , drawCallback: function() {
                let info = this.api().page.info();
                $('#tableInfo').html(`Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`);
                updateCustomPagination(this.api());
            }
        });

        $('#storeFilter, #typeFilter, #dateFromFilter, #dateToFilter').on('change', function() {
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

</script>
@endsection

@section('styles')
<style>
    #customPaginate button {
        min-width: 2.5rem;
    }

</style>
@endsection
