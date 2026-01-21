@extends('layouts.app')

@section('title', 'Vendor Payouts')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Vendor Payouts</h5>
        <a href="{{ route('vendor-payouts.create') }}" class="btn btn-primary">
            + Create Payout
        </a>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Vendor</th>
                    <th>Payable</th>
                    <th>Payout Amount</th>
                    <th>Status</th>
                    <th>Method</th>
                    <th>Reference</th>
                    <th>Paid At</th>
                    <th width="120">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $payout)
                <tr>
                    <td>
                        <strong>{{ $payout->vendor->name ?? '-' }}</strong><br>
                        <small class="text-muted">
                            {{ $payout->vendor->vendor_id ?? '-' }}
                        </small>
                    </td>

                    <td>
                        ₹ {{ $payout->vendor && method_exists($payout->vendor, 'payableAmount') ? number_format($payout->vendor->payableAmount(), 2) : '0.00' }}
                    </td>

                    <td>
                        ₹ {{ number_format($payout->amount, 2) }}
                    </td>

                    <td>
                        <span class="badge bg-{{ $payout->status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($payout->status) }}
                        </span>
                    </td>

                    <td>{{ $payout->payment_method ?? '-' }}</td>
                    <td>{{ $payout->reference_no ?? '-' }}</td>

                    <td>
                        {{ $payout->paid_at ? $payout->paid_at->format('d M Y, h:i A') : '-' }}
                    </td>

                    <td>
                        @if($payout->status === 'pending')
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#payModal{{ $payout->id }}">
                            Mark Paid
                        </button>
                        @else
                        <span class="text-success fw-semibold">Completed</span>
                        @endif
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        No payouts found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $payouts->links() }}
    </div>
</div>

{{-- MODALS --}}
@foreach($payouts as $payout)
@if($payout->status === 'pending')
<div class="modal fade" id="payModal{{ $payout->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('vendor-payouts.paid', $payout) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Payout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Pay <strong>₹ {{ number_format($payout->amount, 2) }}</strong>
                        to <strong>{{ $payout->vendor->name ?? '-' }}</strong>
                    </p>

                    <div class="mb-2">
                        <label class="form-label">Payment Method</label>
                        <input name="payment_method" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Reference / UTR</label>
                        <input name="reference_no" class="form-control" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endsection
