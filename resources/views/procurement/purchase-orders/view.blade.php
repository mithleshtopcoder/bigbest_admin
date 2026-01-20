@extends('layouts.app')

@section('title', 'View Purchase Order')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Purchase Order #{{ $order->po_number }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('procurement.purchase-orders') }}" class="text-decoration-none">Purchase Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            @if($order->status == 'pending')
            <button onclick="approvePO({{ $order->id }})" class="btn btn-sm btn-success">Approve</button>
            @endif
            @if(in_array($order->status, ['draft', 'pending', 'approved']))
            <button onclick="cancelPO({{ $order->id }})" class="btn btn-sm btn-danger">Cancel</button>
            @endif
            <a href="{{ route('procurement.purchase-orders') }}" class="btn btn-sm btn-light">Back</a>
        </div>
    </div>
</div>

<div class="">
    <div class="row">
        <!-- Order Details -->
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>PO Number:</strong></div>
                        <div class="col-md-6">{{ $order->po_number }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Supplier:</strong></div>
                        <div class="col-md-6">{{ $order->supplier->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Store:</strong></div>
                        <div class="col-md-6">{{ $order->store->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Order Date:</strong></div>
                        <div class="col-md-6">{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d M Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6"><strong>Status:</strong></div>
                        <div class="col-md-6">
                            <span class="badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
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
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->productVariant->product->name ?? '' }}
                                        @if($item->productVariant->name)
                                        - {{ $item->productVariant->name }}
                                        @endif
                                    </td>
                                    <td>{{ $item->ordered_quantity }}</td>
                                    <td>{{ number_format($item->unit_cost, 2) }}</td>
                                    <td>{{ number_format($item->total_cost, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2"><span>Subtotal:</span><strong>{{ number_format($order->subtotal, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Tax:</span><strong>{{ number_format($order->tax_amount, 2) }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Discount:</span><strong>{{ number_format($order->discount_amount, 2) }}</strong></div>
                    <hr>
                    <div class="d-flex justify-content-between"><span><strong>Total:</strong></span><strong>{{ number_format($order->total_amount, 2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Make functions global to be accessible from onclick
    window.approvePO = function(id) {
        if (confirm('Are you sure you want to approve this purchase order?')) {
            $.ajax({
                url: '{{ route("procurement.purchase-orders.approve", ":id") }}'.replace(':id', id)
                , type: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , success: function(response) {
                    if (response && response.success) {
                        location.reload();
                    } else {
                        alert(response && response.message ? response.message : 'Error approving purchase order.');
                    }
                }
                , error: function(xhr) {
                    const response = xhr.responseJSON;
                    alert(response && response.message ? response.message : 'Error approving purchase order.');
                }
            });
        }
    };

    window.cancelPO = function(id) {
        if (confirm('Are you sure you want to cancel this purchase order? This will update pending stock quantities.')) {
            $.ajax({
                url: '{{ route("procurement.purchase-orders.cancel", ":id") }}'.replace(':id', id)
                , type: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , success: function(response) {
                    if (response && response.success) {
                        location.reload();
                    } else {
                        alert(response && response.message ? response.message : 'Error cancelling purchase order.');
                    }
                }
                , error: function(xhr) {
                    const response = xhr.responseJSON;
                    alert(response && response.message ? response.message : 'Error cancelling purchase order.');
                }
            });
        }
    };

</script>
@endsection
