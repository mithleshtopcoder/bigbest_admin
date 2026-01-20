@extends('layouts.app')

@section('title', 'Service Radius')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 px-2">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Service Radius</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('store-management.manage-store') }}" class="text-decoration-none">Store Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Service Radius</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('store-management.service-radius.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New
            </a>
        </div>
    </div>
</div>

<!-- Toast container, place after <body> or after header -->
<div aria-live="polite" aria-atomic="true" class="position-fixed top-50 start-50 translate-middle z-index-9999">
    <div class="toast-container">

        @if(session('success'))
        <div class="toast align-items-center text-white bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="toast align-items-center text-white bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif

    </div>
</div>


<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Service Radius List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh" onclick="location.reload()"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>
        <div class="card-body p-2">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="storeFilter">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search service areas...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="serviceAreasTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">Store</th>
                            <th scope="col">Area Name</th>
                            <th scope="col">City</th>
                            <th scope="col">Service Radius</th>
                            <th scope="col">Coverage Area</th>
                            <th scope="col">Min Order</th>
                            <th scope="col">Delivery Charge</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#serviceAreasTable').DataTable({
            processing: true
            , serverSide: true
            , ajax: {
                url: "{{ route('store-management.service-radius.data') }}"
                , type: "GET"
                , data: function(d) {
                    d.store_id = $('#storeFilter').val();
                    d.is_active = $('#statusFilter').val();
                }
            }
            , columns: [{
                    data: 'store_name'
                    , name: 'store_name'
                }
                , {
                    data: 'area_name'
                    , name: 'area_name'
                }
                , {
                    data: 'city'
                    , name: 'city'
                }
                , {
                    data: 'radius_km'
                    , name: 'radius_km'
                    , render: function(data) {
                        return '<span class="fw-bold text-dark">' + data + ' km</span>';
                    }
                }
                , {
                    data: 'coverage_area'
                    , name: 'coverage_area'
                }
                , {
                    data: 'min_order_amount'
                    , name: 'min_order_amount'
                    , render: function(data) {
                        return '₹' + data;
                    }
                }
                , {
                    data: 'delivery_charge'
                    , name: 'delivery_charge'
                    , render: function(data) {
                        return '₹' + data;
                    }
                }
                , {
                    data: 'status'
                    , name: 'status'
                    , orderable: false
                    , searchable: false
                }
                , {
                    data: null
                    , orderable: false
                    , searchable: false
                    , render: function(data, type, row) {
                        var editUrl = "{{ route('store-management.service-radius.edit', ':id') }}".replace(':id', row.id);
                        var deleteUrl = "{{ route('store-management.service-radius.destroy', ':id') }}".replace(':id', row.id);

                        return `
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <a href="${editUrl}" class="btn btn-sm btn-light" title="Edit">
                                    <i class="bi bi-pencil text-primary"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light delete-service-area" data-id="${row.id}" data-url="${deleteUrl}" title="Delete">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
            , pageLength: 25
            , lengthMenu: [
                [10, 25, 50, 100]
                , [10, 25, 50, 100]
            ]
            , order: [
                [1, 'asc']
            ]
            , language: {
                processing: '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                , emptyTable: '<div class="text-center py-4"><p class="text-muted mb-0">No service areas found.</p></div>'
                , zeroRecords: '<div class="text-center py-4"><p class="text-muted mb-0">No matching records found.</p></div>'
            }
        });

        // Search input
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Store filter
        $('#storeFilter').on('change', function() {
            table.draw();
        });

        // Status filter
        $('#statusFilter').on('change', function() {
            table.draw();
        });

        // Delete service area
        $(document).on('click', '.delete-service-area', function(e) {
            e.preventDefault();
            var serviceAreaId = $(this).data('id');
            var deleteUrl = $(this).data('url');

            if (confirm('Are you sure you want to delete this service area?')) {
                $.ajax({
                    url: deleteUrl
                    , type: 'DELETE'
                    , headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    , success: function(response) {
                        table.draw();
                        if (response.success) {
                            alert('Service area deleted successfully.');
                        }
                    }
                    , error: function(xhr) {
                        alert('Failed to delete service area. Please try again.');
                        console.error(xhr);
                    }
                });
            }
        });
    });

</script>
@endsection

@section('styles')
<style>
    #serviceAreasTable_wrapper .dataTables_filter {
        display: none;
    }

</style>
@endsection
