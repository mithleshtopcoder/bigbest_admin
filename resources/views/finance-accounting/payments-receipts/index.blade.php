@extends('layouts.app')

@section('title', 'Payments & Receipts')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Payments & Receipts</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item">Finance & Accounting</li>
                    <li class="breadcrumb-item active" aria-current="page">Payments & Receipts</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                <span class="reportrange-picker-field"></span>
            </div>
            <div class="dropdown filter-dropdown">
                <a class="btn btn-sm btn-light" style="padding: 5px 8px;" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                    <i class="bi bi-funnel me-2"></i>
                    <span>Filter</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="dropdown-item">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="TypeFilter" checked="checked" />
                            <label class="form-check-label c-pointer" for="TypeFilter">Type</label>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="PaymentMethodFilter" checked="checked" />
                            <label class="form-check-label c-pointer" for="PaymentMethodFilter">Payment Method</label>
                        </div>
                    </div>
                    <div class="dropdown-item">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="DateFilter" checked="checked" />
                            <label class="form-check-label c-pointer" for="DateFilter">Date</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <!-- [Payments & Receipts List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Payments & Receipts List</h5>
                    <div class="card-header-action">
                        <div class="card-header-btn">
                            <div data-bs-toggle="tooltip" title="Delete">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger" data-bs-toggle="remove"> </a>
                            </div>
                            <div data-bs-toggle="tooltip" title="Refresh">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning" data-bs-toggle="refresh"> </a>
                            </div>
                            <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success" data-bs-toggle="expand"> </a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25">
                                <div data-bs-toggle="tooltip" title="Options">
                                    <i class="feather-more-vertical"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download"></i>Export</a>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer"></i>Print</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-settings"></i>Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Transaction ID</th>
                                    <th>Type</th>
                                    <th>Party</th>
                                    <th>Payment Method</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="bi bi-currency-rupee"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">
                                                    #{{ strtoupper($payment->payment_type === 'receipt' ? 'REC' : 'PAY') }}-{{ str_pad($payment->id, 3, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </a>
                                        </div>
                                    </td>

                                    <td>
                                        @if($payment->payment_type === 'payment')
                                        <span class="badge bg-soft-danger text-danger">Payment</span>
                                        @else
                                        <span class="badge bg-soft-success text-success">Receipt</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $payment->customer?->full_name ?? 'N/A' }}
                                    </td>

                                    <td>{{ ucfirst($payment->payment_method) }}</td>

                                    <td>
                                        <span class="fw-bold {{ $payment->payment_type === 'payment' ? 'text-danger' : 'text-success' }}">
                                            ₹{{ number_format($payment->amount, 2) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ optional($payment->paid_at)->format('Y-m-d') ?? '-' }}
                                    </td>

                                    <td>
                                        @if($payment->status === 'completed')
                                        <span class="badge bg-soft-success text-success">Completed</span>
                                        @elseif($payment->status === 'pending')
                                        <span class="badge bg-soft-warning text-warning">Pending</span>
                                        @else
                                        <span class="badge bg-soft-danger text-danger">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="{{ route('finance-accounting.payments-receipts.show', $payment->id) }}" 
                                               class="btn btn-sm btn-link text-info p-1" 
                                               title="View Payment Slip Details"
                                               data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('finance-accounting.payments-receipts.download', $payment->id) }}" 
                                               class="btn btn-sm btn-link text-primary p-1" 
                                               title="Download Payment Slip"
                                               data-bs-toggle="tooltip"
                                               target="_blank">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-link text-success p-1" 
                                                    title="Print Payment Slip"
                                                    data-bs-toggle="tooltip"
                                                    onclick="printPaymentSlip({{ $payment->id }})">
                                                <i class="bi bi-printer"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <strong>No payments found</strong>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
        <!-- [Payments & Receipts List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // Print payment slip function
    function printPaymentSlip(paymentId) {
        // Open payment slip in new window for printing
        var url = '{{ route("finance-accounting.payments-receipts.view", ":id") }}'.replace(':id', paymentId);
        var printWindow = window.open(url, '_blank');
        
        if (printWindow) {
            printWindow.onload = function() {
                setTimeout(function() {
                    printWindow.print();
                }, 500);
            };
        } else {
            alert('Please allow popups to print the payment slip.');
        }
    }
</script>
@endsection

@section('styles')
@endsection
