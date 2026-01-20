@extends('layouts.app')

@section('title', 'Payroll Processing')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Payroll Processing</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payroll</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.payroll.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Process Payroll
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

@if(session('errors'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Note:</strong> Some errors occurred during processing:
        <ul class="mb-0 mt-2">
            @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Payroll List</h5>
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
                    <input type="month" class="form-control form-control-sm" id="periodFilter" value="{{ date('Y-m') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="processed">Processed</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="payrollTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">No</th>
                            <th scope="col">Employee Code</th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Period</th>
                            <th scope="col">Present Days</th>
                            <th scope="col">Absent Days</th>
                            <th scope="col">Leave Days</th>
                            <th scope="col">Gross Salary</th>
                            <th scope="col">Net Salary</th>
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
    const table = $('#payrollTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-management.payroll.data") }}',
            data: function(d) {
                d.search = $('#searchInput').val();
                d.employee_id = $('#employeeFilter').val();
                d.payroll_period = $('#periodFilter').val();
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
            { data: 'period_range' },
            { data: 'present_days' },
            { data: 'absent_days' },
            { data: 'leave_days' },
            { data: 'gross_salary' },
            { data: 'net_salary' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!row || !row.id) return '<span class="text-muted">-</span>';
                    
                    const id = row.id;
                    const status = row.status_value || 'draft';
                    const viewUrl = '{{ route("employee-management.payroll.show", ":id") }}'.replace(':id', id);
                    const deleteUrl = '{{ route("employee-management.payroll.destroy", ":id") }}'.replace(':id', id);
                    const csrfToken = '{{ csrf_token() }}';
                    
                    let statusButtons = '';
                    if (status === 'draft') {
                        statusButtons = '<a href="javascript:void(0);" class="btn btn-sm btn-link text-info p-1" onclick="changeStatus(\'' + id + '\', \'processed\')" title="Mark as Processed"><i class="bi bi-check-circle"></i></a>';
                    } else if (status === 'processed') {
                        statusButtons = '<a href="javascript:void(0);" class="btn btn-sm btn-link text-primary p-1" onclick="changeStatus(\'' + id + '\', \'approved\')" title="Approve"><i class="bi bi-check2-circle"></i></a>';
                    } else if (status === 'approved') {
                        statusButtons = '<a href="javascript:void(0);" class="btn btn-sm btn-link text-success p-1" onclick="changeStatus(\'' + id + '\', \'paid\')" title="Mark as Paid"><i class="bi bi-currency-dollar"></i></a>';
                    }
                    
                    const downloadPdfUrl = '{{ route("employee-management.payroll.download-payslip", ":id") }}'.replace(':id', id);
                    
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="' + viewUrl + '" class="btn btn-sm btn-link text-primary p-1" title="View"><i class="bi bi-eye"></i></a>' +
                           '<a href="' + downloadPdfUrl + '" class="btn btn-sm btn-link text-danger p-1" title="Download PDF" target="_blank"><i class="bi bi-file-earmark-pdf"></i></a>' +
                           statusButtons +
                           (status !== 'paid' ? '<form action="' + deleteUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">' +
                           '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                           '<input type="hidden" name="_method" value="DELETE">' +
                           '<button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete"><i class="bi bi-trash"></i></button>' +
                           '</form>' : '') +
                           '</div>';
                }
            }
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Search and filter handlers
    $('#searchInput, #employeeFilter, #periodFilter, #statusFilter').on('change keyup', function() {
        table.draw();
    });
});

function changeStatus(id, status) {
    if (!confirm('Are you sure you want to change the status to ' + status + '?')) {
        return;
    }
    
    const url = '{{ route("employee-management.payroll.status", ":id") }}'.replace(':id', id);
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: status
        },
        success: function(response) {
            if (response.success) {
                $('#payrollTable').DataTable().draw();
                alert('Payroll status updated successfully');
            } else {
                alert(response.message || 'Failed to update status');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            alert(response.message || 'Failed to update status');
        }
    });
}
</script>
@endsection

