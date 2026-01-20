@extends('layouts.app')

@section('title', 'Joining Checklist')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Joining Checklist</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Joining Checklist</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Checklist Items</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Select Onboarding Process</label>
                    <form method="GET" action="{{ route('onboarding.checklist') }}" id="processForm">
                        <select name="process_id" class="form-select" onchange="document.getElementById('processForm').submit();">
                            <option value="">-- Select Process --</option>
                            @foreach($processes as $proc)
                                <option value="{{ $proc['id'] }}" {{ request('process_id') == $proc['id'] ? 'selected' : '' }}>
                                    {{ $proc['name'] }} - {{ $proc['joining_date'] }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                @if($process)
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addChecklistModal">
                        <i class="bi bi-plus-circle me-2"></i>Add Checklist
                    </button>
                    <a href="{{ route('onboarding.checklist-templates') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-list-check me-2"></i>Manage Templates
                    </a>
                </div>
                @endif
            </div>
            
            @if($process)
                <div class="alert alert-info mb-3">
                    <strong>Onboarding Process:</strong> {{ $process->user->name ?? $process->candidate->full_name ?? 'N/A' }} - Joining Date: {{ $process->joining_date }}
                </div>
                <div class="table-responsive">
                    <table id="checklistTable" class="table table-hover table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Task Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Due Date</th>
                                <th>Completed Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    Please select an onboarding process to view checklist.
                </div>
            @endif
        </div>

        <!-- Add Checklist Modal -->
        @if($process)
        <div class="modal fade" id="addChecklistModal" tabindex="-1" aria-labelledby="addChecklistModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addChecklistModalLabel">Add Checklist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addChecklistForm">
                        <div class="modal-body">
                            <input type="hidden" name="onboarding_process_id" value="{{ $process->id }}">
                            <div class="mb-3">
                                <label class="form-label">Task Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="task_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" name="task_category" placeholder="e.g., HR, IT, Admin">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assigned To</label>
                                    <select class="form-select" name="assigned_to">
                                        <option value="">Select User</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" class="form-control" name="due_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="pending">Pending</option>
                                        <option value="in-progress">In Progress</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Checklist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Checklist Modal -->
        <div class="modal fade" id="editChecklistModal" tabindex="-1" aria-labelledby="editChecklistModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editChecklistModalLabel">Edit Checklist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editChecklistForm">
                        <div class="modal-body">
                            <input type="hidden" id="edit_checklist_id" name="id">
                            <div class="mb-3">
                                <label class="form-label">Task Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_task_name" name="task_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-control" id="edit_task_category" name="task_category">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assigned To</label>
                                    <select class="form-select" id="edit_assigned_to" name="assigned_to">
                                        <option value="">Select User</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" class="form-control" id="edit_due_date" name="due_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" id="edit_status" name="status">
                                        <option value="pending">Pending</option>
                                        <option value="in-progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                        <option value="skipped">Skipped</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Checklist</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assign Checklist Modal -->
        <div class="modal fade" id="assignChecklistModal" tabindex="-1" aria-labelledby="assignChecklistModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignChecklistModalLabel">Assign Checklist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="assignChecklistForm">
                        <div class="modal-body">
                            <input type="hidden" id="assign_checklist_id" name="id">
                            <div class="mb-3">
                                <label class="form-label">Assigned To <span class="text-danger">*</span></label>
                                <select class="form-select" id="assign_assigned_to" name="assigned_to" required>
                                    <option value="">Select User</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="assign_due_date" name="due_date">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($process)
<script>
$(document).ready(function() {
    const table = $('#checklistTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("onboarding.checklist.data") }}',
            data: function(d) {
                d.process_id = '{{ $process->id }}';
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
            { data: 'status', orderable: false },
            { data: 'assigned_to' },
            { data: 'due_date' },
            { data: 'completed_date' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    let actions = '<div class="d-flex align-items-center gap-1 justify-content-end">';
                    
                    // Assign button
                    actions += '<button onclick="openAssignModal(' + row.id + ')" class="btn btn-sm btn-link text-primary p-1" title="Assign">' +
                        '<i class="bi bi-person-plus"></i>' +
                        '</button>';
                    
                    // Edit button
                    actions += '<button onclick="openEditModal(' + row.id + ')" class="btn btn-sm btn-link text-info p-1" title="Edit">' +
                        '<i class="bi bi-pencil"></i>' +
                        '</button>';
                    
                    // Complete button
                    if (row.status_raw !== 'completed') {
                        actions += '<button onclick="updateStatus(' + row.id + ', \'completed\')" class="btn btn-sm btn-link text-success p-1" title="Mark Complete">' +
                            '<i class="bi bi-check-circle"></i>' +
                            '</button>';
                    }
                    
                    // Delete button
                    actions += '<button onclick="deleteChecklist(' + row.id + ')" class="btn btn-sm btn-link text-danger p-1" title="Delete">' +
                        '<i class="bi bi-trash"></i>' +
                        '</button>';
                    
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25
    });
});

function updateStatus(id, status) {
    $.ajax({
        url: '{{ route("onboarding.checklist.update-status", ":id") }}'.replace(':id', id),
        type: 'POST',
        data: {
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                $('#checklistTable').DataTable().draw();
                alert('Checklist status updated successfully.');
            }
        }
    });
}

function openAssignModal(id) {
    $('#assign_checklist_id').val(id);
    $('#assignChecklistModal').modal('show');
}

function openEditModal(id) {
    // Fetch checklist data
    $.ajax({
        url: '{{ route("onboarding.checklist.show", ":id") }}'.replace(':id', id),
        type: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#edit_checklist_id').val(data.id);
                $('#edit_task_name').val(data.task_name || '');
                $('#edit_description').val(data.description || '');
                $('#edit_task_category').val(data.task_category || '');
                $('#edit_assigned_to').val(data.assigned_to || '');
                $('#edit_due_date').val(data.due_date || '');
                $('#edit_status').val(data.status || 'pending');
                $('#editChecklistModal').modal('show');
            }
        },
        error: function(xhr) {
            alert('Error loading checklist data.');
        }
    });
}

