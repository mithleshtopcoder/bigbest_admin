@extends('layouts.app')

@section('title', 'Edit Expense')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Expense</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('finance-accounting.expenses.index') }}" class="text-decoration-none">Expenses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="page-header-right">
            <a href="{{ route('finance-accounting.expenses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back
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
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('finance-accounting.expenses.update', $expense->id) }}">
                @csrf
                @method('PUT')
                <div class="col-md-8 mx-auto">
                <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Store <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <select name="store_id" id="store_id" class="form-select form-control @error('store_id') is-invalid @enderror">
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                            <option value="{{ $store->id }}" {{ old('store_id', $expense->store_id) == $store->id ? 'selected' : '' }}>
                                                {{ $store->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('store_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Expense Category <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <select name="category_id" id="category_id" class="form-select form-control @error('category_id') is-invalid @enderror" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Payment Method <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <select name="payment_method_id" id="payment_method_id" class="form-select form-control @error('payment_method_id') is-invalid @enderror" required>
                                            <option value="">Select Payment Method</option>
                                            @foreach($paymentMethods as $method)
                                            <option value="{{ $method->id }}" {{ old('payment_method_id', $expense->payment_method_id) == $method->id ? 'selected' : '' }}>
                                                {{ $method->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('payment_method_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Status <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <select name="status_id" id="status_id" class="form-select form-control @error('status_id') is-invalid @enderror" required>
                                            <option value="">Select Status</option>
                                            @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id', $expense->status_id) == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Title <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $expense->title) }}" required>
                                        @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Amount <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" step="0.01" min="0" value="{{ old('amount', $expense->amount) }}" required>
                                        @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Expense Date <span class="text-danger">*</span></label>
                                    <div class="col-md-7">
                                        <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                                        @error('expense_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="form-label col-md-5">Reference Number</label>
                                    <div class="col-md-7">
                                        <input type="text" name="reference_number" id="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number', $expense->reference_number) }}">
                                        @error('reference_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="form-label col-md-3" style="height: 70px;">Description</label>
                                    <div class="col-md-9">
                                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $expense->description) }}</textarea>
                                        @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="form-label col-md-3" style="height: 70px;">Notes</label>
                                    <div class="col-md-9">
                                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                                        @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-center mt-2">
                            <button type="submit" class="btn btn-primary">Update Expense</button>
                            <a href="{{ route('finance-accounting.expenses.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
