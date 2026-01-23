@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="main-body">

    <h1 class="my-2">Welcome, {{ $vendor->name }}</h1>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Earned</h5>
                    <p class="card-text fs-4">&#8377;{{ number_format($totalEarned, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Paid</h5>
                    <p class="card-text fs-4">&#8377;{{ number_format($totalPaid, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Remaining Payable</h5>
                    <p class="card-text fs-4">&#8377;{{ number_format($remainingPayable, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Online Orders</h5>
                    <p class="card-text fs-4">{{ $onlineOrders ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Payouts --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Recent Payouts</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Amount (₹)</th>
                            <th>Payment Method</th>
                            <th>Reference</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayouts as $payout)
                        <tr>
                            <td>{{ $payout->created_at->format('d M Y') }}</td>
                            <td>&#8377;{{ number_format($payout->amount, 2) }}</td>
                            <td>{{ ucfirst($payout->payment_method) }}</td>
                            <td>{{ $payout->reference_no }}</td>
                            <td>
                                @if($payout->status === 'paid')
                                <span class="badge bg-success">{{ ucfirst($payout->status) }}</span>
                                @elseif($payout->status === 'pending')
                                <span class="badge bg-warning">{{ ucfirst($payout->status) }}</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($payout->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No payouts found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            {{-- <a href="{{ route('vendor.payouts.transactions') }}" class="btn btn-sm btn-primary">View All Transactions</a> --}}
        </div>
    </div>

    {{-- Optional: Recent Orders --}}
    @if(isset($recentOrders) && $recentOrders->count() > 0)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Recent Online Orders</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td>{{ $order->order->order_number }}</td>
                            <td>{{ $order->order->created_at->format('d M Y') }}</td>
                            <td>{{ $order->order->customer->name ?? '-' }}</td>
                            <td>&#8377;{{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->order->status === 'delivered') bg-success
                                    @elseif($order->order->status === 'pending') bg-warning
                                    @else bg-secondary @endif">
                                    {{ ucfirst($order->order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('vendor.orders.index') }}" class="btn btn-sm btn-primary">View All Orders</a>
        </div>
    </div>
    @endif

</div>
@endsection
