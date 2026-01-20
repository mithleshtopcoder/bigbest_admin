@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Purchase Orders</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Purchase Orders</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('procurement.purchase-orders.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create Purchase Order
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
            <h5 class="card-title mb-0">Purchase Orders</h5>
        </div>
        <div class="card-body p-2">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search by PO number, supplier...">
                </div>
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="partially_received">Partially Received</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table id="purchaseOrdersTable" class="table table-hover table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Store</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
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
    const table = $('#purchaseOrdersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("procurement.purchase-orders.data") }}',
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
            { data: 'po_number' },
            { data: 'supplier' },
            { data: 'store' },
            { data: 'order_date' },
            { data: 'total_amount' },
            { data: 'status', orderable: false },
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-end',
                render: function(data, type, row, meta) {
                    const viewUrl = '{{ route("procurement.purchase-orders.view", ":id") }}'.replace(':id', row.id);
                    const statusRaw = row.status_raw || row.status; // Use raw status value
                    let buttons = '<div class="d-flex justify-content-end gap-1">';
                    
                    // View button
                    buttons += '<a href="' + viewUrl + '" class="btn btn-sm btn-primary" title="View"><i class="bi bi-eye-fill"></i></a>';
                    
                    // Approve button - show for pending or draft status
                    if (statusRaw === 'pending' || statusRaw === 'draft') {
                        buttons += '<button class="btn btn-sm btn-success" onclick="approvePO(' + row.id + ')" title="Approve"><i class="bi bi-check-circle-fill"></i></button>';
                    }
                    
                    // Reject/Cancel button - show for draft, pending, or approved status
                    if (statusRaw === 'draft' || statusRaw === 'pending' || statusRaw === 'approved') {
                        buttons += '<button class="btn btn-sm btn-danger" onclick="rejectPO(' + row.id + ')" title="Reject/Cancel"><i class="bi bi-x-circle-fill"></i></button>';
                    }
                    
                    buttons += '</div>';
                    return buttons;
                }
            }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    $('#searchInput').on('keyup', function() {
        table.draw();
    });

    $('#statusFilter').on('change', function() {
        table.draw();
    });
});

// Approve Purchase Order
function approvePO(id) {
    if (confirm('Are you sure you want to approve this purchase order?')) {
        $.ajax({
            url: '{{ route("procurement.purchase-orders.approve", ":id") }}'.replace(':id', id),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response && response.success) {
                    $('#purchaseOrdersTable').DataTable().ajax.reload();
                    alert(response.message || 'Purchase order approved successfully.');
                } else {
                    alert(response && response.message ? response.message : 'Error approving purchase order.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message ? response.message : 'Error approving purchase order.');
            }
        });
    }
}

// Reject/Cancel Purchase Order
function rejectPO(id) {
    if (confirm('Are you sure you want to reject/cancel this purchase order?')) {
        $.ajax({
            url: '{{ route("procurement.purchase-orders.cancel", ":id") }}'.replace(':id', id),
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response && response.success) {
                    $('#purchaseOrdersTable').DataTable().ajax.reload();
                    alert(response.message || 'Purchase order cancelled successfully.');
                } else {
                    alert(response && response.message ? response.message : 'Error cancelling purchase order.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response && response.message ? response.message : 'Error cancelling purchase order.');
            }
        });
    }
}
</script>
@endsection
