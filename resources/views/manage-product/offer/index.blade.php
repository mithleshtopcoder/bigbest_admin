@extends('layouts.app')

@section('title', ucfirst(str_replace('-', ' ', $type)) . ' List')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">{{ ucfirst(str_replace('-', ' ', $type)) }} List</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item active">{{ ucfirst(str_replace('-', ' ', $type)) }}</li>
                </ol>
            </nav>
        </div>

        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('manage-product.offer.create', $type) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create {{ ucfirst(str_replace('-', ' ', $type)) }}
            </a>
        </div>
    </div>
</div>

{{-- ================= MAIN BODY ================= --}}
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">{{ ucfirst(str_replace('-', ' ', $type)) }} List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="offersTable">
                    <thead>
                        <tr>
                            <th>ID</th> {{-- New ID column --}}
                            <th>Code</th>
                            <th>Name</th>
                            <th>Discount</th>
                            <th>Applies To</th>
                            <th>Target</th>
                            <th>Status</th>
                            <th>Validity</th>
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
                    <select id="offersPageLength" class="form-select form-select-sm" style="width:auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                    </select>
                    <span class="small text-muted" id="offersTableInfo"></span>
                </div>
                <div id="offers_paginate" class="d-flex gap-1"></div>
            </div>
        </div>
    </div>
</div>

@endsection

{{-- ================= SCRIPTS ================= --}}
@section('scripts')
<script>
    $(document).ready(function() {

        let table = $('#offersTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: "{{ route('manage-product.offer.datatable', $type) }}"
            , dom: 'rt'
            , pageLength: 25
            , pagingType: 'simple_numbers',

            columns: [{
                    data: 'id'
                }
                , {
                    data: 'code'
                }
                , {
                    data: 'name'
                }
                , {
                    data: 'discount'
                }
                , {
                    data: 'applies_to'
                }
                , {
                    data: 'target'
                }
                , {
                    data: 'status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: 'validity'
                }
                , {
                    data: null
                    , orderable: false
                    , searchable: false
                    , className: 'text-end'
                    , render: function(data, type, row) {
                        let editUrl = "{{ url('manage-product/offer/'.$type.'/edit') }}/" + row.id;
                        let deleteUrl = "{{ url('manage-product/offer/'.$type) }}/" + row.id;

                        return `
        <div class="d-flex justify-content-end gap-1">

            <a href="${editUrl}" class="" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>

            <form action="${deleteUrl}" method="POST" class="d-inline" onsubmit="return confirm('Delete this offer?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-link text-danger" title="Delete">
                    <i class="bi bi-trash"></i>
                </button>
            </form>

        </div>
    `;
                    }

                }
            ],

            drawCallback: function() {
                let info = this.api().page.info();
                $('#offersTableInfo').html(
                    `Showing ${info.start + 1} to ${info.end} of ${info.recordsTotal}`
                );
                updateCustomPagination(this.api());
            }
        });

        $('#offersPageLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        function updateCustomPagination(api) {
            let info = api.page.info();
            let html = '';

            html += `<button class="btn btn-sm btn-outline-secondary"
                    ${info.page === 0 ? 'disabled' : 'data-page="prev"'}>
                    Previous
                </button>`;

            for (let i = 0; i < info.pages; i++) {
                html += `<button class="btn btn-sm ${i === info.page ? 'btn-primary' : 'btn-outline-secondary'}"
                        data-page="${i}">
                        ${i + 1}
                    </button>`;
            }

            html += `<button class="btn btn-sm btn-outline-secondary"
                    ${info.page + 1 >= info.pages ? 'disabled' : 'data-page="next"'}>
                    Next
                </button>`;

            $('#offers_paginate').html(html);
        }

        $('#offers_paginate').on('click', 'button', function() {
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
    #offers_paginate button {
        min-width: 2.5rem;
    }

</style>
@endsection
