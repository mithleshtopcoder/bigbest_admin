@extends('layouts.app')

@section('title', 'Product Master')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex align-items-baseline">
            <h1 class="page-title mb-0">Product Master</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item">Manage Product</li>
                    <li class="breadcrumb-item active">Product Master</li>
                </ol>
            </nav>
        </div>

        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('manage-product.product-master.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Product
            </a>

            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-item-text">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Category</label>
                        </div>
                    </li>
                    <li class="dropdown-item-text">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Status</label>
                        </div>
                    </li>
                    <li class="dropdown-item-text">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Store</label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ================= MAIN BODY ================= --}}
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Product Master List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="productTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Variants</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables will load data here --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted">Show:</label>
                    <select id="productPageLength" class="form-select form-select-sm" style="width:auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="small text-muted" id="productTableInfo"></span>
                </div>

                <div id="product_paginate" class="d-flex gap-1"></div>
            </div>
        </div>
    </div>
</div>

{{-- ================= REJECT MODAL ================= --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for Rejection</label>
                        <textarea name="rejection_reason" id="rejection_reason" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {

        let table = $('#productTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('manage-product.product-master.datatable') }}"
            , columns: [{
                    data: 'product'
                }
                , {
                    data: 'category'
                }
                , {
                    data: 'sku'
                }
                , {
                    data: 'variants'
                }
                , {
                    data: 'stock'
                }
                , {
                    data: 'status'
                }
                , {
                    data: 'actions'
                    , orderable: false
                }
            ]
            , pageLength: 25
            , dom: 'rt'
            , drawCallback: function() {
                let info = this.api().page.info();
                $('#productTableInfo').html(`Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`);
                updatePagination(this.api());
            }
        });

        $('#productPageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updatePagination(api) {
            let info = api.page.info();
            let html = '';

            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page === 0 ? 'disabled' : 'data-page="prev"'}>Previous</button>`;

            for (let i = 0; i < info.pages; i++) {
                html += `<button class="btn btn-sm ${i === info.page ? 'btn-primary' : 'btn-outline-secondary'}" data-page="${i}">${i + 1}</button>`;
            }

            html += `<button class="btn btn-sm btn-outline-secondary" ${info.page + 1 >= info.pages ? 'disabled' : 'data-page="next"'}>Next</button>`;

            $('#product_paginate').html(html);
        }

        $('#product_paginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });

        // REJECT BUTTON HANDLER
        $(document).on('click', '.btn-reject', function() {
            let url = $(this).data('url');
            $('#rejectForm').attr('action', url);
            $('#rejectModal').modal('show');
        });

    });

</script>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endsection
