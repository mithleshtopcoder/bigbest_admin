@extends('layouts.app')
@section('title', 'Edit Product')
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
                    <form method="POST" action="{{ route('vendor.products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                                        <input type="text" name="item_code" id="item_code" class="form-control" placeholder="Enter item code" value="{{ old('item_code', $product->item_code ?? '') }}">
                                    </div>
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
                                    <i class="bi bi-save"></i>
                                    Save Item
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
                                <button class="nav-link" id="stockMaster-tab" data-bs-toggle="tab" data-bs-target="#stockMaster" type="button" role="tab" aria-controls="stockMaster" aria-selected="false">
                                    <i class="feather-layers me-2"></i>Stock Master
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
                                    <form method="POST" action="{{ route('vendor.products.updateinfo', $product->id) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
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
                                                <div class="form-group row">
                                                    <label class="form-label col-md-5">Max Order Qty</label>
                                                    <div class="col-md-7 ps-1">
                                                        <input type="number" name="max_order_quantity" class="form-control" value="{{ old('max_order_quantity', $product->max_order_quantity ?? 1) }}">
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
            </form>
        </div>
    </div>

    <!-- Configuration Tab -->
    <div class="tab-pane fade" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-sm" id="variantTable">
                        <!-- ================= HEADER ================= -->
                        <thead class="table-light">
                            <tr>
                                <th width="30">No.</th>
                                <th>Variant Name</th>
                                <th>Code</th>
                                <th>SKU</th>
                                <th>Unit</th>
                                <th>Unit Value</th>
                                <th>Barcode</th>
                                <th>Image</th>
                                <th>Default</th>
                                <th>Status</th>
                                <th class="text-end d-flex gap-2">Action</th>
                            </tr>
                            <tr id="variantInputRow">
                                <th><input type="text" class="form-control" style="width: 20px;" disabled></th>
                                <th><input type="text" class="form-control" name="variant_name" placeholder="Variant Name"></th>
                                <th><input type="text" class="form-control" name="variant_code" placeholder="Code"></th>
                                <th><input type="text" class="form-control" name="variant_sku" placeholder="SKU (auto-generated)" title="SKU will be auto-generated from product SKU, variant name, and code. You can edit it manually."></th>
                                <th>
                                    <select class="form-control" name="variant_unit">
                                        <option value="piece">Piece</option>
                                        <option value="kg">Kg</option>
                                        <option value="gram">Gram</option>
                                        <option value="liter">Liter</option>
                                        <option value="ml">ML</option>
                                        <option value="bundle">Bundle</option>
                                        <option value="bunch">Bunch</option>
                                        <option value="packet">Packet</option>
                                        <option value="box">Box</option>
                                        <option value="bottle">Bottle</option>
                                        <option value="can">Can</option>
                                        <option value="dozen">Dozen</option>
                                        <option value="pack">Pack</option>
                                        <option value="loose">Loose</option>
                                    </select>
                                </th>
                                <th><input type="text" class="form-control" name="variant_unit_value" placeholder="Unit Value"></th>
                                <th><input type="text" class="form-control" name="variant_barcode" placeholder="Barcode (auto-generated)" title="Barcode will be auto-generated from SKU. You can edit it manually."></th>
                                <th><input type="text" class="form-control" style="width: 20px;" disabled></th>
                                <th>
                                    <select class="form-control" name="variant_is_default">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control" name="variant_is_active">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </th>
                                <th>
                                    <button type="button" id="variantSubmitBtn" class="btn btn-sm btn-primary" style="font-size: 12px;padding: 0.15rem 0.3rem;" onclick="addVariant()"><i class="bi bi-plus-circle"></i> Add</button>
                                    <button type="button" id="variantCancelBtn" class="btn btn-sm btn-secondary" style="font-size: 12px;padding: 0.15rem 0.3rem; display: none;" onclick="cancelEdit()"><i class="bi bi-x-circle"></i> Cancel</button>
                                </th>
                            </tr>
                        </thead>

                        <!-- ================= BODY ================= -->
                        <tbody id="variantTableBody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Master Tab -->
    <div class="tab-pane fade" id="stockMaster" role="tabpanel" aria-labelledby="stockMaster-tab">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="manageStockTable">
                        <thead>
                            <tr>
                                <th>Variant Name</th>
                                <th>Item No.</th>
                                <th>In Stock</th>
                                <th>Available</th>
                                <th>In Transit</th>
                            </tr>
                        </thead>
                        <tbody id="stockTableBody">
                            @foreach($product->variants as $variant)
                            <tr>
                                <td>{{ $variant->name }}</td>
                                <td>{{ $variant->sku }}</td>
                                @php
                                $inStockQty = $variant->stocks->sum('quantity');
                                $availableQty = $variant->stocks->sum(function ($stock) {
                                return max(($stock->quantity ?? 0) - ($stock->reserved_quantity ?? 0), 0);
                                });
                                $inTransitQty = $variant->stocks->sum('in_transit_quantity');
                                @endphp
                                <td><span style="cursor: pointer; color: #0d6efd;" onclick="showInStockModal({{ $variant->id }}, '{{ $variant->name }}')">{{ $inStockQty }}</span></td>
                                <td><span style="cursor: pointer; color: #0d6efd;" onclick="showAvailableModal({{ $variant->id }}, '{{ $variant->name }}')">{{ $availableQty }}</span></td>
                                <td><span style="cursor: pointer; color: #0d6efd;" onclick="showInTransitModal({{ $variant->id }}, '{{ $variant->name }}')">{{ $inTransitQty }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Manage Stock Tab -->
    <div class="tab-pane fade" id="manageStock" role="tabpanel" aria-labelledby="managestock-tab">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="stockTable">
                        <thead>
                            <tr>
                                <th>Warehouse/Location</th>
                                <th>Product Variant</th>
                                <th>Item No. (SKU)</th>
                                <th>Quantity</th>
                                <th>Min Stock</th>
                                <th>Max Stock</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Created At</th>
                                <th class="text-end">Action</th>
                            </tr>
                            <tr id="stockInputRow">
                                <th>
                                    <select name="stock_store_id" id="stock_store_id" class="form-control" required>
                                        <option value="">Select Store</option>
                                        @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select name="stock_variant_id" id="stock_variant_id" class="form-control" required>
                                        <option value="">Select Variant</option>
                                        @foreach($product->variants as $v)
                                        <option value="{{ $v->id }}" data-sku="{{ $v->sku }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input type="text" name="stock_item_no" id="stock_item_no" class="form-control" readonly>
                                </th>
                                <th>
                                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control" step="1" min="0" required>
                                </th>
                                <th>
                                    <input type="number" name="stock_min" id="stock_min" class="form-control" step="1" min="0">
                                </th>
                                <th>
                                    <input type="number" name="stock_max" id="stock_max" class="form-control" step="1" min="0">
                                </th>
                                <th>
                                    <select name="stock_status" id="stock_status" class="form-control">
                                        <option value="in_stock">In Stock</option>
                                        <option value="low_stock">Low Stock</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                        <option value="backorder">Backorder</option>
                                    </select>
                                </th>
                                <th></th>
                                <th></th>
                                <th class="text-end">
                                    <button type="button" id="stockSubmitBtn" class="btn btn-sm btn-primary" style="font-size: 12px;padding: 0.15rem 0.3rem;" onclick="addStock()"><i class="bi bi-plus-circle"></i> Add</button>
                                    <button type="button" id="stockCancelBtn" class="btn btn-sm btn-secondary" style="font-size: 12px;padding: 0.15rem 0.3rem; display: none;" onclick="cancelStockEdit()"><i class="bi bi-x-circle"></i> Cancel</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="manageStockTableBody">
                            <tr>
                                <td colspan="10" class="text-center text-muted">No stock records found. Add stock to get started.</td>
                            </tr>
                        </tbody>

                    </table>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Manage Price Tab -->
    <div class="tab-pane fade" id="managePrice" role="tabpanel" aria-labelledby="manageprice-tab">
        <div class="row">
            <div class="col-12">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle table-sm" id="priceTable">
                        <thead class="table-light">
                            <tr>
                                <th>Variant Name</th>
                                <th>Item No. (SKU)</th>
                                <th>Cost Price</th>
                                <th>MRP (Compare At Price)</th>
                                <th>Selling Price <span class="text-danger">*</span></th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                            <tr id="priceInputRow">
                                <th>
                                    <select name="price_variant_id" id="price_variant_id" class="form-control" required>
                                        <option value="">Select Variant</option>
                                        @foreach($product->variants as $v)
                                        <option value="{{ $v->id }}" data-sku="{{ $v->sku }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input type="text" name="price_item_no" id="price_item_no" class="form-control" readonly>
                                </th>
                                <th>
                                    <input type="number" name="price_cost_price" id="price_cost_price" class="form-control" step="0.01" min="0">
                                </th>
                                <th>
                                    <input type="number" name="price_compare_at_price" id="price_compare_at_price" class="form-control" step="0.01" min="0">
                                </th>
                                <th>
                                    <input type="number" name="price_selling_price" id="price_selling_price" class="form-control" step="0.01" min="0" required>
                                </th>
                                <th>
                                    <input type="date" name="price_effective_from" id="price_effective_from" class="form-control">
                                </th>
                                <th>
                                    <input type="date" name="price_effective_to" id="price_effective_to" class="form-control">
                                </th>
                                <th>
                                    <select name="price_is_active" id="price_is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </th>
                                <th class="text-end">
                                    <button type="button" id="priceSubmitBtn" class="btn btn-sm btn-primary" style="font-size: 12px;padding: 0.15rem 0.3rem;" onclick="addPrice()"><i class="bi bi-plus-circle"></i> Add</button>
                                    <button type="button" id="priceCancelBtn" class="btn btn-sm btn-secondary" style="font-size: 12px;padding: 0.15rem 0.3rem; display: none;" onclick="cancelPriceEdit()"><i class="bi bi-x-circle"></i> Cancel</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="priceTableBody">
                            <tr>
                                <td colspan="9" class="text-center text-muted">No prices found. Add a price to get started.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Discount Tab -->
    <div class="tab-pane fade" id="manageDiscount" role="tabpanel" aria-labelledby="managediscount-tab">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="discountTable">
                        <thead>
                            <tr>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Min Order Amount</th>
                                <th>Max Discount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="discountTableBody">
                            <tr>
                                <td colspan="7" class="text-center text-muted">Loading discounts...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Variant Image Modal -->
<div class="modal fade" id="variantImageModal" tabindex="-1" aria-labelledby="variantImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantImageModalLabel">Manage Variant Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3 form-label" for="variantImageUpload">Upload New Image</label>
                            <div class="col-md-6 ps-1">
                                <input type="file" class="form-control" id="variantImageUpload" accept="image/png, image/jpeg, image/jpg, image/webp" multiple>
                                <small class="text-muted">You can select multiple images. Supported formats: PNG, JPG, JPEG, WEBP</small>
                            </div>
                            <div class="col-md-3 ps-1">
                                <button type="button" class="btn btn-primary btn-sm" onclick="uploadVariantImages()">
                                    <i class="bi bi-upload"></i> Upload Images
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <h6>Existing Images</h6>
                <div id="variantImagesContainer" class="row g-3">
                    <div class="col-12 text-center text-muted">
                        <p>No images found. Upload images to get started.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Stock Breakdown Modal -->
<div class="modal fade" id="stockBreakdownModal" tabindex="-1" aria-labelledby="stockBreakdownModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockBreakdownModalLabel">Stock Breakdown</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <th class="text-end">Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="stockBreakdownBody">
                            <tr>
                                <td colspan="2" class="text-center text-muted">No stock records found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const productId = {
        {
            $product - > id
        }
    };
    const productSku = '{{ $product->sku ?? '
    ' }}';
    let editingVariantId = null;
    let skuManuallyEdited = false;
    let barcodeManuallyEdited = false;

    // Price management variables
    let editingPriceId = null;

    // Calculate discount percentage
    function calculateDiscount() {
        const compareAtPrice = parseFloat($('#price_compare_at_price').val()) || 0;
        const sellingPrice = parseFloat($('#price_selling_price').val()) || 0;

        if (compareAtPrice > 0 && sellingPrice > 0 && compareAtPrice > sellingPrice) {
            const discount = ((compareAtPrice - sellingPrice) / compareAtPrice) * 100;
            // You can display this in a tooltip or hidden field if needed
        }
    }

    // Load variants and prices on page load
    $(document).ready(function() {
        loadVariants();
        loadPrices();

        // Auto-fill item no (SKU) when variant is selected
        $('#price_variant_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const sku = selectedOption.data('sku') || '';
            $('#price_item_no').val(sku);
        });

        // Auto-calculate discount when MRP and Selling Price change
        $('#price_compare_at_price, #price_selling_price').on('input', function() {
            calculateDiscount();
        });

        // Auto-generate SKU when variant name, code, or unit value changes
        $('input[name="variant_name"]').on('input', function() {
            if (!skuManuallyEdited && !editingVariantId) {
                generateSku();
            }
        });

        $('input[name="variant_code"]').on('input', function() {
            if (!skuManuallyEdited && !editingVariantId) {
                generateSku();
            }
        });

        $('input[name="variant_unit_value"]').on('input', function() {
            if (!skuManuallyEdited && !editingVariantId) {
                generateSku();
            }
        });

        // Track manual SKU edits
        $('input[name="variant_sku"]').on('input', function() {
            if ($(this).val().length > 0) {
                skuManuallyEdited = true;
            }
            // Auto-generate barcode when SKU changes
            if (!barcodeManuallyEdited && !editingVariantId) {
                generateBarcode();
            }
        });

        // Track manual barcode edits
        $('input[name="variant_barcode"]').on('input', function() {
            if ($(this).val().length > 0) {
                barcodeManuallyEdited = true;
            }
        });
    });

    // Generate SKU based on product SKU, variant name, and code
    function generateSku() {
        const name = $('input[name="variant_name"]').val().trim();
        const code = $('input[name="variant_code"]').val().trim();

        if (!name) {
            return;
        }

        let sku = '';

        // Use product SKU as base if available
        if (productSku) {
            sku = productSku + '-';
        }

        // Add code if available, otherwise use name
        if (code) {
            sku += code.toUpperCase().replace(/\s+/g, '-');
        } else {
            // Generate from name: take first 3-4 letters of each word
            const nameParts = name.split(/\s+/);
            const skuParts = nameParts.map(part => {
                return part.substring(0, 3).toUpperCase();
            });
            sku += skuParts.join('-');
        }

        // Add unit value if available
        const unitValue = $('input[name="variant_unit_value"]').val().trim();
        if (unitValue) {
            sku += '-' + unitValue.replace(/\s+/g, '');
        }

        // Clean up SKU (remove special characters, keep only alphanumeric and hyphens)
        sku = sku.replace(/[^A-Z0-9-]/g, '').toUpperCase();

        $('input[name="variant_sku"]').val(sku);
    }

    // Generate barcode based on SKU
    function generateBarcode() {
        const sku = $('input[name="variant_sku"]').val().trim();

        if (!sku) {
            return;
        }

        // Generate a numeric barcode from SKU
        // Convert SKU to a numeric string by hashing characters
        let barcode = '';

        // Remove all non-alphanumeric characters first
        const cleanSku = sku.replace(/[^A-Z0-9]/gi, '');

        for (let i = 0; i < cleanSku.length; i++) {
            const char = cleanSku[i];
            if (char >= '0' && char <= '9') {
                barcode += char;
            } else if ((char >= 'A' && char <= 'Z') || (char >= 'a' && char <= 'z')) {
                // Convert letter to number (A=1, B=2, ..., Z=26)
                const charCode = char.toUpperCase().charCodeAt(0);
                if (charCode >= 65 && charCode <= 90) {
                    const num = charCode - 64;
                    barcode += num.toString().padStart(2, '0');
                }
            }
        }

        // Ensure we have at least 8 digits, pad with product ID if needed
        if (barcode.length < 8) {
            const productIdStr = productId ? productId.toString() : '0000';
            barcode = barcode + productIdStr.padStart(4, '0');
        }

        // Take first 13 digits for EAN-13 format, or pad to 13
        if (barcode.length < 13) {
            // Pad with zeros at the end
            barcode = barcode.padEnd(13, '0');
        } else if (barcode.length > 13) {
            // Take first 12 digits for check digit calculation
            barcode = barcode.substring(0, 12);
        }

        // Calculate EAN-13 check digit
        let sum = 0;
        for (let i = 0; i < 12 && i < barcode.length; i++) {
            const digit = parseInt(barcode[i], 10);
            if (!isNaN(digit)) {
                sum += (i % 2 === 0) ? digit : digit * 3;
            }
        }

        // Ensure sum is valid before calculating check digit
        if (isNaN(sum)) {
            // Fallback: use a simple numeric hash
            let hash = 0;
            for (let i = 0; i < sku.length; i++) {
                hash = ((hash << 5) - hash) + sku.charCodeAt(i);
                hash = hash & hash; // Convert to 32bit integer
            }
            barcode = Math.abs(hash).toString().padStart(13, '0').substring(0, 12);
            sum = 0;
            for (let i = 0; i < 12; i++) {
                const digit = parseInt(barcode[i], 10);
                sum += (i % 2 === 0) ? digit : digit * 3;
            }
        }

        const checkDigit = (10 - (sum % 10)) % 10;
        barcode = barcode.substring(0, 12) + checkDigit;

        // Final validation - ensure barcode is all digits
        if (/^\d+$/.test(barcode) && barcode.length === 13) {
            $('input[name="variant_barcode"]').val(barcode);
        } else {
            // Last resort: generate a simple numeric barcode
            const fallbackBarcode = Math.abs(sku.split('').reduce((acc, char) => {
                return acc + char.charCodeAt(0);
            }, 0)).toString().padStart(13, '0').substring(0, 13);
            $('input[name="variant_barcode"]').val(fallbackBarcode);
        }
    }

    // Variant Image Management
    let currentVariantId = null;

    // Load all variants via AJAX
    function loadVariants() {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    // The variants already have images_count from withCount('images')
                    renderVariants(response.data);
                }
            }
            , error: function(xhr) {
                console.error('Error loading variants:', xhr);
                showAlert('Error loading variants', 'danger');
            }
        });
    }

    // Open variant image modal
    function openVariantImageModal(variantId, variantName) {
        currentVariantId = variantId;
        $('#variantImageModalLabel').text(`Manage Images - ${variantName}`);
        $('#variantImageUpload').val('');
        loadVariantImages(variantId);
        const modal = new bootstrap.Modal(document.getElementById('variantImageModal'));
        modal.show();
    }

    // Load variant images
    function loadVariantImages(variantId) {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/${variantId}/images`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    renderVariantImages(response.data);
                }
            }
            , error: function(xhr) {
                console.error('Error loading images:', xhr);
                showAlert('Error loading images', 'danger');
            }
        });
    }

    // Render variant images in modal
    function renderVariantImages(images) {
        const container = $('#variantImagesContainer');
        container.empty();

        if (images.length === 0) {
            container.append('<div class="col-12 text-center text-muted"><p>No images found. Upload images to get started.</p></div>');
            return;
        }

        images.forEach((image, index) => {
            const imageCard = `
                <div class="col-md-3">
                    <div class="card position-relative">
                        <img src="${image.image_path}" class="card-img-top" alt="${image.alt_text || 'Variant Image'}" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="primary_image" value="${image.id}" ${image.is_primary ? 'checked' : ''} onchange="setPrimaryImage(${image.id})">
                                <label class="form-check-label small">Primary</label>
                            </div>
                            <div class="d-flex gap-1 mt-2">
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteVariantImage(${image.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(imageCard);
        });
    }

    // Upload variant images
    function uploadVariantImages() {
        const files = $('#variantImageUpload')[0].files;

        if (files.length === 0) {
            showAlert('Please select at least one image to upload', 'warning');
            return;
        }

        if (!currentVariantId) {
            showAlert('Variant ID is missing', 'danger');
            return;
        }

        const formData = new FormData();
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }
        formData.append('product_variant_id', currentVariantId);
        formData.append('product_id', productId);

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/${currentVariantId}/images/store`
            , method: 'POST'
            , data: formData
            , processData: false
            , contentType: false
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#variantImageUpload').val('');
                    loadVariantImages(currentVariantId);
                    loadVariants(); // Refresh variant list to update image count
                } else {
                    showAlert(response.message || 'Upload failed', 'danger');
                }
            }
            , error: function(xhr) {
                let errorMsg = 'Upload failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('<br>');
                }
                showAlert(errorMsg, 'danger');
            }
        });
    }

    // Set primary image
    function setPrimaryImage(imageId) {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/${currentVariantId}/images/${imageId}/set-primary`
            , method: 'PUT'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    loadVariantImages(currentVariantId);
                }
            }
            , error: function(xhr) {
                showAlert('Error setting primary image', 'danger');
            }
        });
    }

    // Delete variant image
    function deleteVariantImage(imageId) {
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/${currentVariantId}/images/${imageId}/delete`
            , method: 'DELETE'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadVariantImages(currentVariantId);
                    loadVariants(); // Refresh variant list to update image count
                } else {
                    showAlert(response.message || 'Delete failed', 'danger');
                }
            }
            , error: function(xhr) {
                showAlert('Error deleting image', 'danger');
            }
        });
    }

    // Render variants in the table
    function renderVariants(variants) {
        const tbody = $('#variantTableBody');
        tbody.empty();

        if (variants.length === 0) {
            tbody.append('<tr><td colspan="10" class="text-center text-muted">No variants found</td></tr>');
            return;
        }

        variants.forEach((variant, index) => {
            const imageCount = variant.images_count || 0;
            const imageBadge = imageCount > 0 ? `<span class="badge bg-light text-dark ms-1">${imageCount}</span>` : '';
            const row = `
                <tr class="variant-row" data-id="${variant.id}">
                    <td>${index + 1}</td>
                    <td>${variant.name || '-'}</td>
                    <td>${variant.code || '-'}</td>
                    <td>${variant.sku || '-'}</td>
                    <td>${variant.unit || '-'}</td>
                    <td>${variant.unit_value || '-'}</td>
                    <td>${variant.barcode || '-'}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-info" onclick="openVariantImageModal(${variant.id}, '${(variant.name || '').replace(/'/g, "\\'")}')" title="Manage Images">
                            <i class="bi bi-image"></i>${imageBadge}
                        </button>
                    </td>
                    <td>
                        <span class="badge ${variant.is_default ? 'bg-success' : 'bg-secondary'}">
                            ${variant.is_default ? 'Yes' : 'No'}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${variant.is_active ? 'bg-success' : 'bg-danger'}">
                            ${variant.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-primary me-1" onclick="editVariant(${variant.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteVariant(${variant.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Add or Update variant
    function addVariant() {
        const formData = {
            name: $('input[name="variant_name"]').val()
            , code: $('input[name="variant_code"]').val()
            , sku: $('input[name="variant_sku"]').val()
            , barcode: $('input[name="variant_barcode"]').val()
            , unit: $('select[name="variant_unit"]').val()
            , unit_value: $('input[name="variant_unit_value"]').val()
            , is_default: $('select[name="variant_is_default"]').val() == '1' ? 1 : 0
            , is_active: $('select[name="variant_is_active"]').val() == '1' ? 1 : 0
            , sort_order: 0
        };

        // Validation
        if (!formData.name || !formData.unit) {
            showAlert('Variant Name and Unit are required', 'warning');
            return;
        }

        const url = editingVariantId ?
            `/manage-product/product-master/product/${productId}/variants/update/${editingVariantId}` :
            `/manage-product/product-master/product/${productId}/variants/store`;

        const method = editingVariantId ? 'PUT' : 'POST';

        $.ajax({
            url: url
            , method: method
            , data: formData
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    clearVariantForm();
                    loadVariants();
                } else {
                    showAlert(response.message || 'Operation failed', 'danger');
                }
            }
            , error: function(xhr) {
                let errorMsg = 'Operation failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('<br>');
                }
                showAlert(errorMsg, 'danger');
            }
        });
    }

    // Edit variant - populate form
    function editVariant(id) {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    const variant = response.data.find(v => v.id === id);
                    if (variant) {
                        editingVariantId = variant.id;
                        skuManuallyEdited = true; // Don't auto-generate when editing
                        barcodeManuallyEdited = true; // Don't auto-generate when editing
                        $('input[name="variant_name"]').val(variant.name);
                        $('input[name="variant_code"]').val(variant.code || '');
                        $('input[name="variant_sku"]').val(variant.sku || '');
                        $('input[name="variant_barcode"]').val(variant.barcode || '');
                        $('select[name="variant_unit"]').val(variant.unit);
                        $('input[name="variant_unit_value"]').val(variant.unit_value || '');
                        $('select[name="variant_is_default"]').val(variant.is_default ? '1' : '0');
                        $('select[name="variant_is_active"]').val(variant.is_active ? '1' : '0');

                        // Update button text
                        $('#variantSubmitBtn').html('<i class="bi bi-check-circle"></i> Update');
                        $('#variantCancelBtn').show();

                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#variantInputRow').offset().top - 100
                        }, 500);
                    }
                }
            }
            , error: function(xhr) {
                showAlert('Error loading variant data', 'danger');
            }
        });
    }

    // Cancel edit
    function cancelEdit() {
        clearVariantForm();
    }

    // Delete variant
    function deleteVariant(id) {
        if (!confirm('Are you sure you want to delete this variant?')) {
            return;
        }

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/delete/${id}`
            , method: 'DELETE'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadVariants();
                } else {
                    showAlert(response.message || 'Delete failed', 'danger');
                }
            }
            , error: function(xhr) {
                showAlert('Error deleting variant', 'danger');
            }
        });
    }

    // Clear variant form
    function clearVariantForm() {
        editingVariantId = null;
        skuManuallyEdited = false;
        barcodeManuallyEdited = false;
        $('input[name="variant_name"]').val('');
        $('input[name="variant_code"]').val('');
        $('input[name="variant_sku"]').val('');
        $('input[name="variant_barcode"]').val('');
        $('select[name="variant_unit"]').val('piece');
        $('input[name="variant_unit_value"]').val('');
        $('select[name="variant_is_default"]').val('0');
        $('select[name="variant_is_active"]').val('1');

        // Reset button
        $('#variantSubmitBtn').html('<i class="bi bi-plus-circle"></i> Add');
        $('#variantCancelBtn').hide();
    }

    // Show alert message
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' :
            type === 'danger' ? 'alert-danger' :
            type === 'warning' ? 'alert-warning' : 'alert-info';

        const alert = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // Remove existing alerts
        $('.alert').remove();

        // Add new alert at the top of the form
        $('.main-body').prepend(alert);

        // Auto dismiss after 3 seconds
        setTimeout(function() {
            $('.alert').fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }

    // ==================== PRICE MANAGEMENT FUNCTIONS ====================

    // Load all prices via AJAX
    function loadPrices() {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/prices`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    renderPrices(response.data);
                }
            }
            , error: function(xhr) {
                console.error('Error loading prices:', xhr);
                showAlert('Error loading prices', 'danger');
            }
        });
    }

    // Render prices in the table
    function renderPrices(prices) {
        const tbody = $('#priceTableBody');
        tbody.empty();

        if (prices.length === 0) {
            tbody.append('<tr><td colspan="9" class="text-center text-muted">No prices found. Add a price to get started.</td></tr>');
            return;
        }

        prices.forEach((price, index) => {
            const variant = price.product_variant || {};
            const row = `
                <tr class="price-row" data-id="${price.id}">
                    <td>${variant.name || '-'}</td>
                    <td>${variant.sku || '-'}</td>
                    <td>${price.cost_price ? '₹' + parseFloat(price.cost_price).toFixed(2) : '-'}</td>
                    <td>${price.compare_at_price ? '₹' + parseFloat(price.compare_at_price).toFixed(2) : '-'}</td>
                    <td>₹${parseFloat(price.price).toFixed(2)}</td>
                    <td>${price.effective_from ? new Date(price.effective_from).toLocaleDateString() : '-'}</td>
                    <td>${price.effective_to ? new Date(price.effective_to).toLocaleDateString() : '-'}</td>
                    <td>
                        <span class="badge ${price.is_active ? 'bg-success' : 'bg-danger'}">
                            ${price.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-primary me-1" onclick="editPrice(${price.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deletePrice(${price.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Add or Update price
    function addPrice() {
        const formData = {
            product_variant_id: $('#price_variant_id').val()
            , cost_price: $('#price_cost_price').val() || null
            , compare_at_price: $('#price_compare_at_price').val() || null
            , price: $('#price_selling_price').val()
            , effective_from: $('#price_effective_from').val() || null
            , effective_to: $('#price_effective_to').val() || null
            , is_active: $('#price_is_active').val() == '1' ? 1 : 0
        , };

        // Validation
        if (!formData.product_variant_id || !formData.price) {
            showAlert('Variant and Selling Price are required', 'warning');
            return;
        }

        const url = editingPriceId ?
            `/manage-product/product-master/product/${productId}/prices/update/${editingPriceId}` :
            `/manage-product/product-master/product/${productId}/prices/store`;

        const method = editingPriceId ? 'PUT' : 'POST';

        $.ajax({
            url: url
            , method: method
            , data: formData
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    clearPriceForm();
                    loadPrices();
                } else {
                    showAlert(response.message || 'Operation failed', 'danger');
                }
            }
            , error: function(xhr) {
                let errorMsg = 'Operation failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('<br>');
                }
                showAlert(errorMsg, 'danger');
            }
        });
    }

    // Edit price - populate form
    function editPrice(id) {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/prices`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    const price = response.data.find(p => p.id === id);
                    if (price) {
                        editingPriceId = price.id;
                        const variant = price.product_variant || {};

                        $('#price_variant_id').val(price.product_variant_id);
                        $('#price_item_no').val(variant.sku || '');
                        $('#price_cost_price').val(price.cost_price || '');
                        $('#price_compare_at_price').val(price.compare_at_price || '');
                        $('#price_selling_price').val(price.price);

                        // Format dates for input
                        if (price.effective_from) {
                            const fromDate = new Date(price.effective_from);
                            $('#price_effective_from').val(fromDate.toISOString().split('T')[0]);
                        } else {
                            $('#price_effective_from').val('');
                        }

                        if (price.effective_to) {
                            const toDate = new Date(price.effective_to);
                            $('#price_effective_to').val(toDate.toISOString().split('T')[0]);
                        } else {
                            $('#price_effective_to').val('');
                        }

                        $('#price_is_active').val(price.is_active ? '1' : '0');

                        // Update button text
                        $('#priceSubmitBtn').html('<i class="bi bi-check-circle"></i> Update');
                        $('#priceCancelBtn').show();

                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $('#priceInputRow').offset().top - 100
                        }, 500);
                    }
                }
            }
            , error: function(xhr) {
                showAlert('Error loading price data', 'danger');
            }
        });
    }

    // Cancel edit
    function cancelPriceEdit() {
        clearPriceForm();
    }

    // Delete price
    function deletePrice(id) {
        if (!confirm('Are you sure you want to delete this price?')) {
            return;
        }

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/prices/delete/${id}`
            , method: 'DELETE'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadPrices();
                } else {
                    showAlert(response.message || 'Delete failed', 'danger');
                }
            }
            , error: function(xhr) {
                showAlert('Error deleting price', 'danger');
            }
        });
    }

    // Clear price form
    function clearPriceForm() {
        editingPriceId = null;
        $('#price_variant_id').val('');
        $('#price_item_no').val('');
        $('#price_cost_price').val('');
        $('#price_compare_at_price').val('');
        $('#price_selling_price').val('');
        $('#price_effective_from').val('');
        $('#price_effective_to').val('');
        $('#price_is_active').val('1');

        // Reset button
        $('#priceSubmitBtn').html('<i class="bi bi-plus-circle"></i> Add');
        $('#priceCancelBtn').hide();
    }

</script>

<script>
    let editingStockId = null;

    $(document).ready(function() {
        loadStocks();
        loadDiscounts();

        $('#stock_variant_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const sku = selectedOption.data('sku') || '';
            $('#stock_item_no').val(sku);
        });
    });

    function loadDiscounts() {
        const tbody = $('#discountTableBody');
        tbody.html('<tr><td colspan="7" class="text-center text-muted">Loading discounts...</td></tr>');

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/discounts`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    renderDiscounts(response.data || []);
                } else {
                    tbody.html('<tr><td colspan="7" class="text-center text-muted">No discounts found.</td></tr>');
                }
            }
            , error: function() {
                tbody.html('<tr><td colspan="7" class="text-center text-muted">Failed to load discounts.</td></tr>');
            }
        });
    }

    function renderDiscounts(discounts) {
        const tbody = $('#discountTableBody');
        tbody.empty();

        if (!discounts.length) {
            tbody.append('<tr><td colspan="7" class="text-center text-muted">No discounts found.</td></tr>');
            return;
        }

        const today = new Date();

        discounts.forEach(discount => {
            const isPercentage = discount.discount_type === 'percentage';
            const discountTypeLabel = isPercentage ? 'Percentage (%)' : 'Fixed Amount';
            const discountValue = isPercentage ?
                `${parseFloat(discount.discount_value).toFixed(2)}%` :
                parseFloat(discount.discount_value).toFixed(2);

            const maxDiscount = discount.maximum_discount_amount ?
                parseFloat(discount.maximum_discount_amount).toFixed(2) :
                '-';

            const minOrderAmount = discount.minimum_order_amount ?
                parseFloat(discount.minimum_order_amount).toFixed(2) :
                '-';

            const validFrom = discount.valid_from ? new Date(discount.valid_from) : null;
            const validTo = discount.valid_to ? new Date(discount.valid_to) : null;
            const dateOptions = {
                year: 'numeric'
                , month: 'short'
                , day: '2-digit'
            };
            const startDate = validFrom ? validFrom.toLocaleDateString('en-IN', dateOptions) : '-';
            const endDate = validTo ? validTo.toLocaleDateString('en-IN', dateOptions) : '-';
            const isActive = !!discount.is_active &&
                (!validFrom || validFrom <= today) &&
                (!validTo || validTo >= today);

            const statusBadge = isActive ?
                '<span class="badge bg-soft-success text-success">Active</span>' :
                '<span class="badge bg-soft-danger text-danger">Inactive</span>';

            const row = `
                <tr>
                    <td>${discountTypeLabel}</td>
                    <td>${discountValue}</td>
                    <td>${startDate}</td>
                    <td>${endDate}</td>
                    <td>${minOrderAmount}</td>
                    <td>${maxDiscount}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function showInStockModal(variantId, variantName) {
        showStockBreakdownModal(variantId, variantName, 'in_stock', 'In Stock');
    }

    function showAvailableModal(variantId, variantName) {
        showStockBreakdownModal(variantId, variantName, 'available', 'Available');
    }

    function showInTransitModal(variantId, variantName) {
        showStockBreakdownModal(variantId, variantName, 'in_transit', 'In Transit');
    }

    function showStockBreakdownModal(variantId, variantName, type, label) {
        $('#stockBreakdownModalLabel').text(`${variantName} - ${label} by Store`);
        const tbody = $('#stockBreakdownBody');
        tbody.empty();
        tbody.append('<tr><td colspan="2" class="text-center text-muted">Loading...</td></tr>');

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/variants/${variantId}/stocks?type=${type}`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    renderStockBreakdown(response.data);
                } else {
                    tbody.html('<tr><td colspan="2" class="text-center text-muted">No stock records found.</td></tr>');
                }
            }
            , error: function() {
                tbody.html('<tr><td colspan="2" class="text-center text-muted">Failed to load stock records.</td></tr>');
            }
        });

        const modal = new bootstrap.Modal(document.getElementById('stockBreakdownModal'));
        modal.show();
    }

    function renderStockBreakdown(rows) {
        const tbody = $('#stockBreakdownBody');
        tbody.empty();

        if (!rows.length) {
            tbody.append('<tr><td colspan="2" class="text-center text-muted">No stock records found.</td></tr>');
            return;
        }

        rows.forEach(row => {
            const tr = `
                <tr>
                    <td>${row.store_name || '-'}</td>
                    <td class="text-end">${row.quantity ?? 0}</td>
                </tr>
            `;
            tbody.append(tr);
        });
    }

    function loadStocks() {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/stocks`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    renderStocks(response.data);
                }
            }
            , error: function(xhr) {
                console.error('Error loading stocks:', xhr);
                showAlert('Error loading stocks', 'danger');
            }
        });
    }

    function renderStocks(stocks) {
        const tbody = $('#manageStockTableBody');
        tbody.empty();

        if (!stocks.length) {
            tbody.append('<tr><td colspan="10" class="text-center text-muted">No stock records found. Add stock to get started.</td></tr>');
            return;
        }

        stocks.forEach((stock) => {
            const statusBadge = stock.stock_status === 'in_stock' ?
                'bg-success' :
                stock.stock_status === 'low_stock' ?
                'bg-warning' :
                stock.stock_status === 'backorder' ?
                'bg-info' :
                'bg-danger';

            const createdAt = stock.created_at ?
                new Date(stock.created_at).toLocaleDateString('en-IN', {
                    year: 'numeric'
                    , month: 'short'
                    , day: '2-digit'
                }) :
                '-';
            const createdBy = stock.last_restocked_by_user ? .name || '-';
            const row = `
                <tr data-id="${stock.id}">
                    <td>${stock.store?.name || '-'}</td>
                    <td>${stock.product_variant?.name || '-'}</td>
                    <td>${stock.product_variant?.sku || '-'}</td>
                    <td>${stock.quantity ?? 0}</td>
                    <td>${stock.min_stock_level ?? 0}</td>
                    <td>${stock.max_stock_level ?? '-'}</td>
                    <td><span class="badge ${statusBadge}">${(stock.stock_status || '-').replace('_', ' ')}</span></td>
                    <td>${createdBy}</td>
                    <td>${createdAt}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-primary me-1" onclick="editStock(${stock.id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteStock(${stock.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function addStock() {
        const formData = {
            store_id: $('#stock_store_id').val()
            , product_variant_id: $('#stock_variant_id').val()
            , quantity: $('#stock_quantity').val()
            , min_stock_level: $('#stock_min').val() || 0
            , max_stock_level: $('#stock_max').val() || null
            , stock_status: $('#stock_status').val()
        };

        if (!formData.store_id || !formData.product_variant_id || formData.quantity === '') {
            showAlert('Store, Variant, and Quantity are required', 'warning');
            return;
        }

        const url = editingStockId ?
            `/manage-product/product-master/product/${productId}/stocks/update/${editingStockId}` :
            `/manage-product/product-master/product/${productId}/stocks/store`;

        const method = editingStockId ? 'PUT' : 'POST';

        $.ajax({
            url: url
            , method: method
            , data: formData
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    clearStockForm();
                    loadStocks();
                } else {
                    showAlert(response.message || 'Operation failed', 'danger');
                }
            }
            , error: function(xhr) {
                let errorMsg = 'Operation failed';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMsg = errors.join('<br>');
                }
                showAlert(errorMsg, 'danger');
            }
        });
    }

    function editStock(id) {
        $.ajax({
            url: `/manage-product/product-master/product/${productId}/stocks`
            , method: 'GET'
            , success: function(response) {
                if (response.success) {
                    const stock = response.data.find(s => s.id === id);
                    if (stock) {
                        editingStockId = stock.id;
                        $('#stock_store_id').val(stock.store_id);
                        $('#stock_variant_id').val(stock.product_variant_id);
                        $('#stock_item_no').val(stock.product_variant ? .sku || '');
                        $('#stock_quantity').val(stock.quantity ? ? 0);
                        $('#stock_min').val(stock.min_stock_level ? ? 0);
                        $('#stock_max').val(stock.max_stock_level ? ? '');
                        $('#stock_status').val(stock.stock_status || 'in_stock');

                        $('#stockSubmitBtn').html('<i class="bi bi-check-circle"></i> Update');
                        $('#stockCancelBtn').show();

                        $('html, body').animate({
                            scrollTop: $('#stockInputRow').offset().top - 100
                        }, 500);
                    }
                }
            }
            , error: function() {
                showAlert('Error loading stock data', 'danger');
            }
        });
    }

    function cancelStockEdit() {
        clearStockForm();
    }

    function deleteStock(id) {
        if (!confirm('Are you sure you want to delete this stock record?')) {
            return;
        }

        $.ajax({
            url: `/manage-product/product-master/product/${productId}/stocks/delete/${id}`
            , method: 'DELETE'
            , headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            , success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    loadStocks();
                } else {
                    showAlert(response.message || 'Delete failed', 'danger');
                }
            }
            , error: function() {
                showAlert('Error deleting stock', 'danger');
            }
        });
    }

    function clearStockForm() {
        editingStockId = null;
        $('#stock_store_id').val('');
        $('#stock_variant_id').val('');
        $('#stock_item_no').val('');
        $('#stock_quantity').val('');
        $('#stock_min').val('');
        $('#stock_max').val('');
        $('#stock_status').val('in_stock');

        $('#stockSubmitBtn').html('<i class="bi bi-plus-circle"></i> Add');
        $('#stockCancelBtn').hide();
    }

</script>

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
