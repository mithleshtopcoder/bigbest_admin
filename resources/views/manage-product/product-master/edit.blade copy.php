@extends('layouts.app')
@section('title', 'Create Product')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Edit Product</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item text-muted">Manage Product</li>
                    <li class="breadcrumb-item text-muted"><a href="{{ route('manage-product.product-master.index') }}" class="text-decoration-none">Product Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Product</li>
                </ol>
            </nav>
        </div>
    </div>
</div>


    <div class="main-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center py-1">
                        <h5 class="card-title mb-0">Product Information</h5>
                        <div class="d-flex gap-1">
                            <button class="btn btn-xs btn-danger btn-card-remove" data-bs-toggle="tooltip" title="Delete"><i class="bi bi-trash"></i></button>
                            <button class="btn btn-xs btn-warning btn-card-refresh" data-bs-toggle="tooltip" title="Refresh"><i class="bi bi-arrow-clockwise"></i></button>
                            <button class="btn btn-xs btn-success btn-card-fullscreen" data-bs-toggle="tooltip" title="Maximize"><i class="bi bi-arrows-fullscreen"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('manage-product.product-master.update', $product->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row ps-2">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Type</label>
                                        <div class="col-md-7 ps-1">

                                            <select name="item_type" class="form-control">
                                                <option value="">Select Item Type</option>
                                                @foreach($itemTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('item_type', $product->item_type ?? '') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Code</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="item_code" id="item_code" class="form-control" placeholder="Enter item code" value="{{ old('item_code', $product->barcode ?? '') }}"> </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Category</label>
                                        <div class="col-md-7 ps-1">
                                            <select id="category_id" name="category_id" class="form-control">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Name</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}"> </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Sub Category</label>
                                        <div class="col-md-7 ps-1">
                                            <select id="sub_category_id" name="sub_category_id" class="form-control">
                                                <option value="">Select Sub Category</option>
                                                @if(!empty($subCategories))
                                                @foreach($subCategories as $sub)
                                                <option value="{{ $sub->id }}" {{ old('sub_category_id', $product->sub_category_id ?? '') == $sub->id ? 'selected' : '' }}>
                                                    {{ $sub->name }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 form-label">Barcode No</label>
                                        <div class="col-md-6 ps-1">
                                            <input type="text" name="barcode" id="barcode_no" class="form-control" placeholder="Enter barcode no" value="{{ old('barcode', $product->barcode ?? '') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-secondary btn-sm px-2" style="height: 32px;" onclick="regenerateBarcode()" title="View Barcode">
                                                <i class="feather-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row ps-2">
                                <div class="col-md-8 ps-1">
                                    <div class="row">
                                        <div class="col-md-3 ps-1 pr-0">
                                            <div class="form-group">
                                                <label class="form-label custom-label col-md-12 ps-2">Description</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check form-switch ps-0">
                                                    <label class="form-label custom-label col-md-12 rstc-temp-label ps-2" style="display:flex;justify-content:space-between;">
                                                        <span>Single Item</span>
                                                        <input class="form-check-input item-type" name="inventory_item" value="1" id="inventory-item" type="checkbox">
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check form-switch ps-0">
                                                    <label class="form-label custom-label col-md-12 rstc-temp-label ps-2" style="display:flex;justify-content:space-between;">
                                                        <span>Multiple Item</span>
                                                        <input class="form-check-input item-type" name="sales_item" value="1" id="sales-item" type="checkbox">
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-9 ps-0">
                                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Enter description" style="height: 100px;">{{ old('description', $product->description ?? '') }}</textarea> </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="form-label text-md col-md-4 custom-label">
                                            Image
                                        </label>

                                        <div class="col-md-6 ps-1">
                                            <input type="file" name="thumbnail_image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                                        </div>
                                        <div class="col-md-2 ps-1">
                                            Show
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row ps-2">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item Brand</label>
                                        <div class="col-md-7 ps-1">
                                            <select name="brand_id" id="brand_id" class="form-control">
                                                <option value="">Select Brand</option>

                                                @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Item SKU</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="sku" class="form-control" placeholder="Enter item sku" value="{{ old('sku', $product->sku ?? '') }}"> </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Collection</label>
                                        <div class="col-md-7 ps-1">
                                            <select name="collection" class="form-control">
                                                <option value="">Select Collection</option>

                                                @foreach($collections as $collection)
                                                <option value="{{ $collection->id }}" {{ old('collection', $product->collection ?? '') == $collection->id ? 'selected' : '' }}>
                                                    {{ $collection->name }}
                                                </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-5 form-label">Season</label>
                                        <div class="col-md-7 ps-1">
                                            <select name="season" class="form-control">
                                                <option value="">Select Season</option>

                                                @foreach ($seasons as $season)
                                                <option value="{{ $season->id }}" {{ old('season', $product->season ?? '') == $season->id ? 'selected' : '' }}>
                                                    {{ $season->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" style="align-items: center; display: flex; justify-content: center;">
                                    <button class="btn btn-sm btn-primary">
                                        <i class="feather-plus"></i>
                                        Create Item
                                    </button>
                                </div>
                            </div>
                        </form>






                        <!-- Tabs Start -->
                        <div class="row mt-4">
                            <ul class="nav nav-tabs nav-tabs-custom-style mb-2" id="productTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="iteminfo-tab" data-bs-toggle="tab" data-bs-target="#itemInfo" type="button" role="tab" aria-controls="itemInfo" aria-selected="true">
                                        <i class="feather-info me-2"></i>Item Info
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="configuration-tab" data-bs-toggle="tab" data-bs-target="#configuration" type="button" role="tab" aria-controls="configuration" aria-selected="false">
                                        <i class="feather-settings me-2"></i>Configuration
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="managestock-tab" data-bs-toggle="tab" data-bs-target="#manageStock" type="button" role="tab" aria-controls="manageStock" aria-selected="false">
                                        <i class="feather-layers me-2"></i>Manage Stock
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="manageprice-tab" data-bs-toggle="tab" data-bs-target="#managePrice" type="button" role="tab" aria-controls="managePrice" aria-selected="false">
                                        <i class="feather-dollar-sign me-2"></i>Manage Price
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="managediscount-tab" data-bs-toggle="tab" data-bs-target="#manageDiscount" type="button" role="tab" aria-controls="manageDiscount" aria-selected="false">
                                        <i class="feather-percent me-2"></i>Manage Discount
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content" id="productTabsContent">
                                <!-- Item Info Tab -->
                                <div class="tab-pane fade show active" id="itemInfo" role="tabpanel" aria-labelledby="iteminfo-tab">
                                    <div class="col-md-8 mx-auto">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Unit</label>
                                                    <div class="col-md-7 ps-1">
                                                        <select name="unit" class="form-control" required>
                                                            <option value="">Select Unit</option>
                                                            @foreach(['pcs','pack','box','bottle','bag','dozen'] as $unit)
                                                            <option value="{{ $unit }}" {{ old('unit', $product->unit ?? '') == $unit ? 'selected' : '' }}>
                                                                {{ ucfirst($unit) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Weight Unit</label>
                                                    <div class="col-md-7 ps-1">
                                                        <select name="weight_unit" class="form-control" required>
                                                            <option value="">Select Weight Unit</option>
                                                            @foreach(['g','kg','ml','l'] as $w)
                                                            <option value="{{ $w }}" {{ old('weight_unit', $product->weight_unit ?? '') == $w ? 'selected' : '' }}>
                                                                {{ strtoupper($w) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                

                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Status</label>
                                                    <div class="col-md-7 ps-1">
                                                        <select name="status" class="form-control">
                                                            <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>
                                                                Active
                                                            </option>
                                                            <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                                                Inactive
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Min Order Qty</label>
                                                    <div class="col-md-7 ps-1">
                                                        <input type="number" name="min_order_quantity" class="form-control" value="{{ old('min_order_quantity', $product->min_order_quantity ?? 1) }}"> 
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group row">
                                                    <label class="form-label col-md-5">Track Inventory</label>
                                                    <div class="col-md-7 ps-1">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory ?? 1) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="trackInventory">Enable inventory tracking</label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="form-group row">
                                                    <label class="form-label col-md-5">Allow Backorder</label>
                                                    <div class="col-md-7 ps-1">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="allow_backorder" value="1" {{ old('allow_backorder', $product->allow_backorder ?? 0) ? 'checked' : '' }}> <label class="form-check-label" for="allowBackorder">Allow backorder when out of stock</label>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Weight (grams)</label>
                                                    <div class="col-md-7 ps-1">
                                                        <input type="number" name="weight" class="form-control" step="0.01" value="{{ old('weight', $product->weight ?? '') }}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Dimensions (L x W x H)</label>
                                                    <div class="col-md-7 ps-1">
                                                        <div class="row">
                                                            <div class="col-4">
                                                                <input type="number" name="length" class="form-control" placeholder="Length" step="0.01">
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="number" name="width" class="form-control" placeholder="Width" step="0.01">
                                                            </div>
                                                            <div class="col-4">
                                                                <input type="number" name="height" class="form-control" placeholder="Height" step="0.01">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="feather-save"></i>Save Product
                                                    </button>
                                                    <a href="{{ route('manage-product.product-master.index') }}" class="btn btn-light btn-sm">
                                                        <i class="feather-x"></i> Cancel
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Configuration Tab -->
                                <div class="tab-pane fade" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">

                                                <table class="table table-bordered align-middle" id="variantTable">

                                                    <!-- ================= HEADER ================= -->
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="30">
                                                                <input type="checkbox" id="selectAllVariants">
                                                            </th>
                                                            <th>Variant Name</th>
                                                            <th>SKU</th>
                                                            <th>Variant Type</th>
                                                            <th>Variant Value</th>
                                                            <th>Barcode</th>
                                                            <th>Image</th>
                                                            <th>Status</th>
                                                            <th class="text-end">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <!-- ================= BODY ================= -->
                                                    <tbody id="variantTableBody">

                                                        <!-- ===== Existing Backend Variants ===== -->
                                                        @foreach($product->variants as $i => $variant)
                                                        <tr class="variant-row" data-index="{{ $i }}">
                                                            <td><input type="checkbox" name="variants[{{ $i }}][selected]" checked></td>

                                                            <td>
                                                                {{ $variant->name }}
                                                                <input type="hidden" name="variants[{{ $i }}][name]" value="{{ $variant->name }}" class="variant-name">
                                                            </td>

                                                            <td>
                                                                {{ $variant->sku ?? '-' }}
                                                                <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                                                                <input type="hidden" name="variants[{{ $i }}][sku]" value="{{ $variant->sku }}">
                                                            </td>

                                                            <td>
                                                                <select name="variants[{{ $i }}][variant_type]" class="form-select form-select-sm">
                                                                    <option value="weight" {{ $variant->variant_type=='weight'?'selected':'' }}>weight</option>
                                                                    <option value="quantity" {{ $variant->variant_type=='quantity'?'selected':'' }}>quantity</option>
                                                                    <option value="packaging" {{ $variant->variant_type=='packaging'?'selected':'' }}>packaging</option>
                                                                    <option value="size" {{ $variant->variant_type=='size'?'selected':'' }}>size</option>
                                                                    <option value="unit" {{ $variant->variant_type=='unit'?'selected':'' }}>unit</option>
                                                                </select>
                                                            </td>

                                                            <td>
                                                                {{ $variant->variant_value }}
                                                                <input type="hidden" name="variants[{{ $i }}][variant_value]" value="{{ $variant->variant_value }}">
                                                            </td>

                                                            <td>
                                                                {{ $variant->barcode }}
                                                                <input type="hidden" name="variants[{{ $i }}][barcode]" value="{{ $variant->barcode }}">
                                                            </td>

                                                            <td>
                                                                {{-- EXISTING IMAGES --}}
                                                                <div class="d-flex flex-wrap gap-2 mb-2">
                                                                    @foreach($variant->images as $img)
                                                                    <div class="position-relative">
                                                                        <img src="{{ asset($img->image_path) }}" class="border rounded" width="50" height="50">
                                                                    </div>
                                                                    @endforeach
                                                                </div>

                                                                {{-- UPLOAD NEW IMAGES --}}
                                                                <input type="file" name="variants[{{ $i }}][images][]" class="form-control" multiple>
                                                            </td>


                                                            <td>
                                                                <span class="badge {{ $variant->is_active?'bg-soft-success text-success':'bg-soft-danger text-danger' }}">
                                                                    {{ $variant->is_active?'Active':'Inactive' }}
                                                                </span>
                                                                <input type="hidden" name="variants[{{ $i }}][is_active]" value="{{ $variant->is_active }}">
                                                            </td>

                                                            <td class="text-end">
                                                                <i class="feather-edit text-primary me-2" onclick="editVariantRow(this)"></i>
                                                                <i class="feather-trash-2 text-danger" onclick="removeVariantRow(this)"></i>
                                                            </td>
                                                        </tr>
                                                        @endforeach

                                                        <!-- ===== New Variant Row Template ===== -->
                                                        <tr class="bg-light" id="newVariantRow">
                                                            <td></td>
                                                            <td><input type="text" name="new_variant[name]" class="form-control" placeholder="Variant Name"></td>
                                                            <td><input type="text" name="new_variant[sku]" class="form-control" placeholder="SKU"></td>
                                                            <td>
                                                                <select name="new_variant[variant_type]" class="form-select form-select-sm">
                                                                    <option value="">Select Type</option>
                                                                    <option value="weight">weight</option>
                                                                    <option value="quantity">quantity</option>
                                                                    <option value="packaging">packaging</option>
                                                                    <option value="size">size</option>
                                                                    <option value="unit">unit</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" name="new_variant[variant_value]" class="form-control" placeholder="Variant Value"></td>
                                                            <td><input type="text" name="new_variant[barcode]" class="form-control" placeholder="Barcode"></td>
                                                            <td>
                                                                <input type="file" class="form-control" name="new_variant[images][]" multiple>
                                                            </td>
                                                            <td>
                                                                <select name="new_variant[is_active]" class="form-select form-select-sm">
                                                                    <option value="1" selected>Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-success" onclick="addVariantRow()">Add</button>
                                                            </td>
                                                        </tr>

                                                    </tbody>

                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manage Stock Tab -->
                                <div class="tab-pane fade" id="manageStock" role="tabpanel" aria-labelledby="managestock-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="stockTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Warehouse/Location</th>
                                                            <th>Product Variant</th>
                                                            <th>Initial Stock <span class="text-danger">*</span></th>
                                                            <th>Minimum Stock</th>
                                                            <th>Maximum Stock</th>
                                                            <th>Stock Alert</th>
                                                            <th class="text-end">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="stockTableBody">
                                                        @php $row = 0; @endphp

                                                        @foreach($product->variants as $variant)
                                                        @foreach($variant->stocks as $stock)
                                                        <tr>
                                                            {{-- Store --}}
                                                            <td>
                                                                <select name="stock[{{ $row }}][store_id]" class="form-control" required>
                                                                    @foreach($stores as $store)
                                                                    <option value="{{ $store->id }}" {{ $stock->store_id == $store->id ? 'selected' : '' }}>
                                                                        {{ $store->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="stock[{{ $row }}][product_variant]" class="form-control stock-variant">
                                                                    <option value="">Select Variant</option>
                                                                    @foreach($product->variants as $v)
                                                                    <option value="{{ $v->name }}" {{ $v->id == $variant->id ? 'selected' : '' }}>
                                                                        {{ $v->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="stock[{{ $row }}][stock_quantity]" value="{{ $stock->quantity }}" class="form-control" required>
                                                            </td>

                                                            {{-- Min --}}
                                                            <td>
                                                                <input type="number" name="stock[{{ $row }}][min_stock]" value="{{ $stock->min_stock_level }}" class="form-control">
                                                            </td>

                                                            {{-- Max --}}
                                                            <td>
                                                                <input type="number" name="stock[{{ $row }}][max_stock]" value="{{ $stock->max_stock_level }}" class="form-control">
                                                            </td>

                                                            {{-- Alert --}}
                                                            <td class="text-center">
                                                                <input type="checkbox" name="stock[{{ $row }}][stock_alert]" value="1" {{ $stock->stock_alert ? 'checked' : '' }}>
                                                            </td>

                                                            {{-- Action --}}
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeStockRow(this)">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </td>

                                                            {{-- 🔑 REQUIRED FOR UPDATE --}}
                                                            <input type="hidden" name="stock[{{ $row }}][id]" value="{{ $stock->id }}">
                                                        </tr>

                                                        @php $row++; @endphp
                                                        @endforeach
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addStockRow()">
                                                    <i class="feather-plus me-2"></i>Add Row
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manage Price Tab -->
                                <div class="tab-pane fade" id="managePrice" role="tabpanel" aria-labelledby="manageprice-tab">
                                    <div class="row">
                                        <div class="col-12">

                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle" id="priceTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Store / Location</th>
                                                            <th>Product Variant</th>
                                                            <th>Cost Price</th>
                                                            <th>Selling Price <span class="text-danger">*</span></th>
                                                            <th>MRP</th>
                                                            <th>Margin (%)</th>
                                                            <th class="text-end">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="priceTableBody">
                                                        @php $row = 0; @endphp

                                                        @foreach($product->variants as $variant)
                                                        @foreach($variant->prices as $price)
                                                        <tr>
                                                            {{-- Store --}}

                                                            <td>
                                                                <select name="price[{{ $row }}][store_id]" class="form-control" required>
                                                                    <option value="">Select Store</option>

                                                                    @foreach($stores as $store)
                                                                    <option value="{{ $store->id }}" {{ $price->store_id == $store->id ? 'selected' : '' }}>
                                                                        {{ $store->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            {{-- Variant --}}
                                                            <td>
                                                                <select name="price[{{ $row }}][product_variant_id]" class="form-control" required>
                                                                    @foreach($product->variants as $v)
                                                                    <option value="{{ $v->id }}" {{ $v->id == $price->product_variant_id ? 'selected' : '' }}>
                                                                        {{ $v->name }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>

                                                            {{-- Cost --}}
                                                            <td>
                                                                <input type="number" name="price[{{ $row }}][cost_price]" value="{{ $price->cost_price }}" class="form-control cost-price" step="0.01">
                                                            </td>

                                                            {{-- Selling --}}
                                                            <td>
                                                                <input type="number" name="price[{{ $row }}][price]" value="{{ $price->price }}" class="form-control selling-price" step="0.01" required>
                                                            </td>

                                                            {{-- MRP --}}
                                                            <td>
                                                                <input type="number" name="price[{{ $row }}][compare_at_price]" value="{{ $price->compare_at_price }}" class="form-control" step="0.01">
                                                            </td>

                                                            {{-- Margin --}}
                                                            <td>
                                                                <input type="text" class="form-control margin" readonly>
                                                            </td>

                                                            {{-- Action --}}
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removePriceRow(this)">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>

                                                        @php $row++; @endphp
                                                        @endforeach
                                                        @endforeach
                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addPriceRow()">
                                                    <i class="feather-plus me-2"></i>Add Row
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- Manage Discount Tab -->
                                <div class="tab-pane fade" id="manageDiscount" role="tabpanel" aria-labelledby="managediscount-tab">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="discountTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Store/Location</th>
                                                            <th>Discount Type</th>
                                                            <th>Discount Value</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Min Quantity</th>
                                                            <th>Max Discount</th>
                                                            <th>Apply</th>
                                                            <th class="text-end">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="discountTableBody">
                                                        <tr>
                                                            <td>
                                                                <select name="discount[0][store_location]" class="form-control" required>
                                                                    <option value="">Select Store</option>
                                                                    <option value="1" selected>Main Store</option>
                                                                    <option value="2">Store 1</option>
                                                                    <option value="3">Store 2</option>
                                                                    <option value="4">Store 3</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="discount[0][discount_type]" class="form-control">
                                                                    <option value="">No Discount</option>
                                                                    <option value="percentage" selected>Percentage (%)</option>
                                                                    <option value="fixed">Fixed Amount</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[0][discount_value]" class="form-control" placeholder="Discount value" step="0.01" value="10.00">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[0][discount_start_date]" class="form-control" value="2024-01-01">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[0][discount_end_date]" class="form-control" value="2024-12-31">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[0][discount_min_qty]" class="form-control" placeholder="Min qty" value="2">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[0][max_discount]" class="form-control" placeholder="Max discount" step="0.01" value="2000.00">
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="discount[0][apply_discount]" value="1" checked>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiscountRow(this)">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <select name="discount[1][store_location]" class="form-control" required>
                                                                    <option value="">Select Store</option>
                                                                    <option value="1">Main Store</option>
                                                                    <option value="2" selected>Store 1</option>
                                                                    <option value="3">Store 2</option>
                                                                    <option value="4">Store 3</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="discount[1][discount_type]" class="form-control">
                                                                    <option value="">No Discount</option>
                                                                    <option value="percentage">Percentage (%)</option>
                                                                    <option value="fixed" selected>Fixed Amount</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[1][discount_value]" class="form-control" placeholder="Discount value" step="0.01" value="1500.00">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[1][discount_start_date]" class="form-control" value="2024-02-01">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[1][discount_end_date]" class="form-control" value="2024-11-30">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[1][discount_min_qty]" class="form-control" placeholder="Min qty" value="3">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[1][max_discount]" class="form-control" placeholder="Max discount" step="0.01" value="3000.00">
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="discount[1][apply_discount]" value="1" checked>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiscountRow(this)">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <select name="discount[2][store_location]" class="form-control" required>
                                                                    <option value="">Select Store</option>
                                                                    <option value="1">Main Store</option>
                                                                    <option value="2">Store 1</option>
                                                                    <option value="3" selected>Store 2</option>
                                                                    <option value="4">Store 3</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="discount[2][discount_type]" class="form-control">
                                                                    <option value="">No Discount</option>
                                                                    <option value="percentage" selected>Percentage (%)</option>
                                                                    <option value="fixed">Fixed Amount</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[2][discount_value]" class="form-control" placeholder="Discount value" step="0.01" value="15.00">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[2][discount_start_date]" class="form-control" value="2024-03-01">
                                                            </td>
                                                            <td>
                                                                <input type="date" name="discount[2][discount_end_date]" class="form-control" value="2024-10-31">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[2][discount_min_qty]" class="form-control" placeholder="Min qty" value="1">
                                                            </td>
                                                            <td>
                                                                <input type="number" name="discount[2][max_discount]" class="form-control" placeholder="Max discount" step="0.01" value="2500.00">
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-switch">
                                                                    <input class="form-check-input" type="checkbox" name="discount[2][apply_discount]" value="1">
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="removeDiscountRow(this)">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="d-flex justify-content-end gap-2 mt-3">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addDiscountRow()">
                                                    <i class="feather-plus me-2"></i>Add Row
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Form Actions End -->

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 31px !important;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.875rem;
    }
    select2-search--dropdown {
        display: block !important;
    }
    .select2-search__field {
        width: 100% !important;
    }
    .form-group.image-container .col {
        width: 80px;
        height: 80px;
        overflow: hidden;
        border-radius: 5px;
        border: 1px solid #ccc;
        margin: 1px;
        display: flex;
        justify-content: center;
    }
    .form-group.image-container .col span {
        margin: -5px -67px 1px 1px;
        position: absolute;
        border-radius: 5px;
        padding: 5px;
        cursor: pointer;
    }
    .form-group.image-container .col span a {
        color: rgb(220, 5, 5)
    }
</style>
@endsection
