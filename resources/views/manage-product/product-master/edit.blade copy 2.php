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
                                            <option value="{{ $type->id }}" {{ old('item_type', $product->item_type) == $type->id ? 'selected' : '' }}>
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
                                    <label class="col-md-5 form-label">Item Sub Cat</label>
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
                                            <option value="{{ $collection->id }}" {{ old('collection', $product->collection) == $collection->id ? 'selected' : '' }}>
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
                                            @foreach($seasons as $season)
                                            <option value="{{ $season->id }}" {{ old('season', $product->season) == $season->id ? 'selected' : '' }}>
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
                                        <th>SKU</th>
                                        <th>Variant Type</th>
                                        <th>Variant Value</th>
                                        <th>Barcode</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th class="text-end d-flex gap-2">Action
                                            <button type="button" class="btn btn-sm btn-success" style="font-size: 12px;padding: 0.15rem 0.3rem;" onclick="addVariantRow()">Add</button>
                                        </th>
                                    </tr>
                                </thead>

                                <!-- ================= BODY ================= -->
                                <tbody id="variantTableBody">

                                    <!-- ===== Existing Backend Variants ===== -->
                                    @foreach($product->variants as $i => $variant)
                                    <tr class="variant-row" data-index="{{ $i }}">
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->sku ?? '-' }}</td>
                                        <td>{{ $variant->variant_type }}</td>
                                        <td>{{ $variant->variant_value }}</td>
                                        <td>{{ $variant->barcode }}
                                            <i class="bi bi-upc text-primary ms-2" onclick="showBarcodeModal('{{ $variant->barcode }}')" title="Show Barcode" style="cursor: pointer;font-size: 18px;"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                @foreach($variant->images as $img)
                                                <div class="position-relative">
                                                    <img src="{{ asset($img->image_path) }}" class="border rounded" width="50" height="50">
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $variant->is_active?'bg-soft-success text-success':'bg-soft-danger text-danger' }}">
                                                {{ $variant->is_active?'Active':'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <i class="bi bi-pencil text-primary me-2" onclick="editVariantRow(this)"></i>
                                            <i class="bi bi-trash text-danger" onclick="removeVariantRow(this)"></i>
                                        </td>
                                    </tr>
                                    @endforeach

                                    
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
                            <table class="table table-bordered table-sm" id="stockTable">
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
                                        <td><span style="cursor: pointer; color: #0d6efd;" onclick="showStoreAvailabilityModal({{ $variant->id }}, '{{ $variant->name }}', 'in_stock', {{ $variant->quantity ?? 0 }})">{{ $variant->quantity ?? 0 }}</span></td>
                                        <td><span style="cursor: pointer; color: #0d6efd;" onclick="showStoreAvailabilityModal({{ $variant->id }}, '{{ $variant->name }}', 'available', {{ $variant->available ?? 0 }})">{{ $variant->available ?? 0 }}</span></td>
                                        <td><span style="cursor: pointer; color: #0d6efd;" onclick="showStoreAvailabilityModal({{ $variant->id }}, '{{ $variant->name }}', 'in_transit', {{ $variant->in_transit ?? 0 }})">{{ $variant->in_transit ?? 0 }}</span></td>
                                    </tr>
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
                                        <th>Initial Stock <span class="text-danger">*</span></th>
                                        <th>Minimum Stock</th>
                                        <th>Maximum Stock</th>
                                        <th>Stock Alert</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <select name="product_variant_id" class="form-control" required>
                                                <option value="">Select Variant</option>
                                                @foreach($product->variants as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <input type="text" name="item_no" class="form-control" step="0.01" disabled>
                                        </th>
                                        <th>
                                            <input type="number" name="stock_quantity" class="form-control" step="0.01" required>
                                        </th>
                                        <th>
                                            <input type="number" name="min_stock" class="form-control" step="0.01">
                                        </th>
                                        <th>
                                            <input type="number" name="max_stock" class="form-control" step="0.01">
                                        </th>
                                        <th>
                                            <input type="checkbox" name="stock_alert" value="1">
                                        </th>
                                        <th class="text-end">
                                            <button type="button" class="btn btn-xs btn-primary" onclick="editStockRow(this)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="stockTableBody">

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
                            <table class="table table-bordered align-middle table-sm" id="priceTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Variant Name</th>
                                        <th>Item No.</th>
                                        <th>Cost Price</th>
                                        <th>MRP</th>
                                        <th>Selling Price <span class="text-danger">*</span></th>
                                        <th>Effective From</th>
                                        <th>Effective To</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                    <tr>
                                        <th>
                                            <select name="product_variant_id" class="form-control" required>
                                                <option value="">Select Variant</option>
                                                @foreach($product->variants as $v)
                                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <input type="text" name="item_no" class="form-control" step="0.01" disabled>
                                        </th>
                                        <th>
                                            <input type="number" name="cost_price" class="form-control cost-price" step="0.01" required>
                                        </th>
                                        <th>
                                            <input type="number" name="cost_price" class="form-control cost-price" step="0.01" required>
                                        </th>
                                        <th>
                                            <input type="number" name="compare_at_price" class="form-control" step="0.01">
                                        </th>
                                        <th>
                                            <input type="date" name="effective_from" class="form-control" required>
                                        </th>
                                        <th>
                                            <input type="date" name="effective_to" class="form-control" required>
                                        </th>
                                        <th class="text-end">
                                            <button type="button" class="btn btn-xs btn-primary" onclick="editPriceRow(this)">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="priceTableBody">
                                    @foreach($product->variants as $variant)
                                    <tr>
                                        <td>{{ $variant->name }}</td>
                                        <td>{{ $variant->sku }}</td>
                                        <td>{{ $variant->cost_price }}</td>
                                    </tr>
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
                            <table class="table table-bordered table-sm" id="discountTable">
                                <thead>
                                    <tr>
                                        <th>Store/Location</th>
                                        <th>Discount Type</th>
                                        <th>Discount Value</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Min Quantity</th>
                                        <th>Max Discount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="discountTableBody">
                                    <tr>
                                        <td>Main Store</td>
                                        <td>Percentage (%)</td>
                                        <td>10.00</td>
                                        <td>2024-01-01</td>
                                        <td>2024-12-31</td>
                                        <td>2</td>
                                        <td>2000.00</td>
                                        <td>Active</td>
                                    </tr>
                                    <tr>
                                        <td>Store 1</td>
                                        <td>Fixed Amount</td>
                                        <td>1500.00</td>
                                        <td>2024-02-01</td>
                                        <td>2024-11-30</td>
                                        <td>3</td>
                                        <td>3000.00</td>
                                        <td>Active</td>
                                    </tr>
                                    <tr>
                                        <td>Store 2</td>
                                        <td>Percentage (%)</td>
                                        <td>15.00</td>
                                        <td>2024-03-01</td>
                                        <td>2024-10-31</td>
                                        <td>1</td>
                                        <td>2500.00</td>
                                        <td>Active</td>
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

@endsection
@section('scripts')
<script>
    // Stock Table Functions
    let stockRowCount = 1;

    function addStockRow() {

        const tbody = document.getElementById('stockTableBody');

        const row = `
        <tr>
            <td>
                <select name="stock[${stockRowCount}][store_id]"
                        class="form-control"
                        required>
                    <option value="">Select Store</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}">
                            {{ $store->name }} ({{ $store->code }})
                        </option>
                    @endforeach
                </select>
            </td>

            <td>
                 <select name="stock[${stockRowCount}][product_variant]" class="form-control stock-variant">
                    <option value="">Select Variant</option>
                    @foreach($product->variants as $v)
                        <option value="{{ $v->name }}">{{ $v->name }}</option>
                    @endforeach
                </select>
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][stock_quantity]"
                       class="form-control"
                       value="0" required>
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][min_stock]"
                       class="form-control"
                       value="0">
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][max_stock]"
                       class="form-control"
                       value="0">
            </td>

            <td>
                
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="stock[${stockRowCount}][stock_alert]" value="1" checked>
                    </div>                                                                
            </td>

            <td class="text-end">
                <button type="button"
                        class="btn btn-sm btn-danger"
                        onclick="removeStockRow(this)">
                    <i class="feather-trash-2"></i>
                </button>
            </td>
        </tr>
    `;

        tbody.insertAdjacentHTML('beforeend', row);

        // 🔥 THIS IS REQUIRED
        refreshVariantDropdowns();

        stockRowCount++;
    }


    function removeStockRow(button) {
        const tbody = document.getElementById('stockTableBody');
        if (tbody.children.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('At least one row is required');
        }
    }

    // Price Table Functions
    let priceRowCount = 1; // Start from 3 as we have 3 dummy rows

    function addPriceRow() {
        const tbody = document.getElementById('priceTableBody');
        const row = `
            <tr>
        <td>
            <select name="price[${priceRowCount}][store_id]"
                    class="form-control"
                    required>
                <option value="">Select Store</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">
                        {{ $store->name }} ({{ $store->code }})
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <select name="price[${priceRowCount}][product_variant_id]"
                    class="form-control price-variant"
                    required>
                <option value="">Select Variant</option>
            </select>
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][cost_price]"
                   class="form-control cost-price"
                   step="0.01">
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][price]"
                   class="form-control selling-price"
                   step="0.01" required>
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][compare_at_price]"
                   class="form-control"
                   step="0.01">
        </td>

        <td>
            <input type="text"
                   name="price[${priceRowCount}][margin]"
                   class="form-control margin"
                   readonly>
        </td>

        <td class="text-end">
            <button type="button"
                    class="btn btn-sm btn-danger"
                    onclick="removePriceRow(this)">
                <i class="feather-trash-2"></i>
            </button>
        </td>
    </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);

        // 🔥 THIS IS REQUIRED
        refreshVariantDropdowns();
        priceRowCount++;
    }

    function removePriceRow(button) {
        const tbody = document.getElementById('priceTableBody');
        if (tbody.children.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('At least one row is required');
        }
    }

    // Discount Table Functions
    let discountRowCount = 3; // Start from 3 as we have 3 dummy rows
    function addDiscountRow() {
        const tbody = document.getElementById('discountTableBody');
        const row = `
            <tr>
                <td>
                    <select name="discount[${discountRowCount}][store_location]" class="form-control" required>
                        <option value="">Select Store</option>
                        <option value="1">Main Store</option>
                        <option value="2">Store 1</option>
                        <option value="3">Store 2</option>
                        <option value="4">Store 3</option>
                    </select>
                </td>
                <td>
                                                               
                                                            </td>
                <td>
                    <select name="discount[${discountRowCount}][discount_type]" class="form-control">
                        <option value="">No Discount</option>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][discount_value]" class="form-control" placeholder="Discount value" step="0.01" value="0">
                </td>
                <td>
                    <input type="date" name="discount[${discountRowCount}][discount_start_date]" class="form-control">
                </td>
                <td>
                    <input type="date" name="discount[${discountRowCount}][discount_end_date]" class="form-control">
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][discount_min_qty]" class="form-control" placeholder="Min qty" value="1">
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][max_discount]" class="form-control" placeholder="Max discount" step="0.01">
                </td>
                <td>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="discount[${discountRowCount}][apply_discount]" value="1">
                    </div>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeDiscountRow(this)">
                        <i class="feather-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', row);
        discountRowCount++;
    }

    function removeDiscountRow(button) {
        const tbody = document.getElementById('discountTableBody');
        if (tbody.children.length > 1) {
            button.closest('tr').remove();
        } else {
            alert('At least one row is required');
        }
    }

    /* ================= ADD VARIANT (TEMP ONLY) ================= */
    function addVariantRow() {
        const row = document.getElementById('newVariantRow');

        const name = row.querySelector('[name="new_variant[name]"]').value.trim();
        const sku = row.querySelector('[name="new_variant[sku]"]').value.trim();
        const variantType = row.querySelector('[name="new_variant[variant_type]"]').value;
        const variantValue = row.querySelector('[name="new_variant[variant_value]"]').value.trim();
        const barcode = row.querySelector('[name="new_variant[barcode]"]').value.trim();
        const status = row.querySelector('[name="new_variant[is_active]"]').value;

        if (!name || !barcode) {
            alert('Variant Name & Barcode are required');
            return;
        }

        const tbody = document.getElementById('variantTableBody');
        const index = tbody.querySelectorAll('tr.variant-row').length;

        const newRow = document.createElement('tr');
        newRow.classList.add('variant-row');
        newRow.setAttribute('data-index', index);

        newRow.innerHTML = `
        <td><input type="checkbox" name="variants[${index}][selected]"></td>
        <td>${name}<input type="hidden" name="variants[${index}][name]" value="${name}" class="variant-name"></td>
        <td>${sku || '-'}<input type="hidden" name="variants[${index}][sku]" value="${sku}"></td>
        <td>
            <select name="variants[${index}][variant_type]" class="form-select form-select-sm">
                <option value="weight" ${variantType=='weight'?'selected':''}>weight</option>
                <option value="quantity" ${variantType=='quantity'?'selected':''}>quantity</option>
                <option value="packaging" ${variantType=='packaging'?'selected':''}>packaging</option>
                <option value="size" ${variantType=='size'?'selected':''}>size</option>
                <option value="unit" ${variantType=='unit'?'selected':''}>unit</option>
            </select>
        </td>
        <td>${variantValue || '-'}<input type="hidden" name="variants[${index}][variant_value]" value="${variantValue}"></td>
        <td>${barcode}<input type="hidden" name="variants[${index}][barcode]" value="${barcode}"></td>
        <td><input type="file" name="variants[${index}][image]" class="form-control"></td>
        <td>
            <span class="badge ${status==1?'bg-soft-success text-success':'bg-soft-danger text-danger'}">
                ${status==1?'Active':'Inactive'}
            </span>
            <input type="hidden" name="variants[${index}][is_active]" value="${status}">
        </td>
        <td class="text-end">
            <i class="feather-edit text-primary me-2" onclick="editVariantRow(this)"></i>
            <i class="feather-trash-2 text-danger" onclick="removeVariantRow(this)"></i>
        </td>
    `;

        tbody.insertBefore(newRow, row);

        // Reset template row
        row.querySelectorAll('input').forEach(el => el.value = '');
        row.querySelector('[name="new_variant[variant_type]"]').value = '';
        row.querySelector('[name="new_variant[is_active]"]').value = '1';

        // Refresh stock and price dropdowns
        refreshVariantDropdowns();
    }

    /* ================= DELETE ================= */
    function removeVariantRow(el) {
        if (!confirm('Are you sure to delete this variant?')) return;
        const tr = el.closest('tr');
        tr.remove();
        refreshVariantDropdowns();
    }

    /* ================= EDIT ================= */

    function editVariantRow(el) {
        const tr = el.closest('tr');
        const index = tr.getAttribute('data-index');

        const nameInput = tr.querySelector(`[name="variants[${index}][name]"]`);
        const skuInput = tr.querySelector(`[name="variants[${index}][sku]"]`);
        const typeInput = tr.querySelector(`[name="variants[${index}][variant_type]"]`);
        const valueInput = tr.querySelector(`[name="variants[${index}][variant_value]"]`);
        const barcodeInput = tr.querySelector(`[name="variants[${index}][barcode]"]`);
        const statusInput = tr.querySelector(`[name="variants[${index}][is_active]"]`);

        tr.cells[1].innerHTML = `<input type="text" name="variants[${index}][name]" value="${nameInput.value}" class="form-control">`;
        tr.cells[2].innerHTML = `<input type="text" name="variants[${index}][sku]" value="${skuInput.value}" class="form-control">`;
        tr.cells[3].innerHTML = `<input type="text" name="variants[${index}][variant_type]" value="${typeInput.value}" class="form-control">`;
        tr.cells[4].innerHTML = `<input type="text" name="variants[${index}][variant_value]" value="${valueInput.value}" class="form-control">`;
        tr.cells[5].innerHTML = `<input type="text" name="variants[${index}][barcode]" value="${barcodeInput.value}" class="form-control">`;
        tr.cells[7].innerHTML = `
        <select name="variants[${index}][is_active]" class="form-select">
            <option value="1" ${statusInput.value == 1 ? 'selected' : ''}>Active</option>
            <option value="0" ${statusInput.value == 0 ? 'selected' : ''}>Inactive</option>
        </select>
    `;
    }


    /* ================= REBUILD INDEXES ================= */
    function rebuildVariantIndexes() {
        const rows = document.querySelectorAll('#variantTableBody tr:not(.bg-light)');

        rows.forEach((row, index) => {
            row.querySelectorAll('input[type="hidden"]').forEach(i => i.remove());

            const values = row.children;

            row.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="variants[${index}][sub_item_name]" value="${values[1].textContent}">
            <input type="hidden" name="variants[${index}][sub_item_code]" value="${values[2].textContent}">
            <input type="hidden" name="variants[${index}][color]" value="${values[3].textContent}">
            <input type="hidden" name="variants[${index}][color_code]" value="${values[4].textContent}">
            <input type="hidden" name="variants[${index}][size]" value="${values[5].textContent}">
            <input type="hidden" name="variants[${index}][size_code]" value="${values[6].textContent}">
            <input type="hidden" name="variants[${index}][variant_code]" value="${values[7].querySelector('span').textContent}">
            <input type="hidden" name="variants[${index}][status]" value="${values[9].innerText.trim() === 'Active' ? 'active' : 'inactive'}">
        `);
        });
    }

    /* ================= HELPERS ================= */
    function resetVariantForm() {
        document.querySelectorAll('#variantTableBody .bg-light input, #variantTableBody .bg-light select')
            .forEach(el => el.value = '');
        newPictureName.textContent = 'No file chosen';
    }

    function toggleSelectAllVariants(cb) {
        document.querySelectorAll('.variant-checkbox').forEach(c => c.checked = cb.checked);
    }

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // console.log('JS READY');

        $(document).on('change', '#category_id', function() {

            let categoryId = this.value;
            let $sub = $('#sub_category_id');

            // console.log('Category Selected:', categoryId);

            $sub.empty().append('<option value="">Loading...</option>');

            if (!categoryId) {
                $sub.html('<option value="">Select Sub Category</option>');
                return;
            }

            $.ajax({
                url: "{{ url('/sub-categories/by-category') }}/" + categoryId
                , type: "GET"
                , dataType: "json"
                , success: function(data) {

                    // console.log('AJAX DATA:', data);

                    $sub.empty().append('<option value="">Select Sub Category</option>');

                    $.each(data, function(index, item) {
                        $sub.append(
                            $('<option>', {
                                value: item.id
                                , text: item.name
                            })
                        );
                    });
                }
                , error: function(xhr) {
                    // console.error('AJAX ERROR', xhr.responseText);
                }
            });
        });

    });

    $(document).ready(function() {
        $('#category_id').select2({
            theme: 'bootstrap-5'
            , width: '100%'
            , placeholder: 'Select Category'
            , allowClear: true
        });
    });



    document.querySelectorAll('.item-type').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.querySelectorAll('.item-type').forEach(function(cb) {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        });
    });

    let images = [];

    document.getElementById('item_image').addEventListener('change', function(e) {
        const files = Array.from(e.target.files);

        files.forEach(file => images.push(file));

        renderImages();
    });

    function renderImages() {
        const container = document.querySelector('.image-container');
        container.innerHTML = '';

        images.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col';

                col.innerHTML = `
                <img src="${e.target.result}" class="img-fluid w-100">
                <span>
                    <a href="javascript:void(0);" onclick="removeImage(${index})">
                        <i class="feather-trash-2"></i>
                    </a>
                </span>
            `;

                container.appendChild(col);
            };

            reader.readAsDataURL(file);
        });

        syncInputFiles();
    }

    function removeImage(index) {
        images.splice(index, 1);
        renderImages();
    }

    function syncInputFiles() {
        const dataTransfer = new DataTransfer();
        images.forEach(img => dataTransfer.items.add(img));
        document.getElementById('item_image').files = dataTransfer.files;
    }

</script>

<script>
    function refreshVariantDropdowns() {
        let variants = [];
        document.querySelectorAll('.variant-name').forEach(el => {
            if (el.value.trim() !== '') variants.push(el.value.trim());
        });

        document.querySelectorAll('.stock-variant,.price-variant').forEach(select => {
            let selected = select.value;
            select.innerHTML = '<option value="">Select Variant</option>';
            variants.forEach(name => {
                let opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                select.appendChild(opt);
            });
            select.value = selected;
        });
    }

</script>


<script>
    document.getElementById('managestock-tab,manageprice-tab')
        .addEventListener('shown.bs.tab', function() {
            refreshVariantDropdowns();
        });

</script>

<script>
    console.log('🔥 Margin script loaded');

    document.addEventListener('input', function(e) {

        if (
            !e.target.classList.contains('cost-price') &&
            !e.target.classList.contains('selling-price')
        ) {
            return;
        }

        const row = e.target.closest('tr');
        if (!row) return;

        const costInput = row.querySelector('.cost-price');
        const sellingInput = row.querySelector('.selling-price');
        const marginInput = row.querySelector('.margin');

        if (!costInput || !sellingInput || !marginInput) return;

        const cost = parseFloat(costInput.value);
        const selling = parseFloat(sellingInput.value);

        if (!isNaN(cost) && !isNaN(selling) && cost !== 0) {
            const margin = ((selling - cost) / cost) * 100;
            marginInput.value = margin.toFixed(2) + '%';
        } else {
            marginInput.value = '';
        }
    });

    function regenerateBarcode() {
        const barcodeField = document.getElementById('barcode_no');
        let barcodeValue = barcodeField.value;

        // If empty, autofill with random barcode (like create page)
        if (!barcodeValue) {
            barcodeValue = 'PRD-' + Math.floor(Math.random() * 1000000); // example
            barcodeField.value = barcodeValue;
        }

        // Show modal with barcode preview
        showBarcodeModal(barcodeValue);
    }

    function showBarcodeModal(barcode) {
        let modal = document.getElementById('barcodeModal');

        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'barcodeModal';
            modal.classList.add('modal', 'fade');
            modal.tabIndex = -1;
            modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Barcode Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="barcodePreview"></div>
                   
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-sm btn-primary" onclick="printBarcode()">
                                                <i class="feather-printer"></i> Print
                                            </button>

                                            <button class="btn btn-sm btn-success" onclick="downloadBarcode()">
                                                <i class="feather-download"></i> Download
                                            </button>
                </div>
            </div>
        </div>`;
            document.body.appendChild(modal);
        }

        // Generate barcode using JsBarcode
        const previewDiv = modal.querySelector('#barcodePreview');
        previewDiv.innerHTML = '';
        const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        previewDiv.appendChild(svg);
        JsBarcode(svg, barcode, {
            format: "CODE128"
            , width: 2
            , height: 50
        });

        // Show modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    }

    /* PRINT BARCODE */
    function printBarcode() {
        const content = document.getElementById('barcodePreview').outerHTML;

        const win = window.open('', '', 'width=400,height=300');
        win.document.write(`
        <html>
            <body style="text-align:center;">
                ${content}
            </body>
        </html>
    `);
        win.document.close();
        win.print();
    }

    /* DOWNLOAD BARCODE */
    function downloadBarcode() {
        // Get the actual SVG element inside #barcodePreview
        const svg = document.getElementById('barcodePreview').querySelector('svg');
        if (!svg) {
            alert('No barcode found to download.');
            return;
        }

        // Serialize SVG to string
        const svgData = new XMLSerializer().serializeToString(svg);

        // Create canvas
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Create image from SVG
        const img = new Image();
        img.onload = function() {
            // Set canvas size to image size
            canvas.width = img.width;
            canvas.height = img.height;

            // Draw the image on canvas
            ctx.drawImage(img, 0, 0);

            // Trigger download
            const link = document.createElement('a');
            link.download = 'barcode.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        };

        // Encode SVG as base64 and set as image source
        const svgBlob = new Blob([svgData], {
            type: 'image/svg+xml;charset=utf-8'
        });
        const url = URL.createObjectURL(svgBlob);
        img.src = url;
    }

    /* SHOW STORE AVAILABILITY MODAL */
    function showStoreAvailabilityModal(variantId, variantName, stockType, totalQuantity) {
        let modal = document.getElementById('storeAvailabilityModal');

        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'storeAvailabilityModal';
            modal.classList.add('modal', 'fade');
            modal.tabIndex = -1;
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Store Availability</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Variant:</strong> <span id="modalVariantName"></span><br>
                                <strong>Stock Type:</strong> <span id="modalStockType"></span><br>
                                <strong>Total Quantity:</strong> <span id="modalTotalQuantity"></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Store Name</th>
                                            <th>Store Code</th>
                                            <th>Location</th>
                                            <th class="text-end">Quantity</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="storeAvailabilityTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(modal);
        }

        // Set modal content
        document.getElementById('modalVariantName').textContent = variantName;
        document.getElementById('modalStockType').textContent = stockType.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        document.getElementById('modalTotalQuantity').textContent = totalQuantity;

        // Static store data
        const staticStoreData = [{
                id: 1
                , name: 'Main Store'
                , code: 'ST001'
                , location: 'Mumbai, Maharashtra'
                , quantity: Math.floor(totalQuantity * 0.4)
                , status: 'Available'
            }
            , {
                id: 2
                , name: 'Branch Store 1'
                , code: 'ST002'
                , location: 'Delhi, NCR'
                , quantity: Math.floor(totalQuantity * 0.3)
                , status: 'Available'
            }
            , {
                id: 3
                , name: 'Branch Store 2'
                , code: 'ST003'
                , location: 'Bangalore, Karnataka'
                , quantity: Math.floor(totalQuantity * 0.2)
                , status: 'Available'
            }
            , {
                id: 4
                , name: 'Warehouse'
                , code: 'ST004'
                , location: 'Pune, Maharashtra'
                , quantity: Math.floor(totalQuantity * 0.1)
                , status: 'Available'
            }
        ];

        // Populate table
        const tableBody = document.getElementById('storeAvailabilityTableBody');
        tableBody.innerHTML = '';

        if (staticStoreData.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No stores available</td></tr>';
        } else {
            staticStoreData.forEach(store => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${store.name}</td>
                    <td>${store.code}</td>
                    <td>${store.location}</td>
                    <td class="text-end">${store.quantity}</td>
                    <td><span class="badge bg-success">${store.status}</span></td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Show modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
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
