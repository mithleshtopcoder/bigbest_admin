@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 px-2">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Support Tickets</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Support Tickets</li>
                </ol>
            </nav>
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
            <h5 class="card-title mb-0">Support Tickets</h5>
            <div class="d-flex gap-1">
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
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="priorityFilter">
                        <option value="">All Priority</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="assignedFilter">
                        <option value="">All Assignments</option>
                        <option value="unassigned">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search tickets...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ticketsTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">Ticket ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col">Assigned To</th>
                            <th scope="col">Created</th>
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

<!-- View Ticket Modal -->
<div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewTicketModalLabel">Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="ticketDetails">
                <!-- Ticket details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Assign Ticket Modal -->
<div class="modal fade" id="assignTicketModal" tabindex="-1" aria-labelledby="assignTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTicketModalLabel">Assign Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignTicketForm">
                <div class="modal-body">
                    <input type="hidden" id="assignTicketId" name="ticket_id">
                    <div class="mb-3">
                        <label for="assignToUser" class="form-label">Assign To</label>
                        <select class="form-select" id="assignToUser" name="assigned_to" required>
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm">
                <div class="modal-body">
                    <input type="hidden" id="updateStatusTicketId" name="ticket_id">
                    <div class="mb-3">
                        <label for="ticketStatus" class="form-label">Status</label>
                        <select class="form-select" id="ticketStatus" name="status" required>
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                            <option value="closed">Closed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="resolutionNotes" class="form-label">Resolution Notes (Optional)</label>
                        <textarea class="form-control" id="resolutionNotes" name="resolution_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reply Ticket Modal -->
