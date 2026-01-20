@extends('layouts.app')

@section('title', 'Create Stock Movement')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Stock Movement</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('inventory-management.stock-in-stock-out') }}" class="text-decoration-none">
                            Stock In / Stock Out
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Stock Movement Information</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('inventory-management.stock-in-stock-out.store') }}" method="POST">
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
                        <label class="form-label">Movement Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="movement_type" required>
                            <option value="">Select Type</option>
                            <option value="adjustment">Adjustment</option>
                            <option value="damage">Damage (Stock Out)</option>
                            <option value="expiry">Expiry (Stock Out)</option>
                            <option value="return">Return (Stock In)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Movement Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="movement_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <hr>
                <h6 class="mb-3">Items</h6>

                <div id="itemsContainer">
                    <div class="item-row mb-3 border p-3 rounded">
                        <div class="row">
                            <!-- IMPORTANT: position-relative FIX -->
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Product <span class="text-danger">*</span></label>

                                <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." autocomplete="off">

                                <input type="hidden" class="product-variant-id" name="items[0][product_variant_id]" required>

                                <div class="product-search-results" style="display:none; position:absolute; z-index:1000; background:white;
                                     border:1px solid #ddd; max-height:200px; overflow-y:auto; width:100%;">
                                </div>

                                <div class="selected-product-info mt-2" style="display:none;">
                                    <small class="text-muted">
                                        Selected: <span class="selected-product-name"></span>
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" required>
                                <small class="text-muted">Use negative for stock out</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Notes</label>
                                <input type="text" class="form-control" name="items[0][notes]">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-danger remove-item" style="display:none;">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="addItem" class="btn btn-sm btn-secondary mb-3">
                    Add Item
                </button>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Create Movement
                    </button>
                    <a href="{{ route('inventory-management.stock-in-stock-out') }}" class="btn btn-light">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
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
                url: '{{ route("inventory-management.search-variants") }}'
                , data: {
                    search: search
                    , store_id: storeId
                }
                , success: function(response) {
                    console.log('Search Response:', response);

                    if (response.variants && response.variants.length) {
                        let html = '';
                        response.variants.forEach(v => {
                            html += `
                                <div class="p-2 border-bottom product-option"
                                     style="cursor:pointer"
                                     data-id="${v.id}"
                                     data-name="${v.name}"
                                     data-product-name="${v.product_name}">
                                    <strong>${v.product_name}</strong> - ${v.name}
                                    <br>
                                    <small class="text-muted">
                                        SKU: ${v.sku} | Stock: ${v.current_stock}
                                    </small>
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
    });

    // Hide dropdown
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
        newItem.find('.quantity-input').val('');
        newItem.find('.selected-product-info').hide();

        newItem.find('input').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\[\d+]/, '[' + itemIndex + ']'));
            }
        });

        newItem.find('.remove-item').show();
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
