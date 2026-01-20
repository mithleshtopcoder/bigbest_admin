@extends('layouts.app')

@section('title', 'Create POS Order')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <h1 class="page-title mb-0">Create POS</h1>
        <nav aria-label="breadcrumb" class="px-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('new-order.index', 'pos') }}">POS Orders</a></li>
                <li class="breadcrumb-item">Create</li>
            </ol>
        </nav>
    </div>
</div>

</div>
<!-- [ page-header ] end -->

<div class="row">
    <div class="col-12">
        <div class="card stretch stretch-full">
            <div class="card-body">
                <form action="{{ route('new-order.pos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="old_order_id" value="{{ $order->id ?? '' }}">

                    <div class="page-header-right ms-auto">
                        <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                            {{-- <select class="form-select" style="max-width: 300px; width: 300px; height: 29px; padding: 2px 13px; font-size: 14px;" name="store_id" id="store_id" required>
                                <option value="">Select Store</option>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" @if(isset($order) && $order->store_id == $store->id) selected @endif>{{ $store->name }}</option>
                            @endforeach
                            </select> --}}
                            <select class="form-select" style="max-width: 300px; width: 300px; height: 29px; padding: 2px 13px; font-size: 14px;" name="store_id" id="store_id" required{{ $autoStoreId ? 'disabled' : '' }}>

                                <option value="">Select Store</option>

                                @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ $autoStoreId == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}
                                </option>
                                @endforeach
                            </select>

                        </div>
                    </div>


                    <div class="row pl-2">
                        <div class="col-lg-8">
                            <!-- Customer Information -->
                            <div class="card-header py-2 mb-2 pl-1">
                                <h5 class="card-title mb-0">Customer Information</h5>
                            </div>
                            <div class="form-group row">
                                <label class="form-label text-md col-md-2 custom-label">Name</label>
                                <div class="col-md-4 pl-1">
                                    <input type="text" name="customer_name" id="customer_name" class="form-control form-control-sm" placeholder="Enter customer name" value="{{ $order->customer->full_name ?? '' }}" required>
                                </div>
                                <label class="form-label text-md col-md-2 custom-label">Phone</label>
                                <div class="col-md-4 pl-1 position-relative">
                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-sm" placeholder="Enter or search phone number" autocomplete="off" value="{{ $order->customer->phone ?? '' }}" required>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $order->customer_id ?? '' }}">
                                    <div id="customer_suggestions" class="position-absolute w-100 bg-white border rounded shadow-lg" style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; top: 100%; margin-top: 2px;"></div>
                                </div>
                            </div>

                            <!-- Product Search -->
                            <div class="row">
                                <div class="col-md-12 pl-1 mb-4">
                                    <div class="mb-3 mt-3">
                                        <label class="form-label">Search Product</label>
                                        <div class="input-group position-relative">
                                            <input type="text" class="form-control form-control-sm" id="product_search" placeholder="Scan barcode or search product..." autocomplete="off">
                                            <button type="button" class="btn btn-primary btn-sm" id="add_product_btn">
                                                <i class="feather-plus me-2"></i>Add
                                            </button>
                                            <div id="product_suggestions" class="position-absolute w-100 bg-white border rounded shadow-lg" style="z-index: 1000; display: none; max-height: 300px; overflow-y: auto; top: 100%; margin-top: 2px; left: 0;"></div>
                                        </div>
                                    </div>

                                    <!-- Product Table -->
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
                                                @if(isset($order))
                                                @php $productCounter = 0; @endphp
                                                @foreach($order->items as $item)
                                                @php $productCounter++; @endphp
                                                <tr id="product_{{ $productCounter }}">
                                                    <td>
                                                        <input type="hidden" name="products[{{ $productCounter }}][product_id]" value="{{ $item->product_id }}">
                                                        <input type="hidden" name="products[{{ $productCounter }}][variant_id]" value="{{ $item->product_variant_id }}">
                                                        <input type="hidden" name="products[{{ $productCounter }}][name]" value="{{ $item->product_name }}">
                                                        <input type="hidden" name="products[{{ $productCounter }}][variant_name]" value="{{ $item->variant_name ?? '' }}">
                                                        <input type="hidden" name="products[{{ $productCounter }}][sku]" value="{{ $item->product_sku ?? '' }}">
                                                        <strong>{{ $item->product_name }}</strong>
                                                        @if($item->variant_name)<br><small class="text-muted">{{ $item->variant_name }}</small>@endif
                                                        <br><small class="text-muted">SKU: {{ $item->product_sku ?? '' }}</small>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <input type="number" class="form-control form-control-sm price-input" name="products[{{ $productCounter }}][price]" value="{{ $item->unit_price }}" min="0" step="0.01" style="width: 100px; text-align: right;" required>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <input type="number" class="form-control form-control-sm quantity-input" name="products[{{ $productCounter }}][quantity]" value="{{ $item->quantity }}" min="1" style="width: 80px; text-align: right;" required>
                                                    </td>
                                                    <td style="text-align: right">
                                                        <span class="product-total">₹{{ number_format($item->total_price, 2) }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeProduct('product_{{ $productCounter }}', {{ $item->product_variant_id }})">
                                                            <i class="feather-trash-2"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Terms & Notes -->
                                <div class="col-md-12 mt-4">
                                    <h5 class="card-title mb-0 mb-2">Terms and Conditions</h5>
                                    <p class="mb-0 text-muted mb-4">
                                        By completing this order, you agree to our terms and conditions. All sales are final unless otherwise stated.
                                    </p>
                                    <h5 class="card-title mb-0">Notes</h5>
                                    <p class="mb-0 text-muted">
                                        Please ensure all provided information is correct before finalizing your order.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Subtotal</label>
                                            <div class="col-md-6 pl-1">
                                                <span class="form-control form-control-sm w-100" style="text-align: right; background-color: #f8f9fa;" id="subtotal">₹0.00</span>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-1 align-items-center">
                                            <div class="col-md-6 d-flex align-items-center justify-content-between">
                                                <label class="form-label mb-0 custom-label">Discount</label>
                                                <select class="form-select form-select-sm ms-2" name="discount_type" id="discount_type" style="max-width:80px;height:25px;">
                                                    <option value="amount">₹</option>
                                                    <option value="percent">%</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 ps-1">
                                                <input type="number" class="form-control form-control-sm text-end" name="discount" id="discount" value="{{ $order->discount_amount ?? 0 }}" min="0" step="0.01">
                                            </div>
                                        </div>

                                        <div class="form-group row mb-1 align-items-center">
                                            <div class="col-md-6 d-flex align-items-center justify-content-between">
                                                <label class="form-label mb-0 custom-label">Tax</label>
                                                <select class="form-select form-select-sm ms-2" name="tax_percent" id="tax_percent" style="max-width:80px;height:25px;">
                                                    <option value="18" @if(isset($order) && $order->tax_amount > 0) selected @endif>18%</option>
                                                    <option value="12">12%</option>
                                                    <option value="5">5%</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 ps-1">
                                                <span class="form-control form-control-sm text-end bg-light" id="tax_display">₹0.00</span>
                                            </div>
                                        </div>

                                        <div class="form-group row mb-1">
                                            <label class="form-label text-md col-md-6 custom-label">Other Charges</label>
                                            <div class="col-md-6 pl-1">
                                                <input type="number" class="form-control form-control-sm w-100 text-end" name="other_charges" id="other_charges" value="{{ $order->shipping_charge ?? 0 }}" min="0" step="0.01" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fs-5 fw-bold">Total</span>
                                        <span class="fs-5 fw-bold text-primary" id="total">₹0.00</span>
                                    </div>

                                    <input type="hidden" name="subtotal" id="subtotal_input" value="0">
                                    <input type="hidden" name="total" id="total_input" value="0">
                                    <input type="hidden" name="tax_amount" id="tax_amount_input" value="0">

                                    <!-- Payment -->
                                    <div class="form-group row mb-1">
                                        <div class="mt-4 d-flex justify-content-around flex-wrap">
                                            @php
                                            $paymentMethod = $order->payment_method ?? 'cash';
                                            @endphp
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" @if($paymentMethod=='cash' ) checked @endif>
                                                <label class="form-check-label" for="payment_cash">Cash</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" @if($paymentMethod=='card' ) checked @endif>
                                                <label class="form-check-label" for="payment_card">Card</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_upi" value="upi" @if($paymentMethod=='upi' ) checked @endif>
                                                <label class="form-check-label" for="payment_upi">UPI</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_method" id="payment_wallet" value="wallet" @if($paymentMethod=='wallet' ) checked @endif>
                                                <label class="form-check-label" for="payment_wallet">Wallet</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="form-group row mt-4 mb-4 d-flex justify-content-end">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="submit" name="action" value="hold" class="btn btn-warning btn-sm">
                                                <i class="feather-pause me-2"></i>Hold Bill
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Customer phone autocomplete
    let searchTimeout;
    const customerPhoneInput = document.getElementById('customer_phone');
    const customerNameInput = document.getElementById('customer_name');
    const customerIdInput = document.getElementById('customer_id');
    const suggestionsDiv = document.getElementById('customer_suggestions');

    customerPhoneInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Clear previous timeout
        clearTimeout(searchTimeout);

        // Clear customer ID when phone changes
        customerIdInput.value = '';

        if (query.length < 2) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        // Debounce search
        searchTimeout = setTimeout(function() {
            fetch('{{ route("new-order.search-customers") }}?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    displaySuggestions(data.data);
                })
                .catch(error => {
                    console.error('Error searching customers:', error);
                });
        }, 300);
    });

    function displaySuggestions(customers) {
        if (customers.length === 0) {
            suggestionsDiv.style.display = 'none';
            return;
        }

        suggestionsDiv.innerHTML = '';
        customers.forEach(customer => {
            const item = document.createElement('div');
            item.className = 'p-2 border-bottom cursor-pointer customer-suggestion-item';
            item.style.cursor = 'pointer';
            item.innerHTML = `
                <div class="fw-medium">${customer.name}</div>
                <small class="text-muted">${customer.phone}</small>
            `;
            item.addEventListener('click', function() {
                selectCustomer(customer);
            });
            suggestionsDiv.appendChild(item);
        });
        suggestionsDiv.style.display = 'block';
    }

    function selectCustomer(customer) {
        customerPhoneInput.value = customer.phone;
        customerNameInput.value = customer.name;
        customerIdInput.value = customer.id;
        suggestionsDiv.style.display = 'none';
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerPhoneInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
            suggestionsDiv.style.display = 'none';
        }
    });

    // Allow manual editing - clear customer ID if user manually changes phone
    customerPhoneInput.addEventListener('blur', function() {
        // Small delay to allow click on suggestion
        setTimeout(function() {
            const currentPhone = customerPhoneInput.value.trim();

            let savedPhone = null;

            if (customerIdInput.value) {
                const el = document.querySelector(`[data-phone="${currentPhone}"]`);
                savedPhone = el ? el.dataset.phone : null;
            }

            // If phone doesn't match selected customer, clear customer ID
            if (!savedPhone && customerIdInput.value) {
                fetch('{{ route("new-order.search-customers") }}?q=' + encodeURIComponent(currentPhone))
                    .then(response => response.json())
                    .then(data => {
                        const exactMatch = data.data.find(c => c.phone === currentPhone);
                        if (!exactMatch) {
                            customerIdInput.value = '';
                            customerNameInput.value = '';
                        }
                    })
                    .catch(() => {
                        customerIdInput.value = '';
                        customerNameInput.value = '';
                    });
            }
        }, 200);
    });

    // Product management
    let productCounter = document.querySelectorAll('#product_tbody tr').length;

    const addedProducts = new Map();
    document.querySelectorAll('#product_tbody tr').forEach(row => {
        const variantInput = row.querySelector('input[name*="[variant_id]"]');
        if (variantInput) {
            addedProducts.set(parseInt(variantInput.value), row.id);
        }
    });
    document.querySelectorAll('#product_tbody tr').forEach(row => {
        const rowId = row.id;
        attachProductEventListeners(rowId);
    });
    let productSearchTimeout;

    // Product search autocomplete
    const productSearchInput = document.getElementById('product_search');
    const productSuggestionsDiv = document.getElementById('product_suggestions');
    const storeIdSelect = document.getElementById('store_id');

    productSearchInput.addEventListener('input', function() {
        const query = this.value.trim();
        const storeId = storeIdSelect ? storeIdSelect.value : '';

        clearTimeout(productSearchTimeout);

        if (query.length < 2) {
            productSuggestionsDiv.style.display = 'none';
            return;
        }

        productSearchTimeout = setTimeout(function() {
            let url = '{{ route("new-order.search-products") }}?q=' + encodeURIComponent(query);
            if (storeId) {
                url += '&store_id=' + encodeURIComponent(storeId);
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displayProductSuggestions(data.data);
                })
                .catch(error => {
                    console.error('Error searching products:', error);
                });
        }, 300);
    });

    // Handle Enter key to add first suggestion or selected product
    productSearchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const firstSuggestion = productSuggestionsDiv.querySelector('.product-suggestion-item');
            if (firstSuggestion) {
                firstSuggestion.click();
            } else if (this.value.trim()) {
                // If no suggestions, try to add as manual product
                alert('Product not found. Please select from suggestions or ensure store is selected.');
            }
        }
    });

    function displayProductSuggestions(products) {
        if (products.length === 0) {
            productSuggestionsDiv.style.display = 'none';
            return;
        }

        productSuggestionsDiv.innerHTML = '';
        products.forEach(product => {
            const item = document.createElement('div');
            item.className = 'p-2 border-bottom cursor-pointer product-suggestion-item';
            item.style.cursor = 'pointer';

            const stockInfo = product.stock > 0 ?
                `<span class="badge bg-success">Stock: ${product.stock}</span>` :
                `<span class="badge bg-danger">Out of Stock</span>`;

            item.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-medium">${product.name}</div>
                        <small class="text-muted">${product.variant_name || ''} | SKU: ${product.variant_sku || product.sku}</small>
                        <div class="mt-1">${stockInfo}</div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">₹${parseFloat(product.price).toFixed(2)}</div>
                        <small class="text-muted">${product.unit || ''}</small>
                    </div>
                </div>
            `;

            item.addEventListener('click', function() {
                selectProduct(product);
            });

            productSuggestionsDiv.appendChild(item);
        });
        productSuggestionsDiv.style.display = 'block';
    }

    function selectProduct(product) {
        // Check if product already added
        if (addedProducts.has(product.variant_id)) {
            alert('Product already added. Please increase quantity instead.');
            productSearchInput.value = '';
            productSuggestionsDiv.style.display = 'none';
            return;
        }

        // Check stock
        if (product.stock <= 0) {
            if (!confirm('Product is out of stock. Do you want to add it anyway?')) {
                return;
            }
        }

        addProductToTable(product);
        productSearchInput.value = '';
        productSuggestionsDiv.style.display = 'none';
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!productSearchInput.contains(e.target) && !productSuggestionsDiv.contains(e.target)) {
            productSuggestionsDiv.style.display = 'none';
        }
    });

    // Add product button
    document.getElementById('add_product_btn').addEventListener('click', function() {
        const query = productSearchInput.value.trim();
        if (!query) {
            alert('Please enter a product name or scan barcode');
            return;
        }
        // Trigger search to show suggestions
        productSearchInput.dispatchEvent(new Event('input'));
    });

    function addProductToTable(product) {
        productCounter++;
        const productId = 'product_' + productCounter;

        // Track added product
        addedProducts.set(product.variant_id, productId);

        const row = `
            <tr id="${productId}">
                <td>
                    <input type="hidden" name="products[${productCounter}][product_id]" value="${product.product_id}">
                    <input type="hidden" name="products[${productCounter}][variant_id]" value="${product.variant_id}">
                    <input type="hidden" name="products[${productCounter}][name]" value="${product.name}">
                    <input type="hidden" name="products[${productCounter}][variant_name]" value="${product.variant_name || ''}">
                    <input type="hidden" name="products[${productCounter}][sku]" value="${product.variant_sku || product.sku}">
                    <strong>${product.name}</strong>
                    ${product.variant_name ? '<br><small class="text-muted">' + product.variant_name + '</small>' : ''}
                    <br><small class="text-muted">SKU: ${product.variant_sku || product.sku}</small>
                    ${product.stock > 0 ? '<br><small class="text-success">Stock: ' + product.stock + ' ' + (product.unit || '') + '</small>' : '<br><small class="text-danger">Out of Stock</small>'}
                </td>
                <td style="text-align: right">
                    <input type="number" class="form-control form-control-sm price-input" name="products[${productCounter}][price]" value="${product.price}" min="0" step="0.01" style="width: 100px; text-align: right;" required>
                </td>
                <td style="text-align: right">
                    <input type="number" class="form-control form-control-sm quantity-input" name="products[${productCounter}][quantity]" value="1" min="1" max="${product.stock > 0 ? product.stock : ''}" style="width: 80px; text-align: right;" required>
                </td>
                <td style="text-align: right">
                    <span class="product-total">₹${(parseFloat(product.price) * 1).toFixed(2)}</span>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProduct('${productId}', ${product.variant_id})">
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

    function removeProduct(productId, variantId) {
        document.getElementById(productId).remove();
        addedProducts.delete(variantId);
        updateTotals();
    }

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#product_tbody tr').forEach(row => {
            const priceInput = row.querySelector('.price-input');
            const quantityInput = row.querySelector('.quantity-input');
            if (priceInput && quantityInput) {
                const price = parseFloat(priceInput.value) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                subtotal += price * quantity;
            }
        });

        const discountInput = document.getElementById('discount');
        const discountTypeSelect = document.getElementById('discount_type');
        const discount = parseFloat(discountInput ? discountInput.value : 0) || 0;
        const discountType = discountTypeSelect ? discountTypeSelect.value : 'amount';
        let discountAmount = 0;

        if (discountType === 'percent') {
            discountAmount = (subtotal * discount) / 100;
        } else {
            discountAmount = discount;
        }

        const afterDiscount = subtotal - discountAmount;
        const taxPercentSelect = document.getElementById('tax_percent');
        const taxPercent = parseFloat(taxPercentSelect ? taxPercentSelect.value : 18);
        const tax = (afterDiscount * taxPercent) / 100;
        const otherChargesInput = document.getElementById('other_charges');
        const otherCharges = parseFloat(otherChargesInput ? otherChargesInput.value : 0) || 0;
        const total = afterDiscount + tax + otherCharges;

        // Update display
        document.getElementById('subtotal').textContent = '₹' + subtotal.toFixed(2);
        document.getElementById('tax_display').textContent = '₹' + tax.toFixed(2);
        document.getElementById('total').textContent = '₹' + total.toFixed(2);

        document.getElementById('subtotal_input').value = subtotal.toFixed(2);
        document.getElementById('tax_amount_input').value = tax.toFixed(2);
        document.getElementById('total_input').value = total.toFixed(2);
    }

    // Update totals when tax percent changes
    const taxPercentSelect = document.getElementById('tax_percent');
    if (taxPercentSelect) {
        taxPercentSelect.addEventListener('change', updateTotals);
    }

    // Update totals on discount change
    const discountInput = document.getElementById('discount');
    const discountTypeSelect = document.getElementById('discount_type');
    const otherChargesInput = document.getElementById('other_charges');

    if (discountInput) {
        discountInput.addEventListener('input', updateTotals);
    }
    if (discountTypeSelect) {
        discountTypeSelect.addEventListener('change', updateTotals);
    }
    if (otherChargesInput) {
        otherChargesInput.addEventListener('input', updateTotals);
    }

    document.querySelectorAll('button[name="action"]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (this.value === 'hold') {
                const rows = document.querySelectorAll('#product_tbody tr');
                if (rows.length === 0) {
                    e.preventDefault();
                    alert('Add at least one product to hold the bill.');
                }
            }
        });
    });
    updateTotals();

</script>
@endsection

@section('styles')
<style>
    #customer_suggestions,
    #product_suggestions {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .customer-suggestion-item:hover,
    .product-suggestion-item:hover {
        background-color: #f8f9fa;
    }

    .customer-suggestion-item:last-child,
    .product-suggestion-item:last-child {
        border-bottom: none !important;
    }

</style>
@endsection
