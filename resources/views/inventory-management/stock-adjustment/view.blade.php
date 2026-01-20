@extends('layouts.app')

@section('title', 'View Stock Adjustment')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Stock Adjustment #{{ $adjustment->adjustment_number }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory-management.stock-adjustment') }}" class="text-decoration-none">Stock Adjustment</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            @if($adjustment->status == 'pending' && auth()->user()->can('approve_stock_adjustment'))
                <button onclick="approveAdjustment({{ $adjustment->id }})" class="btn btn-sm btn-success">Approve</button>
                <button onclick="rejectAdjustment({{ $adjustment->id }})" class="btn btn-sm btn-danger">Reject</button>
            @endif
            <a href="{{ route('inventory-management.stock-adjustment') }}" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>

<div class="">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Adjustment Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Adjustment Number:</strong><br>
                    {{ $adjustment->adjustment_number }}
                </div>
                <div class="col-md-3">
                    <strong>Store:</strong><br>
                    {{ $adjustment->store->name ?? 'N/A' }}
                </div>
                <div class="col-md-3">
                    <strong>Type:</strong><br>
                    <span class="badge bg-{{ $adjustment->type == 'addition' ? 'success' : ($adjustment->type == 'reduction' ? 'danger' : 'info') }}">
                        {{ ucfirst($adjustment->type) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-{{ $adjustment->status == 'completed' ? 'success' : ($adjustment->status == 'pending' ? 'warning' : ($adjustment->status == 'rejected' ? 'danger' : 'primary')) }}">
                        {{ ucfirst($adjustment->status) }}
                    </span>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <strong>Reason:</strong><br>
                    {{ ucfirst(str_replace('_', ' ', $adjustment->reason)) }}
                </div>
                <div class="col-md-3">
                    <strong>Adjustment Date:</strong><br>
                    {{ $adjustment->adjustment_date->format('d M Y') }}
                </div>
                <div class="col-md-3">
                    <strong>Created By:</strong><br>
                    {{ $adjustment->createdBy->name ?? 'N/A' }}
                </div>
                <div class="col-md-3">
                    <strong>Total Value:</strong><br>
                    ₹{{ number_format($adjustment->total_value, 2) }}
                </div>
            </div>
            @if($adjustment->reason_description)
            <div class="row mt-3">
                <div class="col-md-12">
                    <strong>Reason Description:</strong><br>
                    {{ $adjustment->reason_description }}
                </div>
            </div>
            @endif
            @if($adjustment->notes)
            <div class="row mt-3">
                <div class="col-md-12">
                    <strong>Notes:</strong><br>
                    {{ $adjustment->notes }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Items</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Current Qty</th>
                            <th>Adjusted Qty</th>
                            <th>New Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adjustment->items as $item)
                        <tr>
                            <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->productVariant->name ?? 'N/A' }}</td>
                            <td>{{ $item->current_quantity }}</td>
                            <td class="{{ $item->adjusted_quantity >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $item->adjusted_quantity >= 0 ? '+' : '' }}{{ $item->adjusted_quantity }}
                            </td>
                            <td>{{ $item->new_quantity }}</td>
                            <td>₹{{ number_format($item->unit_cost ?? 0, 2) }}</td>
                            <td>₹{{ number_format($item->total_value, 2) }}</td>
                            <td>{{ $item->remark ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function approveAdjustment(id) {
    if (confirm('Are you sure you want to approve this stock adjustment? This will update the stock quantities.')) {
        $.ajax({
            url: '/inventory-management/stock-adjustment/' + id + '/approve',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error approving adjustment.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error approving adjustment.');
            }
        });
    }
}

function rejectAdjustment(id) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        $.ajax({
            url: '/inventory-management/stock-adjustment/' + id + '/reject',
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
                    alert(response.message || 'Error rejecting adjustment.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error rejecting adjustment.');
            }
        });
    }
}
</script>
@endsection
