@extends('layouts.app')

@section('title', 'View Transfer Request')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="page-title mb-0">View Transfer Request</h1>
        <div class="d-flex gap-2">
            @if($transfer->status == 'pending')
                <a href="{{ route('inventory-management.stock-transfer.transfer-request.edit', $transfer->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <button onclick="cancelTransfer({{ $transfer->id }})" class="btn btn-sm btn-danger">Cancel</button>
            @endif
            <a href="{{ route('inventory-management.stock-transfer.transfer-request') }}" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row ps-2">
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="form-label col-md-3">Transfer Number</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->transfer_number }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">From Store</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->fromStore->name ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">To Store</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->toStore->name ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">Transfer Date</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->transfer_date->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="form-label col-md-3">Status</label>
                    <div class="col-md-9">
                        <span class="badge bg-{{ $transfer->status == 'pending' ? 'warning' : ($transfer->status == 'approved' ? 'success' : ($transfer->status == 'completed' ? 'primary' : 'danger')) }}">
                            {{ ucfirst(str_replace('_', ' ', $transfer->status)) }}
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">Expec. Delivery Date</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->expected_delivery_date ? $transfer->expected_delivery_date->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">Requested By</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->requestedBy->name ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="form-label col-md-3">Total Items</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext">{{ $transfer->total_items }}</div>
                    </div>
                </div>
            </div>
        </div>
        @if($transfer->notes)
        <div class="row ps-2">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="form-label col-md-3" style="height:64px;">Notes</label>
                    <div class="col-md-9">
                        <div class="form-control-plaintext" style="min-height: 64px;">{{ $transfer->notes }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h6 class="mb-3 ps-2">Items Information</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Requested Qty</th>
                        <th>Approved Qty</th>
                        <th>Transferred Qty</th>
                        <th>Received Qty</th>
                        <th>Damaged Qty</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transfer->items as $item)
                    <tr>
                        <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->productVariant->name ?? 'N/A' }}</td>
                        <td>{{ $item->requested_quantity }}</td>
                        <td>{{ $item->approved_quantity ?? '-' }}</td>
                        <td>{{ $item->transferred_quantity }}</td>
                        <td>{{ $item->received_quantity }}</td>
                        <td>{{ $item->damaged_quantity }}</td>
                        <td>{{ $item->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function cancelTransfer(id) {
    if (confirm('Are you sure you want to cancel this transfer request?')) {
        $.ajax({
            url: '/inventory-management/stock-transfer/transfer-request/' + id + '/cancel',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error cancelling transfer.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error cancelling transfer.');
            }
        });
    }
}
</script>
@endsection
@endsection