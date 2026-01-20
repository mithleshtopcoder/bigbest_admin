@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Expenses</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item">Finance & Accounting</li>
            <li class="breadcrumb-item">Expenses</li>
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <a href="{{ route('finance-accounting.expenses.create') }}" class="btn btn-primary">
                <i class="feather-plus me-2"></i>Add Expense
            </a>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->

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

<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <!-- [Expenses List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Expenses List</h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select id="categoryFilter" class="form-select form-select-sm">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select id="statusFilter" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Store</label>
                            <select id="storeFilter" class="form-select form-select-sm">
                                <option value="">All Stores</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" id="dateFromFilter" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3 mt-2">
                            <label class="form-label">Date To</label>
                            <input type="date" id="dateToFilter" class="form-control form-control-sm">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="expensesTable" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Expense ID</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Store</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Payment Method</th>
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
        <!-- [Expenses List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Load filter options
    loadFilterOptions();

    var table = $('#expensesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('finance-accounting.expenses.data') }}",
            type: "GET",
            data: function(d) {
                d.category_id = $('#categoryFilter').val();
                d.status_id = $('#statusFilter').val();
                d.store_id = $('#storeFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
            }
        },
        columns: [
            {
                data: 'expense_number',
                name: 'expense_number',
                render: function(data, type, row) {
                    return '<div class="d-flex align-items-center gap-3">' +
                           '<div class="avatar-text">' +
                           '<i class="feather-credit-card"></i>' +
                           '</div>' +
                           '<a href="javascript:void(0);" class="text-decoration-none">' +
                           '<span class="d-block fw-medium">' + data + '</span>' +
                           '</a>' +
                           '</div>';
                }
            },
            {
                data: 'title',
                name: 'title'
            },
            {
                data: 'category',
                name: 'category'
            },
            {
                data: 'store',
                name: 'store'
            },
            {
                data: 'amount',
                name: 'amount',
                render: function(data) {
                    return '<span class="fw-bold text-danger">₹' + data + '</span>';
                }
            },
            {
                data: 'expense_date',
                name: 'expense_date'
            },
            {
                data: 'payment_method',
                name: 'payment_method'
            },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    var badgeClass = 'bg-soft-success text-success';
                    if (row.status_id) {
                        // You can customize badge colors based on status
                        badgeClass = 'bg-soft-success text-success';
                    }
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="d-flex align-items-center gap-2 justify-content-end">' +
                           '<div class="dropdown">' +
                           '<a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">' +
                           '<i class="feather-more-vertical"></i>' +
                           '</a>' +
                           '<div class="dropdown-menu dropdown-menu-end">' +
                           '<a href="{{ route("finance-accounting.expenses.edit", ":id") }}'.replace(':id', row.id) + '" class="dropdown-item">' +
                           '<i class="feather-edit me-2"></i>Edit' +
                           '</a>' +
                           '<a href="{{ route("finance-accounting.expenses.show", ":id") }}'.replace(':id', row.id) + '" class="dropdown-item">' +
                           '<i class="feather-eye me-2"></i>View' +
                           '</a>' +
                           '<a href="javascript:void(0);" onclick="deleteExpense(' + row.id + ')" class="dropdown-item text-danger">' +
                           '<i class="feather-trash-2 me-2"></i>Delete' +
                           '</a>' +
                           '</div>' +
                           '</div>' +
                           '</div>';
                }
            }
        ],
        order: [[5, 'desc']], // Order by date descending
        pageLength: 25
    });

    // Apply filters
    $('#categoryFilter, #statusFilter, #storeFilter, #dateFromFilter, #dateToFilter').on('change', function() {
        table.draw();
    });

    function loadFilterOptions() {
        // Load categories
        $.ajax({
            url: "{{ route('finance-accounting.expenses.data') }}",
            type: 'GET',
            data: { filter_options: 'categories' },
            success: function(response) {
                // This would need to be implemented in the controller
            }
        });

        // Load stores
        $.ajax({
            url: "{{ route('store-management.manage-store.data') }}",
            type: 'GET',
            success: function(response) {
                if (response.data) {
                    response.data.forEach(function(store) {
                        $('#storeFilter').append('<option value="' + store.id + '">' + store.name + '</option>');
                    });
                }
            }
        });
    }

    function deleteExpense(id) {
        if (confirm('Are you sure you want to delete this expense?')) {
            $.ajax({
                url: "{{ route('finance-accounting.expenses.destroy', ':id') }}".replace(':id', id),
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        table.draw();
                        alert('Expense deleted successfully.');
                    } else {
                        alert('Failed to delete expense: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred while deleting the expense.');
                }
            });
        }
    }
});
</script>
@endsection
