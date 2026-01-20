@extends('layouts.app')

@section('title', 'Create Vendor Payout')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Vendor Payout</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('vendor-payouts.store') }}">
            @csrf

            {{-- Vendor --}}
            <div class="mb-3">
                <label class="form-label">Vendor</label>
                <select name="vendor_id" id="vendor_id" class="form-control" required>
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendor)
                    <option value="{{ $vendor->id }}" data-payable="{{ $vendor->payableAmount() }}">
                        {{ $vendor->name }} ({{ $vendor->vendor_uid }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Payable Amount --}}
            <div class="mb-3">
                <label class="form-label">Payable Amount</label>
                <input type="text" id="payable_amount" class="form-control" readonly>
            </div>

            {{-- Payout Amount --}}
            <div class="mb-3">
                <label class="form-label">Payout Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" min="1" step="0.01" required>
                <small class="text-muted">
                    Cannot exceed payable amount
                </small>
            </div>

            {{-- Note --}}
            <div class="mb-3">
                <label class="form-label">Note (Optional)</label>
                <textarea name="note" class="form-control"></textarea>
            </div>

            <button class="btn btn-primary">
                Create Payout
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('vendor_id').addEventListener('change', function() {
        const payable = this.options[this.selectedIndex].dataset.payable || 0;

        document.getElementById('payable_amount').value =
            '₹ ' + parseFloat(payable).toFixed(2);

        const amountInput = document.getElementById('amount');
        amountInput.max = payable;
        amountInput.value = payable > 0 ? payable : '';
    });

</script>
@endsection
