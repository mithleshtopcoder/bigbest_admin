@extends('layouts.app')

@section('title', 'Create Stock Adjustment')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Stock Adjustment</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory-management.stock-adjustment') }}" class="text-decoration-none">Stock Adjustment</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Stock Adjustment Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('inventory-management.stock-adjustment.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Store <span class="text-danger">*</span></label>
                        <select class="form-select" name="store_id" id="store_id" required>
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="type" required>
                            <option value="">Select Type</option>
                            <option value="addition">Addition (Stock In)</option>
                            <option value="reduction">Reduction (Stock Out)</option>
                            <option value="correction">Correction</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Adjustment Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="adjustment_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <select class="form-select" name="reason" required>
                            <option value="">Select Reason</option>
                            <option value="damaged">Damaged</option>
                            <option value="expired">Expired</option>
                            <option value="lost">Lost</option>
                            <option value="stolen">Stolen</option>
                            <option value="found">Found</option>
                            <option value="returned">Returned</option>
                            <option value="count_error">Count Error</option>
                            <option value="physical_count">Physical Count</option>
                            <option value="vendor_return">Vendor Return</option>
                            <option value="promotion">Promotion</option>
                            <option value="opening_stock">Opening Stock</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reason Description</label>
                        <input type="text" class="form-control" name="reason_description">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>

                <hr>
                <h6 class="mb-3">Items</h6>
                <div id="itemsContainer">
                    <div class="item-row mb-3 border p-3 rounded">
                        <div class="row">
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Product <span class="text-danger">*</span></label>
                                <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." autocomplete="off">
                                <input type="hidden" class="product-variant-id" name="items[0][product_variant_id]" required>
                                <div class="product-search-results" style="display:none; position:absolute; top:100%; left:0; z-index:1000; background:white; border:1px solid #ddd; max-height:200px; overflow-y:auto;"></div>
                                <div class="selected-product-info mt-2" style="display:none;">
                                    <small class="text-muted">Selected: <span class="selected-product-name"></span> | Current Stock: <span class="current-stock">0</span></small>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Adjusted Qty <span class="text-danger">*</span></label>
                                <input type="number" class="form-control adjusted-qty-input" name="items[0][adjusted_quantity]" required>
                                <small class="text-muted">+ for addition, - for reduction</small>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit Cost</label>
                                <input type="number" step="0.01" class="form-control" name="items[0][unit_cost]">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Remark</label>
                                <input type="text" class="form-control" name="items[0][remark]">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-danger remove-item" style="display:none;">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="addItem" class="btn btn-sm btn-secondary mb-3">Add Item</button>

                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Adjustment</button>
                    <a href="{{ route('inventory-management.stock-adjustment') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
let itemIndex = 1;
let searchTimeout;

    // Product search functionality
    $(document).on('input', '.product-search', function() {
        const $input = $(this);
        const $row = $input.closest('.item-row');
        const $results = $row.find('.product-search-results');
        const $hidden = $row.find('.product-variant-id');
        const $info = $row.find('.selected-product-info');
        const search = $input.val();
        const storeId = $('#store_id').val();

        clearTimeout(searchTimeout);

        if (search.length < 2) {
            $results.hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("inventory-management.search-variants") }}'
                , data: {
                    search: search
                    , store_id: storeId
                }
                , success: function(response) {
                    if (response.variants && response.variants.length > 0) {
                        let html = '';
                        response.variants.forEach(function(variant) {
                            html += '<div class="p-2 border-bottom product-option" style="cursor:pointer;" data-id="' + variant.id + '" data-name="' + variant.name + '" data-stock="' + variant.current_stock + '">';
                            html += '<strong>' + variant.product_name + '</strong> - ' + variant.name;
                            html += '<br><small class="text-muted">SKU: ' + variant.sku + ' | Stock: ' + variant.current_stock + '</small>';
                            html += '</div>';
                        });
                        $results.html(html).show();
                    } else {
                        $results.html('<div class="p-2 text-muted">No products found</div>').show();
                    }
                }
            });
        }, 300);
    });

    // Select product
    $(document).on('click', '.product-option', function() {
        const $option = $(this);
        const $row = $option.closest('.item-row');
        const $input = $row.find('.product-search');
        const $hidden = $row.find('.product-variant-id');
        const $info = $row.find('.selected-product-info');
        const $results = $row.find('.product-search-results');
        const $stock = $row.find('.current-stock');

        $hidden.val($option.data('id'));
        $input.val($option.data('name'));
        $stock.text($option.data('stock'));
        $info.find('.selected-product-name').text($option.data('name'));
        $info.show();
        $results.hide();
    });

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.product-search, .product-search-results').length) {
            $('.product-search-results').hide();
        }
    });

    // Add new item row
    $('#addItem').click(function() {
        const newItem = $('.item-row').first().clone();
        newItem.find('input').val('');
        newItem.find('.current-stock').text('0');
        newItem.find('.selected-product-info').hide();
        newItem.find('.product-search-results').hide();
        newItem.find('.remove-item').show();

        newItem.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+\]/, '[' + itemIndex + ']'));
            }
        });

        $('#itemsContainer').append(newItem);
        itemIndex++;
    });

    // Remove item row
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
        }
    });

$(document).on('click', '.remove-item', function() {
    if ($('.item-row').length > 1) {
        $(this).closest('.item-row').remove();
    }
});
});
</script>
@endsection
