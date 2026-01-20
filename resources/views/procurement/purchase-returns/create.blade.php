@extends('layouts.app')

@section('title', 'Create Purchase Return')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="page-title mb-0">Create Purchase Return</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('procurement.purchase-returns.store') }}" method="POST">
            @csrf

            {{-- SUPPLIER & STORE --}}
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
                    <select class="form-select" name="store_id" required>
                        <option value="">Select Store</option>
                        @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- RETURN TYPE & DATE --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Return Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="return_type" required>
                        <option value="damaged">Damaged</option>
                        <option value="defective">Defective</option>
                        <option value="wrong_item">Wrong Item</option>
                        <option value="excess">Excess</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Return Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="return_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            {{-- GRN & REASON --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">GRN (Optional)</label>
                    <select class="form-select" name="purchase_receipt_id">
                        <option value="">Select GRN</option>
                        @foreach($receipts as $receipt)
                        <option value="{{ $receipt->id }}" data-supplier="{{ $receipt->supplier_id }}" data-store="{{ $receipt->store_id }}">
                            {{ $receipt->receipt_number }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Reason</label>
                    <input type="text" class="form-control" name="reason">
                </div>
            </div>

            {{-- NOTES --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2"></textarea>
                </div>
            </div>

            <hr>

            {{-- RETURN ITEMS --}}
            <h6 class="mb-3">Return Items</h6>

            <div id="itemsContainer">
                <div class="item-row border p-3 rounded mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-select product-select" name="items[0][product_variant_id]" required>
                                <option value="">Select Supplier & Store first</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Return Qty <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="items[0][returned_quantity]" min="1" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Unit Cost <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control" name="items[0][unit_cost]" required>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Tax Rate (%)</label>
                            <input type="number" step="0.01" class="form-control" name="items[0][tax_rate]" value="0">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">Reason</label>
                            <input type="text" class="form-control" name="items[0][reason]">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-sm btn-secondary" id="addItem">
                + Add Item
            </button>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Return</button>
                <a href="{{ route('procurement.purchase-returns') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    let itemIndex = 0;

    /* ================= LOAD PRODUCTS FOR A SPECIFIC DROPDOWN ================= */
    function loadProducts(targetDropdown) {
        const supplierId = $('select[name="supplier_id"]').val();
        const storeId = $('select[name="store_id"]').val();

        if (!supplierId || !storeId) {
            targetDropdown.html('<option value="">Select Supplier & Store first</option>');
            return;
        }

        $.ajax({
            url: "{{ route('procurement.purchase-returns.products') }}"
            , data: {
                supplier_id: supplierId
                , store_id: storeId
            }
            , success: function(res) {
                let options = '<option value="">Select Product</option>';
                res.products.forEach(p => {
                    options += `<option value="${p.id}">${p.label}</option>`;
                });
                targetDropdown.html(options);
            }
            , error: function() {
                targetDropdown.html('<option value="">Error loading products</option>');
            }
        });
    }

    /* ================= AUTO FILL SUPPLIER & STORE FROM GRN ================= */
    $('select[name="purchase_receipt_id"]').on('change', function() {
        const selected = $(this).find(':selected');
        $('select[name="supplier_id"]').val(selected.data('supplier'));
        $('select[name="store_id"]').val(selected.data('store'));

        // Reload products for all dropdowns
        $('.product-select').each(function() {
            loadProducts($(this));
        });
    });

    /* ================= ON CHANGE OF SUPPLIER OR STORE ================= */
    $('select[name="supplier_id"], select[name="store_id"]').on('change', function() {
        $('.product-select').each(function() {
            loadProducts($(this));
        });
    });

    /* ================= ADD NEW ITEM ROW ================= */
    $('#addItem').on('click', function() {
        itemIndex++;

        // Clone the first row
        const newRow = $('.item-row:first').clone();

        // Reset values and update names
        newRow.find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name) {
                $(this).attr('name', name.replace(/\d+/, itemIndex)).val('');
            }
        });

        // Append the new row
        $('#itemsContainer').append(newRow);

        // Load products for the new row only
        loadProducts(newRow.find('.product-select'));
    });

</script>
@endsection
