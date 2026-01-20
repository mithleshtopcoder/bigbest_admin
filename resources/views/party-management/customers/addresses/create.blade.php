@extends('layouts.app')

@section('title', 'Create Customer Address')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
        <div class="page-header-left d-flex align-items-center">
            <h1 class="page-title mb-0">Create Customer Address</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('party-management.customers.addresses') }}">Customer Addresses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Address</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('party-management.customers.addresses.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="customer_id" class="form-label">
                        Customer <span class="text-danger">*</span>
                    </label>

                    <select name="customer_id" id="customer_id" class="form-select select2" required>
                        <option value="">Select Customer</option>

                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Address Type <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>Home</option>
                        <option value="office" {{ old('type') == 'office' ? 'selected' : '' }}>Office</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea name="address" id="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="pincode" class="form-label">Pincode <span class="text-danger">*</span></label>
                    <input type="text" name="pincode" id="pincode" class="form-control" value="{{ old('pincode') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="landmark" class="form-label">Landmark</label>
                    <input type="text" name="landmark" id="landmark" class="form-control" value="{{ old('landmark') }}">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4 pt-2 border-top">
                <button type="submit" class="btn btn-primary">
                    <i class="feather-save"></i> Create
                </button>
                <a href="{{ route('party-management.customers.addresses') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#customer_id').select2({
            placeholder: "Select Customer"
            , allowClear: true
            , width: '100%'
        });
    });

</script>
@endsection
