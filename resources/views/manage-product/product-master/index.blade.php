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
                <table class="table table-hover mb-0">
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
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-box-seam text-primary"></i>
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <div class="small text-muted">#PRD-{{ $product->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>{{ $product->category->name ?? '-' }}</td>

                            <td>{{ $product->sku ?? '-' }}</td>

                            <td>
                                <span class="badge bg-secondary">
                                    {{ $product->variant_count }} variants
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $product->total_stock }} units
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $product->status === 'active' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>

                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('manage-product.product-master.edit', $product->id) }}" class="btn btn-sm btn-link text-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('manage-product.product-master.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
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

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        let table = $('.table').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('manage-product.product-master.datatable') }}", // create this route
            dom: 'rt'
            , pageLength: 25
            , pagingType: 'simple_numbers',

            columns: [{
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
            ],

            drawCallback: function() {
                let info = this.api().page.info();
                $('#productTableInfo').html(
                    `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`
                );
                updatePagination(this.api());
            }
        });

        $('#productPageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updatePagination(api) {
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

            $('#product_paginate').html(html);
        }

        $('#product_paginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });

    });

</script>

@endsection

@section('styles')
@endsection
