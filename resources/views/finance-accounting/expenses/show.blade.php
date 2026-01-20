@extends('layouts.app')

@section('title', 'View Expense')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">View Expense</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance-accounting.expenses.index') }}" class="text-decoration-none">Expenses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right d-flex align-items-center gap-2">
            <a href="{{ route('finance-accounting.expenses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
            <a href="{{ route('finance-accounting.expenses.edit', $expense->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit
            </a>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center py-1">
            <h5 class="card-title mb-0">Expense Details - {{ $expense->expense_number }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Expense Number:</th>
                            <td>{{ $expense->expense_number }}</td>
                        </tr>
                        <tr>
                            <th>Title:</th>
                            <td>{{ $expense->title }}</td>
                        </tr>
                        <tr>
                            <th>Category:</th>
                            <td>{{ $expense->category ? $expense->category->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Store:</th>
                            <td>{{ $expense->store ? $expense->store->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td><span class="fw-bold text-danger">₹{{ number_format($expense->amount, 2) }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Expense Date:</th>
                            <td>{{ $expense->expense_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ $expense->paymentMethod ? $expense->paymentMethod->name : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-success">
                                    {{ $expense->statusOption ? $expense->statusOption->name : '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Reference Number:</th>
                            <td>{{ $expense->reference_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created By:</th>
                            <td>{{ $expense->createdBy ? $expense->createdBy->name : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            @if($expense->description)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6 class="mb-2">Description:</h6>
                    <p class="text-muted">{{ $expense->description }}</p>
                </div>
            </div>
            @endif
            @if($expense->notes)
            <div class="row mt-3">
                <div class="col-md-12">
                    <h6 class="mb-2">Notes:</h6>
                    <p class="text-muted">{{ $expense->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
