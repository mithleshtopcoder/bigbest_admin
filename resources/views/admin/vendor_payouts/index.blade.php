@extends('layouts.app')

@section('title', 'Vendor Earnings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Vendor Earnings</h1>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">All Vendors</h5>
        <a href="{{ route('vendor-payouts.create') }}" class="btn btn-primary btn-sm">Add Payout</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Vendor Name</th>
                    <th>Email</th>
                    <th>Total Earned</th>
                    <th>Total Paid</th>
                    <th>Remaining Payable</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $vendor)
                <tr>
                    <td>{{ $vendor->name }}</td>
                    <td>{{ $vendor->email }}</td>
                    <td>₹{{ number_format($vendor->totalEarned, 2) }}</td>
                    <td>₹{{ number_format($vendor->totalPaid, 2) }}</td>
                    <td>₹{{ number_format($vendor->remainingPayable, 2) }}</td>
                    <td class="text-center">
                        @if($vendor->remainingPayable > 0)
                        <a href="{{ route('vendor-payouts.create', ['vendor_id' => $vendor->vendor_id]) }}" class="btn btn-sm btn-success">
                            Pay
                        </a>
                        @else
                        <span class="text-muted">No Payment Due</span>
                        @endif

                        <a href="{{ route('vendor-payouts.transactions', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-info ms-1">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No vendors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
