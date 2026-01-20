@extends('layouts.app')

@section('title', 'Brands')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
    <div class="page-header-left d-flex align-items-baseline">
        <h1 class="page-title mb-0">Brands</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                </li>
                <li class="breadcrumb-item">Manage Product</li>
                <li class="breadcrumb-item active">Brands</li>
            </ol>
        </nav>
    </div>

    <div class="page-header-right d-flex align-items-center gap-2">
        <a href="{{ route('manage-product.brands.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Add Brand
        </a>

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
                        <label class="form-check-label">Sort Order</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- ================= MAIN BODY ================= --}}
<div class="main-body">
    <div class="card">

        {{-- Card Header --}}
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Brand List</h5>
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

        {{-- Card Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="brandsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Website</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data loaded via DataTables or server-side --}}
                        @forelse($brands as $brand)
                        <tr>
                            <td>#BRD-{{ str_pad($brand->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->website ?? '-' }}</td>
                            <td>
                                @if($brand->is_active)
                                <span class="badge bg-soft-success text-success">Active</span>
                                @else
                                <span class="badge bg-soft-danger text-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $brand->sort_order }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('manage-product.brands.edit', $brand->id) }}" class="btn btn-sm btn-link text-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('manage-product.brands.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Delete this brand?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <strong>No brands found</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer --}}
        <div class="card-footer py-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted">Show:</label>
                    <select id="brandsPageLength" class="form-select form-select-sm" style="width:auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="small text-muted" id="brandsTableInfo"></span>
                </div>
                <div id="brands_paginate" class="d-flex gap-1"></div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        let table = $('#brandsTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('manage-product.brands.datatable') }}"
            , dom: 'rt'
            , pageLength: 25
            , pagingType: 'simple_numbers'
            , columns: [{
                    data: 'id'
                }
                , {
                    data: 'name'
                }
                , {
                    data: 'website'
                }
                , {
                    data: 'status'
                }
                , {
                    data: 'sort_order'
                }
                , {
                    data: 'actions'
                    , orderable: false
                }
            ]
            , drawCallback: function() {
                let info = this.api().page.info();
                $('#brandsTableInfo').html(
                    `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`
                );
                updatePagination(this.api());
            }
        });

        $('#brandsPageLength').on('change', function() {
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

            $('#brands_paginate').html(html);
        }

        $('#brands_paginate').on('click', 'button', function() {
            let page = $(this).data('page');
            if (page === 'prev') table.page('previous').draw('page');
            else if (page === 'next') table.page('next').draw('page');
            else if (!isNaN(page)) table.page(page).draw('page');
        });

    });

</script>
@endsection
