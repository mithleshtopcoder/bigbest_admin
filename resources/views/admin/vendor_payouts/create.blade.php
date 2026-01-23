@extends('layouts.app')

@section('title', 'Create Vendor Payout')

@section('content')
<div class="page-header mb-4">
    <h1 class="page-title">Vendor Payout</h1>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="{{ route('vendor-payouts.store') }}" method="POST">
            @csrf
            <input type="hidden" name="vendor_id" value="{{ $vendor->vendor_id }}">

            <div class="row">

                {{-- ================= VENDOR DETAILS BLOCK ================= --}}
                <div class="col-md-6">
                    <div class="card border-light mb-3 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Vendor Details</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $vendor->name }}</p>
                            <p><strong>Email:</strong> {{ $vendor->email }}</p>
                            <p><strong>Mobile:</strong> {{ $vendor->mobile_number }}</p>
                            <p><strong>Remaining Payable:</strong> ₹{{ number_format($remainingPayable, 2) }}</p>

                            @if($vendor->vendorDocument)
                            <h6 class="mt-3">Bank Details</h6>
                            <p><strong>Bank Name:</strong> {{ $vendor->vendorDocument->bank_name }}</p>
                            <p><strong>Account Number:</strong> {{ $vendor->vendorDocument->account_number }}</p>
                            <p><strong>Account Type:</strong> {{ $vendor->vendorDocument->account_type }}</p>
                            <p><strong>IFSC Code:</strong> {{ $vendor->vendorDocument->ifsc_code }}</p>
                            <p><strong>Branch:</strong> {{ $vendor->vendorDocument->branch_name }}</p>
                            @else
                            <p class="text-danger">No bank details found for this vendor.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ================= PAYOUT DETAILS BLOCK ================= --}}
                <div class="col-md-6">
                    <div class="card border-light mb-3 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">Payout Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Payout Amount</label>
                                <input type="number" name="amount" id="amount" class="form-control" step="0.01" min="1" max="{{ $remainingPayable }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">Select Method</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="upi">UPI</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="reference_no" class="form-label">Transaction / Reference ID</label>
                                <input type="text" name="reference_no" id="reference_no" class="form-control" maxlength="150" required>
                            </div>

                            <div class="mb-3">
                                <label for="note" class="form-label">Note</label>
                                <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100">Submit Payout</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-header h6 {
        font-weight: 600;
        font-size: 1rem;
    }

</style>
@endsection
