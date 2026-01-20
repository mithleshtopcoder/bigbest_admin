@extends('layouts.app')

@section('title', 'Create GRN')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title mb-0">Create GRN</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('procurement.grn') }}">GRN</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">GRN Information</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('procurement.grn.store') }}" method="POST">
            @csrf

            {{-- Header Info --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Purchase Order <span class="text-danger">*</span></label>
                    <select class="form-select" name="purchase_order_id" id="purchase_order_id" required>
                        <option value="">Select Purchase Order</option>
                        @foreach($purchaseOrders as $po)
                        <option value="{{ $po->id }}">
                            {{ $po->po_number }} - {{ $po->supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Receipt Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="receipt_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Invoice Number</label>
                    <input type="text" class="form-control" name="invoice_number">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" class="form-control" name="invoice_date">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea class="form-control" name="notes" rows="2"></textarea>
            </div>

            <hr>
            <h6 class="mb-3">Received Items</h6>

            <div id="itemsContainer">
                <p class="text-muted">Select a Purchase Order to load items</p>
            </div>

            <hr>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create GRN</button>
                <a href="{{ route('procurement.grn') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $('#purchase_order_id').on('change', function() {
        const poId = $(this).val();
        const $container = $('#itemsContainer');

        if (!poId) {
            $container.html('<p class="text-muted">Select a Purchase Order to load items</p>');
            return;
        }

        $container.html('<p class="text-muted">Loading items...</p>');

        $.ajax({
            url: '{{ route("procurement.purchase-orders.items", ":id") }}'.replace(':id', poId)
            , success: function(response) {

                if (!response.items || response.items.length === 0) {
                    $container.html('<p class="text-warning">No pending items found.</p>');
                    return;
                }

                let html = '';

                response.items.forEach(function(item, index) {
                    if (item.pending_quantity > 0) {

                        html += `
                    <div class="border rounded p-3 mb-3">
                        <input type="hidden" name="items[${index}][purchase_order_item_id]" value="${item.id}">

                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Product</label>
                                <input type="text" class="form-control"
                                       value="${item.product_name} - ${item.variant_name}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Ordered</label>
                                <input type="number" class="form-control"
                                       value="${item.ordered_quantity}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Received</label>
                                <input type="number" class="form-control"
                                       value="${item.received_quantity}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Pending</label>
                                <input type="number" class="form-control"
                                       value="${item.pending_quantity}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">Receive Qty *</label>
                                <input type="number" class="form-control"
                                       name="items[${index}][received_quantity]"
                                       min="1" max="${item.pending_quantity}"
                                       value="${item.pending_quantity}" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Damaged Qty</label>
                                <input type="number" class="form-control"
                                       name="items[${index}][damaged_quantity]" value="0" min="0">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Unit Cost</label>
                                <input type="number" step="0.01" class="form-control"
                                       name="items[${index}][unit_cost]"
                                       value="${item.unit_cost}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Expiry Date</label>
                                <input type="date" class="form-control"
                                       name="items[${index}][expiry_date]">
                            </div>
                        </div>
                    </div>`;
                    }
                });

                $container.html(html);
            }
            , error: function() {
                $container.html('<p class="text-danger">Failed to load items.</p>');
            }
        });
    });

</script>
@endsection
