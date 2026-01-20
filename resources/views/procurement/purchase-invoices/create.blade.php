@extends('layouts.app')

@section('title', 'Create Purchase Invoice')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Create Purchase Invoice</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('procurement.purchase-invoices') }}" class="text-decoration-none">Purchase Invoices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Invoice Information</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('procurement.purchase-invoices.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select class="form-select" name="supplier_id" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Store <span class="text-danger">*</span></label>
                        <select class="form-select" name="store_id" id="store_id" required>
                            <option value="">Select Store</option>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Due Date</label>
                        <input type="date" class="form-control" name="due_date">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Supplier Invoice Number</label>
                        <input type="text" class="form-control" name="supplier_invoice_number">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GRN (Optional)</label>
                        <select class="form-select" name="purchase_receipt_id">
                            <option value="">Select GRN</option>
                            @foreach($receipts as $receipt)
                            <option value="{{ $receipt->id }}">{{ $receipt->receipt_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>

                <hr>
                <h6 class="mb-3">Invoice Items</h6>

                <div id="itemsContainer">
                    <div class="item-row mb-3 border p-3 rounded">
                        <div class="row">
                            <!-- Product Search -->
                            <div class="col-md-4 position-relative">
                                <label class="form-label">Product <span class="text-danger">*</span></label>
                                <input type="text" class="form-control product-search" placeholder="Search product by name, SKU, or barcode..." autocomplete="off">
                                <input type="hidden" class="product-variant-id" name="items[0][product_variant_id]" required>
                                <div class="product-search-results" style="display:none; position:absolute; z-index:1000; background:white; border:1px solid #ddd; max-height:200px; overflow-y:auto; width:100%;"></div>
                                <div class="selected-product-info mt-2" style="display:none;">
                                    <small class="text-muted">Selected: <span class="selected-product-name"></span></small>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" class="form-control quantity-input" name="items[0][quantity]" min="1" value="1" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Unit Cost <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="items[0][unit_cost]" min="0" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" name="items[0][tax_rate]" min="0" max="100" value="0">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Discount (%)</label>
                                <input type="number" step="0.01" class="form-control" name="items[0][discount_percentage]" min="0" max="100" value="0">
                            </div>

                            <div class="col-md-12 mt-2">
                                <button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="addItem" class="btn btn-sm btn-secondary mb-3">+ Add Item</button>

                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Invoice</button>
                    <a href="{{ route('procurement.purchase-invoices') }}" class="btn btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    let itemIndex = 1;
    let searchTimeout;

    // Product search
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
                    if (response.variants && response.variants.length) {
                        let html = '';
                        response.variants.forEach(v => {
                            html += `
                            <div class="p-2 border-bottom product-option" style="cursor:pointer"
                                 data-id="${v.id}" data-name="${v.name}" data-product-name="${v.product_name}">
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

    // Select product
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

    // Hide dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.product-search, .product-search-results').length) {
            $('.product-search-results').hide();
        }
    });

    // Add item
    $('#addItem').click(function() {
        const newItem = $('.item-row').first().clone();
        newItem.find('input').val('');
        newItem.find('.selected-product-info').hide();
        newItem.find('.remove-item').show();

        newItem.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) $(this).attr('name', name.replace(/\[\d+]/, '[' + itemIndex + ']'));
        });

        $('#itemsContainer').append(newItem);
        itemIndex++;
    });

    // Remove item
    $(document).on('click', '.remove-item', function() {
        if ($('.item-row').length > 1) {
            $(this).closest('.item-row').remove();
        }
    });

</script>
@endsection
