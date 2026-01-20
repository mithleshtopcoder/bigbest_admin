@extends('layouts.app')

@section('title', 'Customer Feedback')

@section('content')

{{-- ================= PAGE HEADER ================= --}}
<div class="page-header d-flex justify-content-between align-items-center">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
        <h5 class="mb-0">Customer Feedback</h5>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Party Management</li>
                <li class="breadcrumb-item">Customers</li>
                <li class="breadcrumb-item active">Feedback</li>
            </ol>
        </nav>
    </div>
</div>

{{-- ================= MAIN CONTENT ================= --}}
<div class="main-body mt-3">
    <div class="card">

        {{-- Card Header --}}
        <div class="card-header d-flex justify-content-between align-items-center py-2">
            <h5 class="card-title mb-0">Customer Feedback List</h5>
        </div>

        {{-- Card Body --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Rating</th>
                            <th>Feedback</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($feedbacks as $feedback)
                        <tr>
                            <td>#FDB-{{ str_pad($feedback->id, 4, '0', STR_PAD_LEFT) }}</td>

                            <td>{{ $feedback->customer?->full_name ?? 'N/A' }}</td>

                            <td>{{ $feedback->order?->order_number ?? '-' }}</td>

                            {{-- Rating --}}
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++) <i class="feather-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-1">{{ number_format($feedback->rating, 1) }}</span>
                                </div>
                            </td>

                            <td>{{ Str::limit($feedback->comment, 50) }}</td>

                            <td>{{ $feedback->created_at->format('d M Y') }}</td>

                            {{-- Status --}}
                            <td>
                                @if($feedback->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                                @elseif($feedback->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                                @else
                                <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">

                                    {{-- Approve --}}
                                    @if($feedback->status === 'pending')
                                    <form action="{{ route('party-management.customers.feedback.approve', $feedback->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Approve">
                                            <i class="feather-check"></i>
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <form action="{{ route('party-management.customers.feedback.reject', $feedback->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" title="Reject">
                                            <i class="feather-x"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- View --}}
                                    <a href="{{ route('party-management.customers.feedback.show', $feedback->id) }}" class="btn btn-sm btn-primary" title="View">
                                        <i class="feather-eye"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No feedback found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Footer --}}
        <div class="card-footer d-flex justify-content-end">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .table td .btn {
        padding: 4px 8px;
    }

    .table td .btn i {
        font-size: 14px;
    }

</style>
@endsection
