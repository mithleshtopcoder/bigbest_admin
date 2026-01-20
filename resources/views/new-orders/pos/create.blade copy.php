@extends('layouts.app')

@section('title', 'Create POS Order')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Create POS Order</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('new-order.index', 'pos') }}">POS Orders</a></li>
            <li class="breadcrumb-item">Create</li>
        </ul>
    </div>
    <div class="page-header-right ms-auto">
        <div class="page-header-right-items">
            <div class="d-flex d-md-none">
                <a href="javascript:void(0)" class="page-header-right-close-toggle py-2">
                    <i class="feather-arrow-left me-2"></i>
                    <span>Back</span>
                </a>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
            <select class="form-select" style="max-width: 300px;
    width: 300px;
    height: 29px;
    padding: 2px 13px;
    font-size: 14px;" name="store_id" required>
                <option value="">Select Store</option>
                <option value="1">Main Store</option>
                <option value="2">Branch Store</option>
            </select>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <!-- [Create POS Order Form] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-body">
                    <form action="{{ route('new-order.pos.store') }}" method="POST">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-8">
                                <!-- Customer Information -->

                                <div class="card-header py-2 mb-2 pl-1">
                                    <h5 class="card-title mb-0">Customer Information</h5>
                                </div>
                                <div class="form-group row">
                                    <label class="form-label text-md col-md-2 custom-label">Customer Name</label>
                                    <div class="col-md-4 pl-1">
                                        <input type="text" name="customer_name" id="customer_name" class="form-control form-control-sm" placeholder="Enter customer name">
                                    </div>
                                    <label class="form-label text-md col-md-2 custom-label">Customer Phone</label>
                                    <div class="col-md-4 pl-1">
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-sm" placeholder="Enter customer phone">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 pl-1 mb-4">
                                        <div class="mb-3 mt-3">
                                            <label class="form-label">Search Product</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control form-control-sm" id="product_search" placeholder="Scan barcode or search product...">
                                                <button type="button" class="btn btn-primary btn-sm" id="add_product_btn">
                                                    <i class="feather-plus me-2"></i>Add
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="product_table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th style="text-align: right">Price</th>
                                                        <th style="text-align: right;width: 90px;">Quantity</th>
                                                        <th style="text-align: right">Total</th>
                                                        <th class="text-end" style="width: 50px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product_tbody">
                                                    <tr>
                                                        <td>Example Product A</td>
                                                        <td style="text-align: right">$10.00</td>
                                                        <td style="text-align: right">
                                                            <input type="number" class="form-control form-control-sm" value="1" min="1" style="width: 90px;">
                                                        </td>
                                                        <td style="text-align: right">$10.00</td>
                                                        <td class="text-end">
                                                            <button type="button" class="action-danger">
                                                                <i class="feather-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Example Product B</td>
                                                        <td style="text-align: right">$25.00</td>
                                                        <td style="text-align: right">
                                                            <input type="number" class="form-control form-control-sm" value="2" min="1" style="width: 90px;">
                                                        </td>
                                                        <td style="text-align: right">$50.00</td>
                                                        <td class="text-end">
                                                            <button type="button" class="action-danger">
                                                                <i class="feather-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <h5 class="card-title mb-0 mb-2">Terms and Conditions</h5>
                                        <p class="mb-0 text-muted mb-4">
                                            By completing this order, you agree to our terms and conditions. All sales are final unless otherwise stated. Products should be checked upon delivery and any issues must be reported within 24 hours. For full terms, please refer to our website or ask at the counter.
                                        </p>
                                        <h5 class="card-title mb-0">Notes</h5>
                                        <p class="mb-0 text-muted">
                                            Please ensure all provided information is correct before finalizing your order. Delivery times may vary based on stock availability and location. For any special instructions, please use the order notes.
                                        </p>
                                    </div>
                                </div>



                            </div>
                            <div class="col-lg-4">
                                <!-- Order Summary -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Order Summary</h5>
                                    </div>
                                    <div class="card-body">
                                       
                                        <div class="mb-3">
                                            <div class="form-group row mb-1">
                                                <label class="form-label text-md col-md-6 custom-label">Subtotal</label>
                                                <div class="col-md-6 pl-1">
                                                    <input type="number" class="form-control form-control-sm w-100" style="text-align: right" name="discount" id="discount" value="0" min="0" step="0.01" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="form-label text-md col-md-6 custom-label" style="justify-content: space-between;">
                                                    <span>Discount</span>
                                                    <select class="form-select form-control-sm" name="discount_type" id="discount_type" style="max-width:80px;min-height: 25px;line-height: 12px;">
                                                        <option value="amount">₹</option>
                                                        <option value="percent">%</option>
                                                    </select>

                                                </label>
                                                <div class="col-md-6 pl-1">
                                                    <input type="number" class="form-control form-control-sm w-100" style="text-align: right" name="discount" id="discount" value="0" min="0" step="0.01" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="form-label text-md col-md-6 custom-label" style="justify-content: space-between;">
                                                    <span>Tax (GST)</span>
                                                    <select class="form-select form-control-sm" name="discount_type" id="discount_type" style="max-width:80px;min-height: 25px;line-height: 12px;">
                                                        <option value="amount">18 %</option>
                                                        <option value="amount">12 %</option>
                                                        <option value="amount">5 %</option>
                                                    </select>

                                                </label>
                                                <div class="col-md-6 pl-1">
                                                    <input type="number" class="form-control form-control-sm w-100" style="text-align: right" name="discount" id="discount" value="0" min="0" step="0.01" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="form-group row mb-1">
                                                <label class="form-label text-md col-md-6 custom-label">Other Charges</label>
                                                <div class="col-md-6 pl-1">
                                                    <input type="number" class="form-control form-control-sm w-100" style="text-align: right" name="other_charges" id="other_charges" value="0" min="0" step="0.01" autocomplete="off">
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <hr>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fs-5 fw-bold">Total</span>
                                            <span class="fs-5 fw-bold text-primary" id="total">₹60.00</span>
                                        </div>
                                        <input type="hidden" name="subtotal" id="subtotal_input" value="0">
                                        <input type="hidden" name="total" id="total_input" value="0">
                                        <input type="hidden" name="tax_amount" id="tax_amount_input" value="0">



                                        <div class="form-group row mb-1">
                                            <div class="mt-4" style="display: flex;justify-content: space-around;">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" checked>
                                                    <label class="form-check-label" for="payment_cash">
                                                        Cash
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                                    <label class="form-check-label" for="payment_card">
                                                        Card
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_upi" value="upi">
                                                    <label class="form-check-label" for="payment_upi">
                                                        UPI
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_wallet" value="wallet">
                                                    <label class="form-check-label" for="payment_wallet">
                                                        Wallet
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mt-4 mb-4 d-flex justify-content-end">
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="submit" name="action" value="create" class="btn btn-primary btn-sm">
                                                    <i class="feather-check me-2"></i>Pay
                                                </button> 
                                                <button type="submit" name="action" value="save" class="btn btn-success btn-sm">
                                                    <i class="feather-save me-2"></i>Save
                                                </button>
                                                <button type="submit" name="action" value="save_print" class="btn btn-info btn-sm">
                                                    <i class="feather-printer me-2"></i>Print
                                                </button>
                                                <button type="button" class="btn btn-light btn-sm" onclick="window.location.href='{{ route('new-order.index', 'pos') }}'">
                                                    <i class="feather-x me-2"></i>Cancel
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="downloadOrder()">
                                                    <i class="feather-download me-2"></i>Download
                                                </button>
                                            </div>
                                        </div>
                                        <script>
                                            function downloadOrder() {
                                                // Implement your download logic here
                                                alert('Download functionality not implemented yet.');
                                            }
                                        </script>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [Create POS Order Form] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
