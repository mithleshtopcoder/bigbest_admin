@extends('layouts.app')

@section('title', 'View Online Order')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">View Online Order - #{{ $order->order_number }}</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('new-order.index', 'online') }}" class="text-decoration-none">Online Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('new-order.index', 'online') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>

            @if($order->status === 'pending')
            <button class="btn btn-sm btn-success" onclick="acceptOrder('{{ $order->id }}')">
                <i class="bi bi-check me-2"></i>Accept Order
            </button>

            <button class="btn btn-sm btn-danger" onclick="rejectOrder('{{ $order->id }}')">
                <i class="bi bi-x me-2"></i>Reject Order
            </button>
            @endif

            <button class="btn btn-sm btn-primary" onclick="window.print()">
                <i class="bi bi-printer me-2"></i>Print
            </button>
        </div>

    </div>
</div>
<div class="main-body">
    <div class="row">
        <!-- [View Online Order] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <div class="row pl-2">
                        <div class="col-lg-8">
                            <!-- Customer Information -->
                            <div class="card-header py-2 mb-2 pl-1">
                                <h5 class="card-title mb-0">Customer Information</h5>
                            </div>
                            <div class="form-group row">
                                <label class="form-label text-md col-md-2 custom-label">Full Name</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="{{ $order->customer->full_name ?? 'Guest' }}" readonly>
                                </div>
                                <label class="form-label text-md col-md-2 custom-label">Email</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="{{ $order->customer->email ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label text-md col-md-2 custom-label">Phone</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="{{ $order->customer->phone ?? '-' }}" readonly>
                                </div>
                                <label class="form-label text-md col-md-2 custom-label">Address</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" class="form-control form-control-sm" value="{{ $order->deliveryAddress->address ?? '-' }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pl-1 mb-4">
                                    <div class="mb-3 mt-3">
                                        <label class="form-label">Order Items</label>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="product_table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th style="text-align: right">Price</th>
                                                        <th style="text-align: right;width: 90px;">Quantity</th>
                                                        <th style="text-align: right">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product_tbody">
                                                    @foreach($order->items as $item)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                                                            <small class="text-muted">SKU: {{ $item->product->sku ?? '-' }}</small>
                                                        </td>
                                                        <td style="text-align: right">₹{{ number_format($item->unit_price, 2) }}</td>
                                                        <td style="text-align: right">{{ $item->quantity }}</td>
                                                        <td style="text-align: right">₹{{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <h5 class="card-title mb-0 mb-2">Delivery Details</h5>
                                    <p class="mb-0 text-muted mb-2">
                                        {{ $order->deliveryAddress->address ?? '-' }}, {{ $order->deliveryAddress->city ?? '-' }}, {{ $order->deliveryAddress->state ?? '-' }} - {{ $order->deliveryAddress->pincode ?? '-' }}
                                    </p>
                                    @if($order->delivery_instructions)
                                    <h5 class="card-title mb-0 mb-2 mt-3">Special Instructions</h5>
                                    <p class="mb-0 text-muted mb-4">
                                        {{ $order->delivery_instructions }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Order Summary -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Subtotal</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹{{ number_format($order->subtotal, 2) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Discount</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹{{ number_format($order->discount_amount, 2) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Tax (GST 18%)</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹{{ number_format($order->tax_amount, 2) }}" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Shipping</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="text" class="form-control form-control-sm w-100" style="text-align: right" value="₹{{ number_format($order->shipping_charge, 2) }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fs-5 fw-bold">Total</span>
                                        <span class="fs-5 fw-bold text-primary">₹{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Payment Method</label>
                                        <div class="col-md-6 pl-1">
                                            <span class="badge bg-soft-primary text-primary">{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Payment Status</label>
                                        <div class="col-md-6 pl-1">
                                            <span class="badge bg-{{ $order->payment_status=='paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Order ID</label>
                                        <div class="col-md-6 pl-1">
                                            <span>#{{ $order->order_number }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Order Date</label>
                                        <div class="col-md-6 pl-1">
                                            <span>{{ $order->created_at->format('Y-m-d h:i A') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Status</label>
                                        <div class="col-md-6 pl-1">
                                            <span class="badge bg-soft-success text-success">{{ ucfirst($order->status) }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-1">
                                        <label class="form-label text-md col-md-6 custom-label">Delivery Type</label>
                                        <div class="col-md-6 pl-1">
                                            <span>{{ ucfirst(str_replace('_', ' ', $order->delivery_type ?? 'Home Delivery')) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [View Online Order] end -->
    </div>
</div>

<div class="modal fade" id="acceptOrderModal" tabindex="-1" aria-labelledby="acceptOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Accept Order Confirmation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted">Are you sure you want to accept this order? This action will notify the customer.</p>
                <div class="bg-light p-3 rounded">
                    <p class="mb-1"><strong>Order ID:</strong> #{{ $order->order_number }}</p>
                    <p class="mb-1"><strong>Customer:</strong> {{ $order->customer->full_name }}</p>
                    <p class="mb-0"><strong>Total Amount:</strong> ₹{{ number_format($order->total_amount,2) }}</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                <form id="acceptOrderForm" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success px-4">Confirm Accept</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form id="rejectOrderForm" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Reject Order Confirmation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted">Are you sure you want to reject order <strong>#{{ $order->order_number }}</strong>?</p>
                    <div class="mb-3">
                        <label for="reject_reason" class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reject_reason" name="reject_reason" rows="4" required minlength="10" maxlength="500" placeholder="Please explain why the order is being rejected..."></textarea>
                        <small class="text-muted">Minimum 10 characters required.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function acceptOrder(orderId) {
        document.getElementById('acceptOrderForm').action = `/new-order/online/${orderId}/accept`;
        new bootstrap.Modal(document.getElementById('acceptOrderModal')).show();
    }

    function rejectOrder(orderId) {
        document.getElementById('rejectOrderForm').action = `/new-order/online/${orderId}/reject`;
        document.getElementById('reject_reason').value = '';
        new bootstrap.Modal(document.getElementById('rejectOrderModal')).show();
    }

</script>
@endsection

@section('styles')
<style>
    .bg-soft-warning {
        background-color: rgba(255, 193, 7, 0.12);
    }

    /* Print Styles */
    @media print {

        .page-header,
        .btn,
        .breadcrumb,
        .modal {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }

        .col-lg-8,
        .col-lg-4 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }

    /* Mobile Tweaks */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 10px;
        }
    }

</style>
@endsection
