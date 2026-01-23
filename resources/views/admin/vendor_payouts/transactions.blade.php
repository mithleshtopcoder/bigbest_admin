@extends('layouts.app')

@section('title', 'Vendor Transactions')

@section('content')
<div class="page-header">
    <h1 class="page-title">Transactions - {{ $vendor->name }}</h1>
    <a href="{{ route('vendor-payouts.transactions.pdf', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-primary float-end">Download PDF</a>
</div>

<div class="card mt-3">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Reference / Transaction ID</th>
                    <th>Note</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                <tr>
                    <td>{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                    <td>₹{{ number_format($tx->amount, 2) }}</td>
                    <td>{{ ucfirst($tx->payment_method) }}</td>
                    <td>{{ $tx->reference_no }}</td>
                    <td>{{ $tx->note }}</td>
                    <td>
                        <span class="badge {{ $tx->status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ ucfirst($tx->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
