@extends('layouts.app')

@section('title', ucfirst($type))

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 px-2">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">{{ ucfirst($type) }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Party Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($type) }}</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('party-management.suppliers-vendors.create', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">{{ ucfirst($type) }} List</h5>
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
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search {{ strtolower($type) }}...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="suppliersTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">{{ ucfirst($type) }} ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Contact Person</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">GST Number</th>
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
        var table = $('#suppliersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('party-management.suppliers-vendors.data', ['type' => $type]) }}",
                type: "GET",
                data: function(d) {
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [
                { data: 'code', name: 'code' },
                { data: 'name', name: 'name' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'gst_number', name: 'gst_number' },
                { 
                    data: 'status', 
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var viewUrl = "{{ route('party-management.suppliers-vendors.show', ['type' => $type, 'id' => ':id']) }}".replace(':id', row.id);
                        var editUrl = "{{ route('party-management.suppliers-vendors.edit', ['type' => $type, 'id' => ':id']) }}".replace(':id', row.id);
                        var deleteUrl = "{{ route('party-management.suppliers-vendors.destroy', ['type' => $type, 'id' => ':id']) }}".replace(':id', row.id);
                        
                        return `
                            <div class="d-flex align-items-center gap-2 justify-content-end">
                                <a href="${viewUrl}" class="btn btn-sm btn-light" title="View">
                                    <i class="bi bi-eye text-primary"></i>
                                </a>
                                <a href="${editUrl}" class="btn btn-sm btn-light" title="Edit">
                                    <i class="bi bi-pencil text-success"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-light delete-supplier" data-id="${row.id}" data-url="${deleteUrl}" title="Delete">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            order: [[1, 'asc']],
            language: {
                processing: '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>',
                emptyTable: '<div class="text-center py-4"><p class="text-muted mb-0">No {{ strtolower($type) }} found.</p></div>',
                zeroRecords: '<div class="text-center py-4"><p class="text-muted mb-0">No matching records found.</p></div>'
            }
        });

        // Search input
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Status filter
        $('#statusFilter').on('change', function() {
            table.draw();
        });

        // Delete supplier
        $(document).on('click', '.delete-supplier', function(e) {
            e.preventDefault();
            var supplierId = $(this).data('id');
            var deleteUrl = $(this).data('url');
            
            if (confirm('Are you sure you want to delete this {{ strtolower($type) }}?')) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.draw();
                        if (response.success) {
                            alert(response.message || '{{ ucfirst($type) }} deleted successfully.');
                        } else {
                            alert(response.message || 'Failed to delete {{ strtolower($type) }}.');
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete {{ strtolower($type) }}. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        alert(message);
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
    #suppliersTable_wrapper .dataTables_filter {
        display: none;
    }
</style>
@endsection
