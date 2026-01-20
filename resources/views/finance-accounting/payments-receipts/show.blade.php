@extends('layouts.app')

@section('title', 'Payment Slip Details')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Payment Slip Details</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance-accounting.payments-receipts') }}" class="text-decoration-none">Payments & Receipts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('finance-accounting.payments-receipts.download', $payment->id) }}" class="btn btn-sm btn-primary" target="_blank">
                <i class="bi bi-download me-2"></i>Download PDF
            </a>
            <a href="{{ route('finance-accounting.payments-receipts.view', $payment->id) }}" class="btn btn-sm btn-info" target="_blank">
                <i class="bi bi-printer me-2"></i>Print
            </a>
            <a href="{{ route('finance-accounting.payments-receipts') }}" class="btn btn-sm btn-light">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">
                {{ $payment->payment_type === 'receipt' ? 'Receipt' : 'Payment' }} Slip - {{ $payment->payment_number }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Transaction ID:</th>
                            <td>#{{ strtoupper($payment->payment_type === 'receipt' ? 'REC' : 'PAY') }}-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <th>Payment Number:</th>
                            <td>{{ $payment->payment_number }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td>
                                @if($payment->payment_type === 'payment')
                                <span class="badge bg-danger">Payment</span>
                                @else
                                <span class="badge bg-success">Receipt</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Customer:</th>
                            <td>{{ $payment->customer?->full_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td><span class="fw-bold {{ $payment->payment_type === 'payment' ? 'text-danger' : 'text-success' }}">₹{{ number_format($payment->amount, 2) }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Payment Method:</th>
                            <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($payment->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                                @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @else
                                <span class="badge bg-danger">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Paid Date:</th>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y, h:i A') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Transaction ID:</th>
                            <td>{{ $payment->transaction_id ?: '-' }}</td>
                        </tr>
                        @if($payment->order)
                        <tr>
                            <th>Order Number:</th>
                            <td>{{ $payment->order->order_number ?? '-' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
