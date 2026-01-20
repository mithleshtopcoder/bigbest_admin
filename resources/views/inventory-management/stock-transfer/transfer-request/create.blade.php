@extends('layouts.app')

@section('title', 'Create Transfer Request')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="page-title mb-0">Create Transfer Request</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('inventory-management.stock-transfer.transfer-request.store') }}" method="POST">
            @csrf
            <div class="row ps-2">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="form-label col-md-3">From Store <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select form-control" name="from_store_id" required>
                        <option value="">Select Store</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                    </div>
                    <div class="form-group row">
                        <label class="form-label col-md-3">To Store <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                            <select class="form-select form-control" name="to_store_id" required>
                        <option value="">Select Store</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
                    <div class="form-group row">
                        <label class="form-label col-md-3">Transfer Date <span class="text-danger">*</span></label>
                        <div class="col-md-9">
                    <input type="date" class="form-control" name="transfer_date" value="{{ date('Y-m-d') }}" required>
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

            {{-- TRANSFER ITEMS --}}
            <h6 class="mb-3 ps-2">Items Information</h6>

            <div id="itemsContainer">
                <div class="item-row border p-3 rounded mb-3">
                    <div class="row ps-2">
                        <div class="form-group row">
                            <label class="form-label col-md-1">Product <span class="text-danger">*</span></label>
                            <div class="col ps-1">
                                <select class="form-select product-select form-control select2" name="items[0][product_variant_id]" required>
                                <option value="">Select From Store first</option>
                            </select>
                        </div>
                            <label class="form-label col" style="max-width: 159px;width: 159px">Requested Qty <span class="text-danger">*</span></label>
                            <div class="col ps-1" style="max-width: 120px;width: 120px">
                            <input type="number" class="form-control" name="items[0][requested_quantity]" min="1" required>
                        </div>
                            <label class="form-label col" style="max-width: 90px;width: 90px">Notes</label>
                            <div class="col ps-1 pe-0" style="max-width: 280px;width: 280px">
                            <input type="text" class="form-control" name="items[0][notes]">
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
                <button type="submit" class="btn btn-primary">Create Request</button>
                <a href="{{ route('inventory-management.stock-transfer.transfer-request') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    let itemIndex = 0;

    /* ================= INITIALIZE SELECT2 FOR PRODUCT DROPDOWN ================= */
    function initSelect2(dropdown) {
        // Destroy existing Select2 instance if any
        if ($(dropdown).hasClass('select2-hidden-accessible')) {
            $(dropdown).select2('destroy');
        }
        // Initialize Select2
        $(dropdown).select2({
            theme: 'bootstrap-5',
            placeholder: 'Select Product',
            allowClear: true,
            width: '100%'
        });
    }

    /* ================= LOAD PRODUCTS BASED ON FROM STORE ================= */
    function loadProducts(targetDropdown) {
        const storeId = $('select[name="from_store_id"]').val();
        const isSelect2Initialized = $(targetDropdown).hasClass('select2-hidden-accessible');

        if (!storeId) {
            $(targetDropdown).html('<option value="">Select From Store first</option>');
            // Update Select2 if already initialized
            if (isSelect2Initialized) {
                $(targetDropdown).trigger('change.select2');
            }
            return;
        }

        $.ajax({
            url: "{{ route('inventory-management.stock-transfer.products') }}"
            , data: {
                store_id: storeId
            }
            , success: function(res) {
                let options = '<option value="">Select Product</option>';
                res.products.forEach(p => {
                    options += `<option value="${p.id}">${p.label}</option>`;
                });
                $(targetDropdown).html(options);
                // Update Select2 if already initialized
                if (isSelect2Initialized) {
                    $(targetDropdown).trigger('change.select2');
                }
            }
            , error: function() {
                $(targetDropdown).html('<option value="">Error loading products</option>');
                if (isSelect2Initialized) {
                    $(targetDropdown).trigger('change.select2');
                }
            }
        });
    }

    /* ================= INITIALIZE SELECT2 ON PAGE LOAD ================= */
    $(document).ready(function() {
        // Initialize Select2 for existing product dropdowns
        $('.product-select').each(function() {
            initSelect2(this);
        });
    });

    /* ================= ON CHANGE OF FROM STORE ================= */
    $('select[name="from_store_id"]').on('change', function() {
        $('.product-select').each(function() {
            loadProducts($(this));
        });
    });

    /* ================= ADD NEW ITEM ROW ================= */
    $('#addItem').on('click', function() {
        itemIndex++;

        const newRow = $('.item-row:first').clone();
        newRow.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) $(this).attr('name', name.replace(/\d+/, itemIndex)).val('');
        });

        // Destroy Select2 on cloned select before appending
        newRow.find('.product-select').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
                $(this).select2('destroy');
            }
        });

        $('#itemsContainer').append(newRow);
        
        // Load products and initialize Select2 for the new row
        const newDropdown = newRow.find('.product-select');
        loadProducts(newDropdown);
        initSelect2(newDropdown[0]);
    });

</script>
@endsection
