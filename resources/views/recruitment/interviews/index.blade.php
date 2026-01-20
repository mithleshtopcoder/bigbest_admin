@extends('layouts.app')

@section('title', 'Interview Scheduling')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Interview Scheduling</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Interviews</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('recruitment.interviews.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Schedule Interview
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

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Interviews</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="in-progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="interviewsTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Candidate</th>
                            <th>Job Title</th>
                            <th>Interviewer</th>
                            <th>Scheduled At</th>
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
    const table = $('#interviewsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("recruitment.interviews.data") }}',
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
            { data: 'candidate_name' },
            { data: 'job_title' },
            { data: 'interviewer' },
            { data: 'scheduled_at' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="{{ route("recruitment.interviews.edit", ":id") }}'.replace(':id', row.id) + '" class="btn btn-sm btn-link text-primary p-1" title="Edit">' +
                           '<i class="bi bi-pencil"></i>' +
                           '</a>' +
                           '<button onclick="deleteInterview(' + row.id + ')" class="btn btn-sm btn-link text-danger p-1" title="Delete">' +
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

function deleteInterview(id) {
    if (confirm('Are you sure you want to delete this interview?')) {
        $.ajax({
            url: '{{ route("recruitment.interviews.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#interviewsTable').DataTable().draw();
                    alert('Interview deleted successfully.');
                }
            }
        });
    }
}
</script>
@endsection
