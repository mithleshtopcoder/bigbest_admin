@extends('layouts.app')

@section('title', 'Checklist Templates')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Checklist Templates</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checklist Templates</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('onboarding.checklist-templates.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>New Template
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

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Checklist Templates</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="templatesTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Task Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Mandatory</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
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
    const table = $('#templatesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("onboarding.checklist-templates.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            { data: 'task_name' },
            { data: 'task_category' },
            { data: 'description' },
            { data: 'is_mandatory', orderable: false },
            { data: 'sort_order' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="{{ route("onboarding.checklist-templates.edit", ":id") }}'.replace(':id', row.id) + '" class="btn btn-sm btn-link text-primary p-1" title="Edit">' +
                           '<i class="bi bi-pencil"></i>' +
                           '</a>' +
                           '<button onclick="deleteTemplate(' + row.id + ')" class="btn btn-sm btn-link text-danger p-1" title="Delete">' +
                           '<i class="bi bi-trash"></i>' +
                           '</button>' +
                           '</div>';
                }
            }
        ],
        order: [[5, 'asc']],
        pageLength: 25
    });

    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    $('#statusFilter').on('change', function() {
        table.draw();
    });
});

function deleteTemplate(id) {
    if (confirm('Are you sure you want to delete this template?')) {
        $.ajax({
            url: '{{ route("onboarding.checklist-templates.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#templatesTable').DataTable().draw();
                    alert('Template deleted successfully.');
                }
            },
            error: function(xhr) {
                alert('Error deleting template.');
            }
        });
    }
}
</script>
@endsection
