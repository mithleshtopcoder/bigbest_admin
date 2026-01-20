@extends('layouts.app')
@section('title', 'Training Modules')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Training Modules</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Training Modules</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('onboarding.training.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Module
            </a>
        </div>
    </div>
</div>
<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Training Modules</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="trainingTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Department</th>
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
    const table = $('#trainingTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("onboarding.training.data") }}',
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
            { data: 'title' },
            { data: 'module_type' },
            { data: 'duration_minutes' },
            { data: 'department' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="{{ route("onboarding.training.edit", ":id") }}'.replace(':id', row.id) + '" class="btn btn-sm btn-link text-primary p-1" title="Edit">' +
                           '<i class="bi bi-pencil"></i>' +
                           '</a>' +
                           '<button onclick="deleteTraining(' + row.id + ')" class="btn btn-sm btn-link text-danger p-1" title="Delete">' +
                           '<i class="bi bi-trash"></i>' +
                           '</button>' +
                           '</div>';
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25
    });

    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    $('#statusFilter').on('change', function() {
        table.draw();
    });
});

function deleteTraining(id) {
    if (confirm('Are you sure you want to delete this training module?')) {
        $.ajax({
            url: '{{ route("onboarding.training.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#trainingTable').DataTable().draw();
                    alert('Training module deleted successfully.');
                }
            }
        });
    }
}
</script>
@endsection
