@extends('layouts.app')
@section('title', 'View ' . ucfirst($type))
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">{{ ucfirst($type) }} Details</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('party-management.suppliers-vendors.index', ['type' => $type]) }}" class="text-decoration-none">{{ ucfirst($type) }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('party-management.suppliers-vendors.edit', ['type' => $type, 'id' => $supplier->id]) }}" class="btn btn-sm btn-success">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
            <a href="{{ route('party-management.suppliers-vendors.index', ['type' => $type]) }}" class="btn btn-sm btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="">
    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="card-title mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted small">Name</label>
                            <p class="mb-0 fw-semibold">{{ $supplier->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Code</label>
                            <p class="mb-0 fw-semibold">{{ $supplier->code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                @if($supplier->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($supplier->status == 'inactive')
                                    <span class="badge bg-danger">Inactive</span>
                                @elseif($supplier->status == 'suspended')
                                    <span class="badge bg-warning">Suspended</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($supplier->status) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Contact Person</label>
                            <p class="mb-0">{{ $supplier->contact_person ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">{{ $supplier->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0">{{ $supplier->phone ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0">
                                @if($supplier->address)
                                    {{ $supplier->address }}
                                    @if($supplier->city || $supplier->state || $supplier->pincode)
                                        <br>
                                        {{ collect([$supplier->city, $supplier->state, $supplier->pincode])->filter()->implode(', ') }}
                                    @endif
                                    @if($supplier->country)
                                        <br>{{ $supplier->country }}
                                    @endif
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax & Payment Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="card-title mb-0">Tax & Payment</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">GST Number</label>
                        <p class="mb-0">{{ $supplier->gst_number ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">PAN Number</label>
                        <p class="mb-0">{{ $supplier->pan_number ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Payment Terms</label>
                        <p class="mb-0">{{ $supplier->payment_terms ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Information -->
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="card-title mb-0">Purchase Orders</h5>
                    <span class="badge bg-primary">{{ $supplier->purchaseOrders->count() }}</span>
                </div>
                <div class="card-body">
                    @if($supplier->purchaseOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->purchaseOrders->take(10) as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->status == 'approved' ? 'success' : ($order->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No purchase orders found.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center py-2">
                    <h5 class="card-title mb-0">Purchase Receipts</h5>
                    <span class="badge bg-primary">{{ $supplier->purchaseReceipts->count() }}</span>
                </div>
                <div class="card-body">
                    @if($supplier->purchaseReceipts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Receipt Number</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->purchaseReceipts->take(10) as $receipt)
                                    <tr>
                                        <td>{{ $receipt->receipt_number }}</td>
                                        <td>{{ $receipt->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $receipt->status == 'completed' ? 'success' : ($receipt->status == 'pending' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($receipt->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No purchase receipts found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
