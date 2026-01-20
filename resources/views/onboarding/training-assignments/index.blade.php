@extends('layouts.app')

@section('title', 'Training Assignments')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Training Assignments</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Training Assignments</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right">
            <a href="{{ route('onboarding.training-assignments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Assign Training
            </a>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Training Assignments List</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Onboarding Process</label>
                        <select class="form-select" name="process_id" id="process_id">
                            <option value="">All Processes</option>
                            @foreach($processes as $process)
                                @php
                                    $name = 'N/A';
                                    if ($process->candidate) {
                                        $name = $process->candidate->full_name;
                                    } elseif ($process->employeeProfile && $process->employeeProfile->user) {
                                        $name = $process->employeeProfile->user->name;
                                    } elseif ($process->user) {
                                        $name = $process->user->name;
                                    }
                                @endphp
                                <option value="{{ $process->id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table id="trainingAssignmentsTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Training Module</th>
                            <th>Onboarding Process</th>
                            <th>Assigned Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this training assignment?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let table = $('#trainingAssignmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("onboarding.training-assignments.data") }}',
            data: function(d) {
                d.process_id = $('#process_id').val();
            },
            error: function(xhr, error, thrown) {
                console.error('DataTable Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Error loading training assignments. Please check the console for details.');
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'training_module', name: 'training_module' },
            { data: 'process_name', name: 'process_name' },
            { data: 'assigned_date', name: 'assigned_date' },
            { data: 'due_date', name: 'due_date' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'progress', name: 'progress' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return data || '';
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10
    });

    // Filter on process change
    $('#process_id').on('change', function() {
        table.draw();
    });

    // Delete handler
    let deleteId = null;
    window.deleteAssignment = function(id) {
        deleteId = id;
        $('#deleteModal').modal('show');
    };
    
    $(document).on('click', '.delete-assignment', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (deleteId) {
            $.ajax({
                url: '{{ route("onboarding.training-assignments.destroy", ":id") }}'.replace(':id', deleteId),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        table.draw();
                        alert('Training assignment deleted successfully.');
                    }
                },
                error: function(xhr) {
                    alert('Error deleting training assignment.');
                }
            });
        }
    });
});
</script>
@endsection
