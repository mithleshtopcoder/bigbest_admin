@extends('layouts.app')

@section('title', ucfirst(str_replace('-', ' ', $status)) . ' Orders')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">{{ ucfirst(str_replace('-', ' ', $status)) }} Orders</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item">Order Status</li>
                    <li class="breadcrumb-item active" aria-current="page">{{ ucfirst(str_replace('-', ' ', $status)) }}</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="filterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="StatusFilter" checked>
                                <label class="form-check-label" for="StatusFilter">Status</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="DateFilter" checked>
                                <label class="form-check-label" for="DateFilter">Date</label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item-text">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="PaymentFilter" checked>
                                <label class="form-check-label" for="PaymentFilter">Payment</label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="main-body">
    <x-order-status-cards :current-status="$status" />

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">{{ ucfirst(str_replace('-', ' ', $status)) }} Orders List</h5>
            <div class="d-flex gap-1">
                <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Date & Time</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-cart-check text-primary fs-5"></i>
                                    <a href="{{ route('new-order.viewonline', ['type' => 'online', 'id' => $order->id]) }}">
                                        <strong>#{{ $order->order_number }}</strong>
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{ $order->created_at->format('Y-m-d') }} <br>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-grow-1">
                                        <a href="javascript:void(0);">
                                            {{ $order->customer->full_name ?? 'Guest' }} <br>
                                            <small class="text-muted">{{ $order->customer->email ?? '' }}</small>
                                        </a>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <!-- Copy Order Details Icon -->
                                        <button class="btn btn-sm btn-outline-secondary p-1 copy-order-btn" data-order-id="{{ $order->id }}" data-bs-toggle="tooltip" title="Copy Order Details" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-clipboard" style="font-size: 12px;"></i>
                                        </button>

                                        <!-- Live Location Icon -->
                                        @if($order->deliveryAddress && $order->deliveryAddress->latitude && $order->deliveryAddress->longitude)
                                        <a href="https://www.google.com/maps?q={{ $order->deliveryAddress->latitude }},{{ $order->deliveryAddress->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary p-1" data-bs-toggle="tooltip" title="View Live Location" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-geo-alt" style="font-size: 12px;"></i>
                                        </a>
                                        @endif

                                        <!-- Order Details Icon -->
                                        <a href="{{ route('new-order.viewonline', ['type' => 'online', 'id' => $order->id]) }}" class="btn btn-sm btn-outline-info p-1" data-bs-toggle="tooltip" title="Order Details" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-info-circle" style="font-size: 12px;"></i>
                                        </a>

                                        <!-- WhatsApp Icon -->
                                        @php
                                        $phone = $order->deliveryAddress->contact_phone ?? $order->customer->phone ?? '';

                                        // Build complete order details message
                                        $whatsappMessage = "📦 *ORDER DETAILS*\n\n";
                                        $whatsappMessage .= "Order #: {$order->order_number}\n";
                                        $whatsappMessage .= "Date: " . $order->created_at->format('d M Y, h:i A') . "\n\n";

                                        // Customer Information
                                        $whatsappMessage .= "👤 *CUSTOMER INFORMATION*\n";
                                        $whatsappMessage .= "Name: " . ($order->customer->full_name ?? 'Guest') . "\n";
                                        $whatsappMessage .= "Email: " . ($order->customer->email ?? 'N/A') . "\n";
                                        $whatsappMessage .= "Phone: " . ($order->customer->phone ?? 'N/A') . "\n\n";

                                        // Delivery Address
                                        if($order->deliveryAddress) {
                                        $whatsappMessage .= "📍 *DELIVERY ADDRESS*\n";
                                        $whatsappMessage .= "Contact: " . ($order->deliveryAddress->contact_name ?? '') . " (" . ($order->deliveryAddress->contact_phone ?? '') . ")\n";
                                        $whatsappMessage .= "Address: " . ($order->deliveryAddress->address ?? '') . "\n";
                                        if($order->deliveryAddress->landmark) {
                                        $whatsappMessage .= "Landmark: " . $order->deliveryAddress->landmark . "\n";
                                        }
                                        $whatsappMessage .= $order->deliveryAddress->city . ", " . $order->deliveryAddress->state . " - " . $order->deliveryAddress->pincode . "\n";
                                        if($order->deliveryAddress->latitude && $order->deliveryAddress->longitude) {
                                        $whatsappMessage .= "Location: https://www.google.com/maps?q=" . $order->deliveryAddress->latitude . "," . $order->deliveryAddress->longitude . "\n";
                                        }
                                        $whatsappMessage .= "\n";
                                        }

                                        // Order Items
                                        if($order->items && $order->items->count() > 0) {
                                        $whatsappMessage .= "🛒 *ORDER ITEMS*\n";
                                        foreach($order->items as $item) {
                                        $whatsappMessage .= "• " . $item->product_name;
                                        if($item->variant_name) {
                                        $whatsappMessage .= " (" . $item->variant_name . ")";
                                        }
                                        $whatsappMessage .= " - Qty: " . $item->quantity . " × ₹" . number_format($item->unit_price, 2) . " = ₹" . number_format($item->total_price, 2) . "\n";
                                        }
                                        $whatsappMessage .= "\n";
                                        }

                                        // Payment Summary
                                        $whatsappMessage .= "💰 *PAYMENT SUMMARY*\n";
                                        $whatsappMessage .= "Subtotal: ₹" . number_format($order->subtotal, 2) . "\n";
                                        if($order->discount_amount > 0) {
                                        $whatsappMessage .= "Discount: -₹" . number_format($order->discount_amount, 2) . "\n";
                                        }
                                        if($order->tax_amount > 0) {
                                        $whatsappMessage .= "Tax: ₹" . number_format($order->tax_amount, 2) . "\n";
                                        }
                                        if($order->shipping_charge > 0) {
                                        $whatsappMessage .= "Shipping: ₹" . number_format($order->shipping_charge, 2) . "\n";
                                        }
                                        $whatsappMessage .= "Total Amount: ₹" . number_format($order->total_amount, 2) . "\n";
                                        $whatsappMessage .= "Payment Status: " . ucfirst($order->payment_status) . "\n";
                                        $whatsappMessage .= "Payment Method: " . ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) . "\n";
                                        $whatsappMessage .= "Order Status: " . ucfirst(str_replace('_', ' ', $order->status)) . "\n";

                                        if($order->delivery_instructions) {
                                        $whatsappMessage .= "\n📝 Delivery Instructions: " . $order->delivery_instructions . "\n";
                                        }

                                        $whatsappUrl = $phone ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $phone) . "?text=" . urlencode($whatsappMessage) : '#';
                                        @endphp
                                        <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-sm btn-outline-success p-1 {{ !$phone ? 'disabled' : '' }}" data-bs-toggle="tooltip" title="Send via WhatsApp" style="width: 24px; height: 24px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-whatsapp" style="font-size: 12px;"></i>
                                        </a>
                                    </div>
                                </div>

                                <!-- Hidden complete order details for copying -->
                                <div id="order-details-{{ $order->id }}" style="display: none;">
                                    ORDER DETAILS

                                    Order #: {{ $order->order_number }}
                                    Date: {{ $order->created_at->format('d M Y, h:i A') }}

                                    CUSTOMER INFORMATION
                                    Name: {{ $order->customer->full_name ?? 'Guest' }}
                                    Email: {{ $order->customer->email ?? 'N/A' }}
                                    Phone: {{ $order->customer->phone ?? 'N/A' }}

                                    @if($order->deliveryAddress)
                                    DELIVERY ADDRESS
                                    Contact: {{ $order->deliveryAddress->contact_name ?? '' }} ({{ $order->deliveryAddress->contact_phone ?? '' }})
                                    Address: {{ $order->deliveryAddress->address ?? '' }}
                                    @if($order->deliveryAddress->landmark)
                                    Landmark: {{ $order->deliveryAddress->landmark }}
                                    @endif
                                    {{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->state }} - {{ $order->deliveryAddress->pincode }}
                                    @if($order->deliveryAddress->latitude && $order->deliveryAddress->longitude)
                                    Location: https://www.google.com/maps?q={{ $order->deliveryAddress->latitude }},{{ $order->deliveryAddress->longitude }}

                                    @endif
                                    @endif
                                    @if($order->items && $order->items->count() > 0)
                                    ORDER ITEMS
                                    @foreach($order->items as $item)
                                    • {{ $item->product_name }}@if($item->variant_name) ({{ $item->variant_name }})@endif - Qty: {{ $item->quantity }} × ₹{{ number_format($item->unit_price, 2) }} = ₹{{ number_format($item->total_price, 2) }}
                                    @endforeach

                                    @endif
                                    PAYMENT SUMMARY
                                    Subtotal: ₹{{ number_format($order->subtotal, 2) }}
                                    @if($order->discount_amount > 0)
                                    Discount: -₹{{ number_format($order->discount_amount, 2) }}
                                    @endif
                                    @if($order->tax_amount > 0)
                                    Tax: ₹{{ number_format($order->tax_amount, 2) }}
                                    @endif
                                    @if($order->shipping_charge > 0)
                                    Shipping: ₹{{ number_format($order->shipping_charge, 2) }}
                                    @endif
                                    Total Amount: ₹{{ number_format($order->total_amount, 2) }}
                                    Payment Status: {{ ucfirst($order->payment_status) }}
                                    Payment Method: {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                                    Order Status: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    @if($order->delivery_instructions)

                                    Delivery Instructions: {{ $order->delivery_instructions }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-gray-200 text-dark">{{ $order->items_count }} items</span>
                            </td>
                            <td>
                                <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                $statusClass = match($order->status) {
                                'accepted', 'confirmed' => 'bg-soft-primary text-primary',
                                'preparing', 'processing' => 'bg-soft-info text-info',
                                'out-for-delivery', 'out_for_delivery' => 'bg-soft-warning text-warning',
                                'completed', 'delivered' => 'bg-soft-success text-success',
                                'cancelled' => 'bg-soft-danger text-danger',
                                default => 'bg-soft-secondary text-secondary'
                                };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_',' ',$order->status)) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <!-- Status Change Dropdown -->
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown" aria-expanded="false" title="Change Status">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" style="min-width:220px;">
                                            <form action="{{ route('orders.update-status', $order->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm mb-2">
                                                    @foreach($order->nextStatuses as $nextStatus)
                                                    <option value="{{ $nextStatus }}">{{ $statusLabels[$nextStatus] }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary w-100">
                                                    Update Status
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- View Order -->
                                    <a href="{{ route('new-order.viewonline', ['type' => 'online', 'id' => $order->id]) }}" class="btn btn-sm btn-outline-secondary" title="View Order">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>



                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="card-footer py-1">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div>
                    <p class="mb-0 text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                    </p>
                </div>
                <div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips and copy address functionality
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Copy order details button click handlers
        document.querySelectorAll('.copy-order-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const orderDetailsElement = document.getElementById('order-details-' + orderId);
                if (orderDetailsElement) {
                    const orderDetailsText = orderDetailsElement.textContent.trim();
                    navigator.clipboard.writeText(orderDetailsText).then(function() {
                        // Show success feedback
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-check" style="font-size: 12px;"></i>';
                        btn.classList.remove('btn-outline-secondary');
                        btn.classList.add('btn-success');

                        setTimeout(function() {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy order details:', err);
                        alert('Failed to copy order details');
                    });
                }
            });
        });
    });

</script>
@endsection

@section('styles')
<style>
    /* Pagination Styles */
    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #495057;
        border-color: #dee2e6;
        padding: 0.375rem 0.75rem;
    }

    .pagination .page-item.active .page-link {
        background-color: rgb(19 84 58);
        border-color: rgb(19 84 58);
        color: #fff;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #495057;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        cursor: not-allowed;
        opacity: 0.6;
    }

</style>
@endsection
