@extends('layouts.app')

@section('title', 'View In-Transit Transfer')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Transfer #{{ $transfer->transfer_number }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory-management.stock-transfer.in-transit') }}" class="text-decoration-none">In-Transit</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            @if(in_array($transfer->status, ['approved', 'in_transit']))
                <button onclick="receiveTransfer({{ $transfer->id }})" class="btn btn-sm btn-success">Receive</button>
            @endif
            <a href="{{ route('inventory-management.stock-transfer.in-transit') }}" class="btn btn-sm btn-light">Back</a>
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
                    <span class="badge bg-info">In Transit</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Items - Receive Quantities</h5>
        </div>
        <div class="card-body">
            <form id="receiveForm">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Received Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="received_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Approved Qty</th>
                                <th>Received Qty</th>
                                <th>Damaged Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->items as $item)
                            <tr>
                                <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                                <td>{{ $item->productVariant->name ?? 'N/A' }}</td>
                                <td>{{ $item->approved_quantity }}</td>
                                <td>
                                    <input type="number" 
                                           class="form-control received-qty" 
                                           name="items[{{ $item->id }}][received_quantity]" 
                                           value="{{ $item->approved_quantity }}" 
                                           min="0" 
                                           max="{{ $item->approved_quantity }}"
                                           required>
                                </td>
                                <td>
                                    <input type="number" 
                                           class="form-control damaged-qty" 
                                           name="items[{{ $item->id }}][damaged_quantity]" 
                                           value="0" 
                                           min="0" 
                                           max="{{ $item->approved_quantity }}"
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
function receiveTransfer(id) {
    if (confirm('Are you sure you want to receive this transfer? This will update stock at the destination store.')) {
        const formData = $('#receiveForm').serialize();
        
        $.ajax({
            url: '/inventory-management/stock-transfer/in-transit/' + id + '/receive',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Error receiving transfer.');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                alert(response?.message || 'Error receiving transfer.');
            }
        });
    }
}
</script>
@endsection