<script>
    // Customer type toggle
    document.getElementById('customer_type').addEventListener('change', function() {
        const customerSelect = document.getElementById('customer_select_container');
        const customerName = document.getElementById('customer_name');
        if (this.value === 'registered') {
            customerSelect.style.display = 'block';
            customerName.setAttribute('readonly', 'readonly');
        } else {
            customerSelect.style.display = 'none';
            customerName.removeAttribute('readonly');
        }
    });

    // Product management
    let productCounter = 0;
    const products = [];

    document.getElementById('add_product_btn').addEventListener('click', function() {
        const searchValue = document.getElementById('product_search').value;
        if (!searchValue) {
            alert('Please enter a product name or scan barcode');
            return;
        }
        addProductToTable(searchValue);
        document.getElementById('product_search').value = '';
    });

    function addProductToTable(productName) {
        productCounter++;
        const productId = 'product_' + productCounter;
        const row = `
            <tr id="${productId}">
                <td>
                    <input type="hidden" name="products[${productCounter}][name]" value="${productName}">
                    <strong>${productName}</strong><br>
                    <small class="text-muted">SKU: SKU-${productCounter}</small>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm price-input" name="products[${productCounter}][price]" value="0" min="0" step="0.01" style="width: 100px;" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm quantity-input" name="products[${productCounter}][quantity]" value="1" min="1" style="width: 80px;" required>
                </td>
                <td>
                    <span class="product-total">₹0.00</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProduct('${productId}')">
                        <i class="feather-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('product_tbody').insertAdjacentHTML('beforeend', row);
        attachProductEventListeners(productId);
        updateTotals();
    }

    function attachProductEventListeners(productId) {
        const row = document.getElementById(productId);
        const priceInput = row.querySelector('.price-input');
        const quantityInput = row.querySelector('.quantity-input');

        [priceInput, quantityInput].forEach(input => {
            input.addEventListener('input', function() {
                updateProductTotal(productId);
                updateTotals();
            });
        });
    }

    function updateProductTotal(productId) {
        const row = document.getElementById(productId);
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
        const total = price * quantity;
        row.querySelector('.product-total').textContent = '₹' + total.toFixed(2);
    }

    function removeProduct(productId) {
        document.getElementById(productId).remove();
        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#product_tbody tr').forEach(row => {
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const quantity = parseInt(row.querySelector('.quantity-input').value) || 0;
            subtotal += price * quantity;
        });

        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const discountType = document.getElementById('discount_type').value;
        let discountAmount = 0;

        if (discountType === 'percent') {
            discountAmount = (subtotal * discount) / 100;
        } else {
            discountAmount = discount;
        }

        const afterDiscount = subtotal - discountAmount;
        const tax = (afterDiscount * 5) / 100; // 5% GST
        const total = afterDiscount + tax;

        document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('tax').value = tax.toFixed(2);
        document.getElementById('total').textContent = '₹' + total.toFixed(2);

        document.getElementById('subtotal_input').value = subtotal.toFixed(2);
        document.getElementById('tax_amount_input').value = tax.toFixed(2);
        document.getElementById('total_input').value = total.toFixed(2);
    }

    document.getElementById('discount').addEventListener('input', updateTotals);
    document.getElementById('discount_type').addEventListener('change', updateTotals);

</script>
@endsection

@section('styles')
@endsection
