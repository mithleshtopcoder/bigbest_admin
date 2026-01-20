@extends('layouts.app')

@section('title', 'Attendance Management')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Attendance Management</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Attendance</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-success" id="punchInBtn" data-bs-toggle="modal" data-bs-target="#punchInModal">
                <i class="bi bi-clock-history me-2"></i>Punch In
            </button>
            <button type="button" class="btn btn-sm btn-danger" id="punchOutBtn" data-bs-toggle="modal" data-bs-target="#punchOutModal">
                <i class="bi bi-clock me-2"></i>Punch Out
            </button>
            <a href="{{ route('employee-management.attendance.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Attendance
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
            <h5 class="card-title mb-0">Attendance List</h5>
        </div>
        <div class="card-body p-2">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="employeeFilter">
                        <option value="">All Employees</option>
                        @foreach(\App\Models\EmployeeProfile::with('user')->whereHas('user', function($q) { $q->where('status', 1); })->get() as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->user->name ?? 'N/A' }} ({{ $emp->employee_code ?? 'N/A' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" id="dateFromFilter" placeholder="From Date">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control form-control-sm" id="dateToFilter" placeholder="To Date">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="half_day">Half Day</option>
                        <option value="leave">Leave</option>
                        <option value="holiday">Holiday</option>
                        <option value="weekend">Weekend</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="attendanceTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">No</th>
                            <th scope="col">Employee Code</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Date</th>
                            <th scope="col">Punch In</th>
                            <th scope="col">Punch Out</th>
                            <th scope="col">Total Hours</th>
                            <th scope="col">Status</th>
                            <th scope="col">Type</th>
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

<!-- Punch In Modal -->
<div class="modal fade" id="punchInModal" tabindex="-1" aria-labelledby="punchInModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="punchInModalLabel">Punch In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="punchInForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="punchInEmployee" class="form-label">Employee <span class="text-danger">*</span></label>
                        <select class="form-select" id="punchInEmployee" name="employee_profile_id" required>
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\EmployeeProfile::with('user')->whereHas('user', function($q) { $q->where('status', 1); })->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->user->name ?? 'N/A' }} ({{ $emp->employee_code ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="punchInDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="punchInDate" name="attendance_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Punch In</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Punch Out Modal -->
<div class="modal fade" id="punchOutModal" tabindex="-1" aria-labelledby="punchOutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="punchOutModalLabel">Punch Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="punchOutForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="punchOutEmployee" class="form-label">Employee <span class="text-danger">*</span></label>
                        <select class="form-select" id="punchOutEmployee" name="employee_profile_id" required>
                            <option value="">Select Employee</option>
                            @foreach(\App\Models\EmployeeProfile::with('user')->whereHas('user', function($q) { $q->where('status', 1); })->get() as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->user->name ?? 'N/A' }} ({{ $emp->employee_code ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="punchOutDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="punchOutDate" name="attendance_date" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Punch Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const table = $('#attendanceTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-management.attendance.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.employee_id = $('#employeeFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
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
            { data: 'employee_code' },
            { data: 'employee_name' },
            { data: 'attendance_date' },
            { data: 'punch_in' },
            { data: 'punch_out' },
            { data: 'total_hours' },
            { data: 'status', orderable: false },
            { data: 'attendance_type' },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!row || !row.id) return '<span class="text-muted">-</span>';
                    
                    const id = row.id;
                    const editUrl = '{{ route("employee-management.attendance.edit", ":id") }}'.replace(':id', id);
                    const deleteUrl = '{{ route("employee-management.attendance.destroy", ":id") }}'.replace(':id', id);
                    const csrfToken = '{{ csrf_token() }}';
                    
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="' + editUrl + '" class="btn btn-sm btn-link text-primary p-1" title="Edit"><i class="bi bi-pencil"></i></a>' +
                           '<form action="' + deleteUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">' +
                           '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                           '<input type="hidden" name="_method" value="DELETE">' +
                           '<button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete"><i class="bi bi-trash"></i></button>' +
                           '</form>' +
                           '</div>';
                }
            }
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Search and filter handlers
    $('#searchInput, #employeeFilter, #dateFromFilter, #dateToFilter, #statusFilter').on('change keyup', function() {
        table.draw();
    });

    // Punch In Form
    $('#punchInForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("employee-management.attendance.punch-in") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#punchInModal').modal('hide');
                    $('#punchInForm')[0].reset();
                    table.draw();
                    alert('Punched in successfully at ' + response.data.punch_in);
                } else {
                    alert(response.message || 'Failed to punch in');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response.message || 'Failed to punch in');
            }
        });
    });

    // Punch Out Form
    $('#punchOutForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        
        $.ajax({
            url: '{{ route("employee-management.attendance.punch-out") }}',
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#punchOutModal').modal('hide');
                    $('#punchOutForm')[0].reset();
                    table.draw();
                    alert('Punched out successfully. Total hours: ' + response.data.total_hours);
                } else {
                    alert(response.message || 'Failed to punch out');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response.message || 'Failed to punch out');
            }
        });
    });
});
</script>
@endsection
