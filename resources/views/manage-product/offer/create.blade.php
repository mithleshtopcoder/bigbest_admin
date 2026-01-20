@extends('layouts.app')

@section('title', 'Create ' . ucfirst(str_replace('-', ' ', $type)))

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="card">
            <div class="card-header">
                <h5>Create {{ ucfirst(str_replace('-', ' ', $type)) }}</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <form action="{{ route('manage-product.offer.store', $type) }}" method="POST">
                    @csrf
                    <div class="row ps-2">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="form-label col-md-2">Coupon Code <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                </div>
                                <label class="form-label col-md-2">Coupon Name <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="form-label col-md-2" style="height: 80px;">Description</label>
                                <div class="col-md-10 ps-1">
                                    <textarea name="description" class="form-control" style="height: 80px;" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Discount Type <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <select name="discount_type" class="form-select form-control" required>
                                        <option value="percentage" {{ old('discount_type')=='percentage'?'selected':'' }}>Percentage</option>
                                        <option value="fixed" {{ old('discount_type')=='fixed'?'selected':'' }}>Fixed Amount</option>
                                    </select>
                                </div>
                                <div class="col-md-6 ps-1"></div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Discount Value <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                                    <input type="number" step="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
                                </div>
                                <div class="col-md-6 ps-1"></div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="form-label col-md-2">Mini. Order Amount</label>
                                <div class="col-md-4 ps-1">
                                    <input type="number" step="0.01" name="minimum_order_amount" class="form-control" value="{{ old('minimum_order_amount') }}">
                        </div>
                                <label class="form-label col-md-2">Max. Disc. Amount</label>
                                <div class="col-md-4 ps-1">
                            <input type="number" step="0.01" name="maximum_discount_amount" class="form-control" value="{{ old('maximum_discount_amount') }}">
                        </div>
                    </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Applicable To</label>
                                <div class="col-md-4 ps-1">
                                    <select name="applicable_to" class="form-select form-control">
                                        <option value="all" {{ old('applicable_to')=='all'?'selected':'' }}>All</option>
                                        <option value="category" {{ old('applicable_to')=='category'?'selected':'' }}>Category</option>
                                        <option value="sub_category" {{ old('applicable_to')=='sub_category'?'selected':'' }}>Sub Category</option>
                                        <option value="product" {{ old('applicable_to')=='product'?'selected':'' }}>Product</option>
                                        <option value="brand" {{ old('applicable_to')=='brand'?'selected':'' }}>Brand</option>
                            </select>
                        </div>
                                <label class="form-label col-md-2">Brand</label>
                                <div class="col-md-4 ps-1">
                                    <select name="brand_id" class="form-select form-control">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id?'selected':'' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Category</label>
                                <div class="col-md-4 ps-1">
                                    <select name="category_id" id="category_id" class="form-select form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id')==$category->id?'selected':'' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                                <label class="form-label col-md-2">Sub Category</label>
                                <div class="col-md-4 ps-1">
                                    <select name="sub_category_id" id="sub_category_id" class="form-select form-control">
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>
                    </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Product</label>
                                <div class="col-md-4 ps-1">
                                    <select name="product_id" id="product_id" class="form-select form-control">
                            <option value="">Select Product</option>
                        </select>
                    </div>
                                <label class="form-label col-md-2">Valid From <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                            <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', now()->format('Y-m-d')) }}" required>
                        </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Valid To <span class="text-danger">*</span></label>
                                <div class="col-md-4 ps-1">
                            <input type="date" name="valid_to" class="form-control" value="{{ old('valid_to', now()->addMonth()->format('Y-m-d')) }}" required>
                        </div>
                                <label class="form-label col-md-2">Usage Limit</label>
                                <div class="col-md-4 ps-1">
                            <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}">
                        </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Usage Limit Per User</label>
                                <div class="col-md-4 ps-1">
                            <input type="number" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user') }}">
                        </div>
                                <div class="col-md-6 ps-1">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', 1) ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                                    <div class="form-check form-switch mt-1">
                                        <input type="checkbox" name="is_first_order_only" value="1" class="form-check-input" {{ old('is_first_order_only') ? 'checked' : '' }}>
                            <label class="form-check-label">First Order Only</label>
                                    </div>
                        </div>
                    </div>

                            <div class="form-group row">
                                <label class="form-label col-md-2">Terms & Conditions</label>
                                <div class="col-md-10 ps-1">
                        <textarea name="terms_conditions" class="form-control" rows="3">{{ old('terms_conditions') }}</textarea>
                                </div>
                    </div>

                            <div class="form-group row">
                                <div class="col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-success">Create Coupon</button>
                        <a href="{{ route('manage-product.offer.index', $type) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // EXISTING CATEGORY → SUBCATEGORY (UNCHANGED)
    document.getElementById('category_id').addEventListener('change', function() {
        let categoryId = this.value;
        let url = "{{ route('manage-product.offer.get-subcategories', ':id') }}";
        url = url.replace(':id', categoryId);

        // reset
        document.getElementById('sub_category_id').innerHTML =
            '<option value="">Select Sub Category</option>';
        document.getElementById('product_id').innerHTML =
            '<option value="">Select Product</option>';

        if (!categoryId) return;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                let sub = document.getElementById('sub_category_id');
                data.forEach(item => {
                    sub.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            });
    });

    // ✅ NEW: SUBCATEGORY → PRODUCT
    document.getElementById('sub_category_id').addEventListener('change', function() {
        let categoryId = document.getElementById('category_id').value;
        let subCategoryId = this.value;

        let product = document.getElementById('product_id');
        product.innerHTML = '<option value="">Select Product</option>';

        if (!categoryId || !subCategoryId) return;

        let url = "{{ route('manage-product.offer.get.products', [':cat', ':sub']) }}";
        url = url.replace(':cat', categoryId).replace(':sub', subCategoryId);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                data.forEach(item => {
                    product.innerHTML +=
                        `<option value="${item.id}">${item.name}</option>`;
                });
            });
    });

</script>
@endsection