<div class="modal fade" id="replyTicketModal" tabindex="-1" aria-labelledby="replyTicketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="replyTicketModalLabel">Reply to Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="replyTicketForm">
                <div class="modal-body">
                    <input type="hidden" id="replyTicketId" name="ticket_id">
                    <div class="mb-3">
                        <label for="replyMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="replyMessage" name="message" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isInternal" name="is_internal" value="1">
                            <label class="form-check-label" for="isInternal">
                                Internal Note (Visible only to admins)
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var table = $('#ticketsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('support.tickets.data') }}",
            type: "GET",
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.priority = $('#priorityFilter').val();
                d.assigned_to = $('#assignedFilter').val();
            }
        },
        columns: [
            { 
                data: 'ticket_number', 
                name: 'ticket_number',
                render: function(data, type, row) {
                    return '<span class="fw-medium">' + data + '</span>';
                }
            },
            { 
                data: 'customer', 
                name: 'customer',
                render: function(data, type, row) {
                    var html = '<div><span class="fw-medium">' + data + '</span></div>';
                    if (row.customer_email) {
                        html += '<small class="text-muted">' + row.customer_email + '</small>';
                    }
                    return html;
                }
            },
            { 
                data: 'subject', 
                name: 'subject',
                render: function(data, type, row) {
                    return '<span class="text-truncate d-inline-block" style="max-width: 200px;" title="' + data + '">' + data + '</span>';
                }
            },
            { 
                data: 'priority', 
                name: 'priority',
                orderable: false,
                searchable: false
            },
            { 
                data: 'status', 
                name: 'status',
                orderable: false,
                searchable: false
            },
            { 
                data: 'assigned_to', 
                name: 'assigned_to',
                render: function(data, type, row) {
                    if (data === 'Unassigned') {
                        return '<span class="text-muted">Unassigned</span>';
                    }
                    return data;
                }
            },
            { 
                data: 'created_at', 
                name: 'created_at'
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var actions = '<div class="d-flex justify-content-end gap-1">';
                    actions += '<button class="btn btn-xs btn-info view-ticket" data-id="' + row.id + '" data-bs-toggle="tooltip" title="View"><i class="bi bi-eye"></i></button>';
                    if (!row.assigned_to_id) {
                        actions += '<button class="btn btn-xs btn-primary assign-ticket" data-id="' + row.id + '" data-bs-toggle="tooltip" title="Assign"><i class="bi bi-person-plus"></i></button>';
                    }
                    actions += '<button class="btn btn-xs btn-warning update-status" data-id="' + row.id + '" data-status="' + row.status_raw + '" data-bs-toggle="tooltip" title="Update Status"><i class="bi bi-arrow-repeat"></i></button>';
                    actions += '<button class="btn btn-xs btn-success reply-ticket" data-id="' + row.id + '" data-bs-toggle="tooltip" title="Reply"><i class="bi bi-reply"></i></button>';
                    actions += '</div>';
                    return actions;
                }
            }
        ],
        order: [[6, 'desc']],
        pageLength: 25
    });

    // Filter handlers
    $('#statusFilter, #priorityFilter, #assignedFilter').on('change', function() {
        table.draw();
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // View ticket
    $(document).on('click', '.view-ticket', function() {
        var ticketId = $(this).data('id');
        $.ajax({
            url: "{{ route('support.tickets.show', ':id') }}".replace(':id', ticketId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var ticket = response.data;
                    var customerName = ticket.customer ? (ticket.customer.first_name + ' ' + (ticket.customer.last_name || '')) : 'N/A';
                    var assignedTo = ticket.assigned_user ? ticket.assigned_user.name : 'Unassigned';
                    
                    var html = '<div class="ticket-details">';
                    html += '<div class="mb-3"><strong>Ticket Number:</strong> ' + ticket.ticket_number + '</div>';
                    html += '<div class="mb-3"><strong>Customer:</strong> ' + customerName + ' (' + (ticket.customer?.email || 'N/A') + ')</div>';
                    html += '<div class="mb-3"><strong>Subject:</strong> ' + ticket.subject + '</div>';
                    html += '<div class="mb-3"><strong>Description:</strong><br><div class="p-2 bg-light rounded">' + ticket.description + '</div></div>';
                    html += '<div class="mb-3"><strong>Priority:</strong> <span class="badge bg-' + getPriorityColor(ticket.priority) + '">' + ticket.priority.toUpperCase() + '</span></div>';
                    html += '<div class="mb-3"><strong>Status:</strong> <span class="badge bg-' + getStatusColor(ticket.status) + '">' + ticket.status.replace('_', ' ').toUpperCase() + '</span></div>';
                    html += '<div class="mb-3"><strong>Assigned To:</strong> ' + assignedTo + '</div>';
                    html += '<div class="mb-3"><strong>Created:</strong> ' + new Date(ticket.created_at).toLocaleString() + '</div>';
                    
                    if (ticket.replies && ticket.replies.length > 0) {
                        html += '<hr><h6>Conversation:</h6><div class="conversation">';
                        ticket.replies.forEach(function(reply) {
                            var isAdmin = reply.replied_by_type === 'admin';
                            var replierName = isAdmin ? (reply.admin?.name || 'Admin') : (reply.customer ? (reply.customer.first_name + ' ' + (reply.customer.last_name || '')) : 'Customer');
                            html += '<div class="mb-3 p-2 border rounded ' + (isAdmin ? 'bg-light' : '') + '">';
                            html += '<div class="d-flex justify-content-between mb-1">';
                            html += '<strong>' + replierName + '</strong>';
                            html += '<small class="text-muted">' + new Date(reply.created_at).toLocaleString() + '</small>';
                            html += '</div>';
                            html += '<div>' + reply.message + '</div>';
                            if (reply.is_internal) {
                                html += '<small class="text-muted"><i class="bi bi-lock"></i> Internal Note</small>';
                            }
                            html += '</div>';
                        });
                        html += '</div>';
                    }
                    
                    html += '</div>';
                    $('#ticketDetails').html(html);
                    $('#viewTicketModal').modal('show');
                }
            },
            error: function() {
                alert('Error loading ticket details');
            }
        });
    });

    // Assign ticket
    $(document).on('click', '.assign-ticket', function() {
        var ticketId = $(this).data('id');
        $('#assignTicketId').val(ticketId);
        $('#assignTicketModal').modal('show');
    });

    $('#assignTicketForm').on('submit', function(e) {
        e.preventDefault();
        var ticketId = $('#assignTicketId').val();
        $.ajax({
            url: "{{ route('support.tickets.assign', ':id') }}".replace(':id', ticketId),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#assignTicketModal').modal('hide');
                    table.draw();
                    alert('Ticket assigned successfully');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors || {};
                var errorMsg = 'Error assigning ticket';
                if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    // Update status
    $(document).on('click', '.update-status', function() {
        var ticketId = $(this).data('id');
        var currentStatus = $(this).data('status');
        $('#updateStatusTicketId').val(ticketId);
        $('#ticketStatus').val(currentStatus);
        $('#updateStatusModal').modal('show');
    });

    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        var ticketId = $('#updateStatusTicketId').val();
        $.ajax({
            url: "{{ route('support.tickets.status', ':id') }}".replace(':id', ticketId),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#updateStatusModal').modal('hide');
                    table.draw();
                    alert('Status updated successfully');
                }
            },
            error: function(xhr) {
                var errorMsg = 'Error updating status';
                if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    // Reply to ticket
    $(document).on('click', '.reply-ticket', function() {
        var ticketId = $(this).data('id');
        $('#replyTicketId').val(ticketId);
        $('#replyMessage').val('');
        $('#isInternal').prop('checked', false);
        $('#replyTicketModal').modal('show');
    });

    $('#replyTicketForm').on('submit', function(e) {
        e.preventDefault();
        var ticketId = $('#replyTicketId').val();
        $.ajax({
            url: "{{ route('support.tickets.reply', ':id') }}".replace(':id', ticketId),
            type: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#replyTicketModal').modal('hide');
                    table.draw();
                    alert('Reply sent successfully');
                }
            },
            error: function(xhr) {
                var errorMsg = 'Error sending reply';
                if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            }
        });
    });

    function getPriorityColor(priority) {
        var colors = {
            'urgent': 'danger',
            'high': 'warning',
            'medium': 'info',
            'low': 'secondary'
        };
        return colors[priority] || 'secondary';
    }

    function getStatusColor(status) {
        var colors = {
            'open': 'warning',
            'in_progress': 'info',
            'resolved': 'success',
            'closed': 'secondary',
            'cancelled': 'danger'
        };
        return colors[status] || 'secondary';
    }
});
</script>
@endsection
