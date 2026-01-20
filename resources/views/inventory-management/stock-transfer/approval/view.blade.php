@extends('layouts.app')

@section('title', 'View Transfer for Approval')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Transfer #{{ $transfer->transfer_number }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory-management.stock-transfer.approval') }}" class="text-decoration-none">Approval</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            @if($transfer->status == 'pending' && auth()->user()->can('approve_stock_transfer'))
                <button onclick="approveTransfer({{ $transfer->id }})" class="btn btn-sm btn-success">Approve</button>
                <button onclick="rejectTransfer({{ $transfer->id }})" class="btn btn-sm btn-danger">Reject</button>
            @endif
            <a href="{{ route('inventory-management.stock-transfer.approval') }}" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>

<div class="">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Transfer Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Transfer Number:</strong><br>
                    {{ $transfer->transfer_number }}
                </div>
                <div class="col-md-3">
                    <strong>From Store:</strong><br>
                    {{ $transfer->fromStore->name ?? 'N/A' }}
                </div>
                <div class="col-md-3">
                    <strong>To Store:</strong><br>
                    {{ $transfer->toStore->name ?? 'N/A' }}
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-warning">Pending</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Items - Set Approved Quantities</h5>
        </div>
        <div class="card-body">
            <form id="approvalForm">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Requested Qty</th>
                                <th>Available Stock</th>
                                <th>Approved Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->items as $item)
                            @php
                                $stock = \App\Models\ProductStock::where('product_variant_id', $item->product_variant_id)
                                    ->where('store_id', $transfer->from_store_id)
                                    ->first();
                                $availableStock = $stock ? ($stock->quantity - $stock->reserved_quantity) : 0;
                            @endphp
                            <tr>
                                <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->productVariant->name ?? 'N/A' }}</td>
                                <td>{{ $item->requested_quantity }}</td>
                                <td>{{ $availableStock }}</td>
                                <td>
                                    <input type="number" 
                                           class="form-control approved-qty" 
                                           name="items[{{ $item->id }}][approved_quantity]" 
                                           value="{{ $item->requested_quantity }}" 
                                           min="0" 
                                           max="{{ min($item->requested_quantity, $availableStock) }}"
                                           required>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function approveTransfer(id) {
    if (confirm('Are you sure you want to approve this transfer? This will move stock to in-transit.')) {
        const formData = $('#approvalForm').serialize();
        
        $.ajax({
            url: '/inventory-management/stock-transfer/approval/' + id + '/approve',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error approving transfer.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error approving transfer.');
            }
        });
    }
}

function rejectTransfer(id) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        $.ajax({
            url: '/inventory-management/stock-transfer/approval/' + id + '/reject',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                rejection_reason: reason
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error rejecting transfer.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error rejecting transfer.');
            }
        });
    }
}
</script>
@endsection
