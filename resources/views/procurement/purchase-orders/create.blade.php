@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title mb-0">Create Purchase Order</h1>
    </div>
</div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('procurement.purchase-orders.store') }}" method="POST">
                @csrf
            <div class="row ps-2">
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="form-label col-md-3">Supplier <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select form-control" name="supplier_id" id="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-label col-md-3">Store <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select form-control" name="store_id" id="store_id" required>
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                    <div class="form-group row">
                        <label class="form-label col-md-3">Order Date <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="order_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group row">
                        <label class="form-label col-md-3">Expec. Delivery Date</label>
                        <div class="col-md-9">
                        <input type="date" class="form-control" name="expected_delivery_date">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-label col-md-3" style="height:64px;">Notes</label>
                        <div class="col-md-9">
                            <textarea class="form-control" style="height: 64px;" name="notes" rows="2"></textarea>
                </div>
                    </div>
                    </div>
                </div>

            {{-- ITEMS --}}
            <h6 class="mb-3 ps-2">Items Information</h6>

                <div id="itemsContainer">
                <div class="item-row border p-3 rounded mb-3">
                    <div class="row ps-2">
                        <div class="form-group row">
                            <label class="form-label col-md-1">Product <span class="text-danger">*</span></label>
                            <div class="col ps-1 position-relative">
                                <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." autocomplete="off">
                                <input type="hidden" class="product-variant-id" name="items[0][product_variant_id]" required>
                                <div class="product-search-results" style="display:none; position:absolute; z-index:1000; background:white; border:1px solid #ddd; max-height:200px; overflow-y:auto; width:100%;"></div>
                                <div class="selected-product-info mt-2" style="display:none;">
                                    <small class="text-muted">
                                        Selected: <span class="selected-product-name"></span>
                                    </small>
                                </div>
                            </div>
                            <label class="form-label col" style="max-width: 100px;width: 100px">Quantity <span class="text-danger">*</span></label>
                            <div class="col ps-1" style="max-width: 100px;width: 100px">
                                <input type="number" class="form-control quantity-input" name="items[0][ordered_quantity]" min="1" value="1" required>
                            </div>
                            <label class="form-label col" style="max-width: 100px;width: 100px">Unit Cost <span class="text-danger">*</span></label>
                            <div class="col ps-1" style="max-width: 100px;width: 100px">
                                <input type="number" step="0.01" class="form-control unit-cost-input" name="items[0][unit_cost]" min="0" required>
                            </div>
                            <label class="form-label col" style="max-width: 90px;width: 90px">Tax (%)</label>
                            <div class="col ps-1" style="max-width: 90px;width: 90px">
                                <input type="number" step="0.01" class="form-control" name="items[0][tax_rate]" min="0" max="100" value="0">
                            </div>
                            <label class="form-label col" style="max-width: 90px;width: 90px">Discount (%)</label>
                            <div class="col ps-1 pe-0" style="max-width: 90px;width: 90px">
                                <input type="number" step="0.01" class="form-control" name="items[0][discount_percentage]" min="0" max="100" value="0">
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

            <button type="button" class="btn btn-sm btn-secondary mb-3" id="addItem">
                + Add Item
            </button>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Purchase Order</button>
                    <a href="{{ route('procurement.purchase-orders') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let itemIndex = 1;
    let searchTimeout;

    // ================= Product Search =================
    $(document).on('input', '.product-search', function() {
        const $input = $(this);
        const $row = $input.closest('.item-row');
        const $results = $row.find('.product-search-results');
        const search = $input.val();
        const storeId = $('#store_id').val();

        clearTimeout(searchTimeout);

        if (search.length < 2) {
            $results.hide();
            return;
        }

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("procurement.purchase-orders.search-variants") }}'
                , data: {
                    search: search
                    , store_id: storeId
                }
                , success: function(response) {
                    if (response.variants && response.variants.length) {
                        let html = '';
                        response.variants.forEach(v => {
                            html += `
                            <div class="p-2 border-bottom product-option" style="cursor:pointer"
                                 data-id="${v.id}" data-name="${v.name}" data-product-name="${v.product_name}" data-cost="${v.cost_price}">
                                <strong>${v.product_name}</strong> - ${v.name}
                                <br>
                                <small class="text-muted">SKU: ${v.sku} | Stock: ${v.current_stock}</small>
                            </div>
                        `;
                        });
                        $results.html(html).show();
                    } else {
                        $results.html('<div class="p-2 text-muted">No products found</div>').show();
                    }
                }
            });
        }, 300);
    });

    // ================= Select Product =================
    $(document).on('click', '.product-option', function() {
        const $opt = $(this);
        const $row = $opt.closest('.item-row');
        const fullName = $opt.data('product-name') + ' - ' + $opt.data('name');

        $row.find('.product-variant-id').val($opt.data('id'));
        $row.find('.product-search').val(fullName);
        $row.find('.selected-product-name').text(fullName);
        $row.find('.selected-product-info').show();
        $row.find('.product-search-results').hide();

        // Auto-fill unit cost if available
        if ($opt.data('cost') > 0) {
            $row.find('.unit-cost-input').val($opt.data('cost'));
        }
    });

    // ================= Hide dropdown on outside click =================
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.product-search, .product-search-results').length) {
            $('.product-search-results').hide();
        }
    });

    // ================= Add Item =================
    $('#addItem').click(function() {
        const newItem = $('.item-row').first().clone();
        newItem.find('.product-search').val('');
        newItem.find('.product-variant-id').val('');
        newItem.find('.quantity-input').val(1);
        newItem.find('.unit-cost-input').val('');
        newItem.find('input[name*="[tax_rate]"]').val(0);
        newItem.find('input[name*="[discount_percentage]"]').val(0);
        newItem.find('.selected-product-info').hide();
        newItem.find('.product-search-results').hide();

        newItem.find('input').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+]/, '[' + itemIndex + ']'));
            }
        });

        // Add remove button if not present
        if (newItem.find('.remove-item').length === 0) {
            newItem.find('.form-group.row').append('<div class="col-auto ps-1" style="max-width: 80px;width: 80px"><button type="button" class="btn btn-sm btn-danger remove-item">Remove</button></div>');
        } else {
        newItem.find('.remove-item').show();
        }
        
        $('#itemsContainer').append(newItem);
        itemIndex++;
    });

    // ================= Remove Item =================
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
        }
    });

</script>
@endsection