function deleteChecklist(id) {
    if (confirm('Are you sure you want to delete this checklist?')) {
        $.ajax({
            url: '{{ route("onboarding.checklist.destroy", ":id") }}'.replace(':id', id),
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#checklistTable').DataTable().draw();
                    alert('Checklist deleted successfully.');
                }
            },
            error: function(xhr) {
                alert('Error deleting checklist.');
            }
        });
    }
}

// Add Checklist Form
$('#addChecklistForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("onboarding.checklist.store") }}',
        type: 'POST',
        data: $(this).serialize() + '&_token={{ csrf_token() }}',
        success: function(response) {
            if (response.success) {
                $('#addChecklistModal').modal('hide');
                $('#addChecklistForm')[0].reset();
                $('#checklistTable').DataTable().draw();
                alert('Checklist added successfully.');
            }
        },
        error: function(xhr) {
            alert('Error adding checklist.');
        }
    });
});

// Edit Checklist Form
$('#editChecklistForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#edit_checklist_id').val();
    $.ajax({
        url: '{{ route("onboarding.checklist.update", ":id") }}'.replace(':id', id),
        type: 'PUT',
        data: $(this).serialize() + '&_token={{ csrf_token() }}',
        success: function(response) {
            if (response.success) {
                $('#editChecklistModal').modal('hide');
                $('#checklistTable').DataTable().draw();
                alert('Checklist updated successfully.');
            }
        },
        error: function(xhr) {
            alert('Error updating checklist.');
        }
    });
});

// Assign Checklist Form
$('#assignChecklistForm').on('submit', function(e) {
    e.preventDefault();
    const id = $('#assign_checklist_id').val();
    $.ajax({
        url: '{{ route("onboarding.checklist.assign", ":id") }}'.replace(':id', id),
        type: 'POST',
        data: $(this).serialize() + '&_token={{ csrf_token() }}',
        success: function(response) {
            if (response.success) {
                $('#assignChecklistModal').modal('hide');
                $('#checklistTable').DataTable().draw();
                alert('Checklist assigned successfully.');
            }
        },
        error: function(xhr) {
            alert('Error assigning checklist.');
        }
    });
});
</script>
@endif
@endsection
