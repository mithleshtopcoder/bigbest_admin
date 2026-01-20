@extends('layouts.app')

@section('title', 'Salary Structure - ' . $employee->user->name)

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Salary Structure - {{ $employee->user->name }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('employee-management.salary-structure.list') }}" class="text-decoration-none">Salary Structure</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $employee->user->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('employee-management.salary-structure.create', $employee->user->uuid) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Add Salary Structure
            </a>
            <a href="{{ route('employee-management.salary-structure.list') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back to List
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
            <h5 class="card-title mb-0">Salary Structures</h5>
        </div>
        <div class="card-body p-2">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <select class="form-select form-select-sm" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-9">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0" id="salaryStructuresTable">
                    <thead>
                        <tr class="border-b">
                            <th scope="col">No</th>
                            <th scope="col">Basic Salary</th>
                            <th scope="col">Gross Salary</th>
                            <th scope="col">Net Salary</th>
                            <th scope="col">Effective Date</th>
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
    const employeeUuid = '{{ $employee->user->uuid }}';
    const table = $('#salaryStructuresTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("employee-management.salary-structure.data", ":uuid") }}'.replace(':uuid', employeeUuid),
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
            { data: 'basic_salary' },
            { data: 'gross_salary' },
            { data: 'net_salary' },
            { data: 'effective_date' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    if (!row || !row.id) return '<span class="text-muted">-</span>';
                    
                    const id = row.id;
                    const status = row.status_value || 'inactive';
                    const editUrl = '{{ route("employee-management.salary-structure.edit", [":uuid", ":id"]) }}'.replace(':uuid', employeeUuid).replace(':id', id);
                    const deleteUrl = '{{ route("employee-management.salary-structure.destroy", [":uuid", ":id"]) }}'.replace(':uuid', employeeUuid).replace(':id', id);
                    const statusUrl = '{{ route("employee-management.salary-structure.status", [":uuid", ":id"]) }}'.replace(':uuid', employeeUuid).replace(':id', id);
                    const csrfToken = '{{ csrf_token() }}';
                    const newStatus = status === 'active' ? 'inactive' : 'active';
                    const statusText = status === 'active' ? 'Deactivate' : 'Activate';
                    const statusClass = status === 'active' ? 'text-warning' : 'text-success';
                    
                    return '<div class="d-flex align-items-center gap-1 justify-content-end">' +
                           '<a href="' + editUrl + '" class="btn btn-sm btn-link text-primary p-1" title="Edit"><i class="bi bi-pencil"></i></a>' +
                           '<a href="javascript:void(0);" class="btn btn-sm btn-link ' + statusClass + ' p-1" onclick="changeStatus(\'' + id + '\', \'' + newStatus + '\', \'' + statusUrl + '\')" title="' + statusText + '"><i class="bi ' + (status === 'active' ? 'bi-x-circle' : 'bi-check-circle') + '"></i></a>' +
                           '<form action="' + deleteUrl + '" method="POST" class="d-inline" onsubmit="return confirm(\'Are you sure?\');">' +
                           '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                           '<input type="hidden" name="_method" value="DELETE">' +
                           '<button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete"><i class="bi bi-trash"></i></button>' +
                           '</form>' +
                           '</div>';
                }
            }
        ],
        order: [[4, 'desc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: '<i class="bi bi-hourglass-split"></i> Loading...',
            emptyTable: 'No salary structures found',
            zeroRecords: 'No matching salary structures found'
        }
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        table.draw();
    });
});

function changeStatus(id, newStatus, url) {
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            status: newStatus === 'active' ? 1 : 0
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#salaryStructuresTable').DataTable().draw();
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message || 'Failed to update status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'An error occurred while updating status.');
    });
}

function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.main-body');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}
</script>
@endsection

