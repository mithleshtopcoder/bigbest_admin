@extends('layouts.app')
@section('title', 'Create Product')
@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Add New Product</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item text-muted">Manage Product</li>
                    <li class="breadcrumb-item text-muted"><a href="{{ route('manage-product.product-master.index') }}" class="text-decoration-none">Product Master</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Create Product</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
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

                <form method="POST" action="{{ route('manage-product.product-master.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row ps-2">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Type</label>
                                <div class="col-md-7 ps-1">
                                    <select name="item_type" class="form-control form-control">
                                        <option value="">Select Item Type</option>
                                        @foreach ($itemTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Code</label>
                                <div class="col-md-7 ps-1">
                                    <input type="text" name="item_code" id="item_code" class="form-control form-control" placeholder="Enter item code">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Category</label>
                                <div class="col-md-7 ps-1">
                                    <select id="category_id" name="category_id" class="form-control form-control select2">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Name</label>
                                <div class="col-md-7 ps-1">
                                    <input type="text" name="name" class="form-control form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Sub Category</label>
                                <div class="col-md-7 ps-1">
                                    <select id="sub_category_id" name="sub_category_id" class="form-select form-control">
                                        <option value="">Select Sub Category</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-4">Barcode No</label>
                                <div class="col-md-6 ps-1">
                                    <input type="text" name="barcode" id="barcode_no" class="form-control form-control" placeholder="Enter barcode no">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-secondary btn-sm px-2" style="height: 32px;" onclick="openBarcodeModal()" title="View Barcode">
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
                                        <label class="form-label col-md-12 ps-2">Description</label>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check form-switch ps-0">
                                            <label class="form-label col-md-12 rstc-temp-label ps-2" style="display:flex;justify-content:space-between;">
                                                <span>Single Item</span>
                                                <input class="form-check-input item-type" name="inventory_item" value="1" id="inventory-item" type="checkbox">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check form-switch ps-0">
                                            <label class="form-label col-md-12 rstc-temp-label ps-2" style="display:flex;justify-content:space-between;">
                                                <span>Multiple Item</span>
                                                <input class="form-check-input item-type" name="sales_item" value="1" id="sales-item" type="checkbox">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9 ps-0">
                                    <textarea name="description" id="description" cols="30" rows="5" class="form-control form-control" placeholder="Enter description" style="height: 100px;"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-4">Image</label>
                                <div class="col-md-6 ps-1">
                                    <input type="file" name="thumbnail_image" class="form-control form-control" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewThumbnail(this)">
                                    <small class="text-muted d-block mt-1">Allowed formats: JPG, PNG, WEBP. Max size: 2MB</small>
                                    <div class="mt-2">
                                        <img id="thumbnailPreview" src="{{ isset($product) && $product->thumbnail_image ? asset($product->thumbnail_image) : '' }}" alt="Thumbnail Preview" style="display: {{ isset($product) && $product->thumbnail_image ? 'block' : 'none' }};
                                            width: 120px;
                                            height: 120px;
                                            object-fit: cover;
                                            border: 1px solid #ddd;
                                            border-radius: 6px;
                                            padding: 4px;">
                                    </div>
                                </div>
                                <div class="col-md-2 ps-1">show</div>
                            </div>
                        </div>
                    </div>
                    <div class="row ps-2">
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item Brand</label>
                                <div class="col-md-7 ps-1">
                                    <select name="brand_id" id="brand_id" class="form-select form-control">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-5">Item SKU</label>
                                <div class="col-md-7 ps-1">
                                    <input type="text" name="sku" class="form-control form-control" placeholder="Enter item sku">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Collection</label>
                                <div class="col-md-7 ps-1">
                                    <select name="collection" class="form-control form-control">
                                        <option value="">Select Collection</option>
                                        @foreach ($collections as $collection)
                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-5">Season</label>
                                <div class="col-md-7 ps-1">
                                    <select name="season" class="form-control form-control">
                                        <option value="">Select Season</option>
                                        @foreach ($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" style="align-items: center; display: flex; justify-content: center;">
                            <button class="btn btn-primary btn-sm px-2" style="font-size: 14px;">
                                <i class="feather-plus fs-5"></i>
                                Create Item
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- [ Main Content ] end -->
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
                        class="form-select form-control"
                        required>
                    <option value="">Select Store</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">
                            {{ $store->name }} ({{ $store->code }})
                        </option>
                    @endforeach
                </select>
            </td>

            <td>
                <select name="stock[${stockRowCount}][product_variant]"
                        class="form-select form-control stock-variant">
                    <option value="">Select Variant</option>
                </select>
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][stock_quantity]"
                       class="form-control form-control"
                       value="0" required>
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][min_stock]"
                       class="form-control form-control"
                       value="0">
            </td>

            <td>
                <input type="number"
                       name="stock[${stockRowCount}][max_stock]"
                       class="form-control form-control"
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
                    class="form-select form-control"
                    required>
                <option value="">Select Store</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">
                        {{ $store->name }} ({{ $store->code }})
                    </option>
                @endforeach
            </select>
        </td>

        <td>
            <select name="price[${priceRowCount}][product_variant_id]"
                    class="form-select form-control price-variant"
                    required>
                <option value="">Select Variant</option>
            </select>
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][cost_price]"
                   class="form-control form-control cost-price"
                   step="0.01">
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][price]"
                   class="form-control form-control selling-price"
                   step="0.01" required>
        </td>

        <td>
            <input type="number"
                   name="price[${priceRowCount}][compare_at_price]"
                   class="form-control form-control"
                   step="0.01">
        </td>

        <td>
            <input type="text"
                   name="price[${priceRowCount}][margin]"
                   class="form-control form-control margin"
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
                    <select name="discount[${discountRowCount}][store_location]" class="form-select form-control" required>
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
                    <select name="discount[${discountRowCount}][discount_type]" class="form-select form-control">
                        <option value="">No Discount</option>
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed">Fixed Amount</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][discount_value]" class="form-control form-control" placeholder="Discount value" step="0.01" value="0">
                </td>
                <td>
                    <input type="date" name="discount[${discountRowCount}][discount_start_date]" class="form-control form-control">
                </td>
                <td>
                    <input type="date" name="discount[${discountRowCount}][discount_end_date]" class="form-control form-control">
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][discount_min_qty]" class="form-control form-control" placeholder="Min qty" value="1">
                </td>
                <td>
                    <input type="number" name="discount[${discountRowCount}][max_discount]" class="form-control form-control" placeholder="Max discount" step="0.01">
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
        const row = document.querySelector('#variantTableBody tr.bg-light');

        const name = row.querySelector('[name="new_variant[name]"]').value.trim();
        const sku = row.querySelector('[name="new_variant[sku]"]').value.trim();
        const variantType = row.querySelector('[name="new_variant[variant_type]"]').value;
        const variantValue = row.querySelector('[name="new_variant[variant_value]"]').value.trim();
        const barcode = row.querySelector('[name="new_variant[barcode]"]').value.trim();
        const status = row.querySelector('[name="new_variant[is_active]"]').value;
        const imageInput = row.querySelector('[name="new_variant[images][]"]'); // multiple files

        if (!name || !barcode) {
            alert('Variant Name & Barcode are required');
            return;
        }

        const tbody = document.getElementById('variantTableBody');
        const index = tbody.querySelectorAll('tr').length - 1;

        // Prepare a display of image filenames (optional)
        let imageHTML = '-';
        if (imageInput && imageInput.files.length > 0) {
            imageHTML = '<ul class="mb-0">';
            for (let i = 0; i < imageInput.files.length; i++) {
                const file = imageInput.files[i];
                imageHTML += `<li>${file.name}</li>`;
            }
            imageHTML += '</ul>';
        }

        // Create a new row
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
        <td><input type="checkbox" name="variants[${index}][selected]"></td>

        <td>${name}
            <input type="hidden" name="variants[${index}][name]" value="${name}" class="variant-name">
        </td>

        <td>${sku || '-' }
            <input type="hidden" name="variants[${index}][sku]" value="${sku}">
        </td>

        <td>${variantType || '-' }
            <input type="hidden" name="variants[${index}][variant_type]" value="${variantType}">
        </td>

        <td>${variantValue || '-' }
            <input type="hidden" name="variants[${index}][variant_value]" value="${variantValue}">
        </td>

        <td>
            <div class="d-flex gap-2 align-items-center">
                <span>${barcode}</span>
                <i class="feather-copy text-secondary"></i>
            </div>
            <input type="hidden" name="variants[${index}][barcode]" value="${barcode}">
        </td>

        <td>
            ${imageHTML}
            <!-- Keep the actual file input for submission -->
            <input type="file" name="variants[${index}][images][]" multiple style="display:none;" />
        </td>

        <td>
            <span class="badge ${status == 1 ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger'}">
                ${status == 1 ? 'Active' : 'Inactive'}
            </span>
            <input type="hidden" name="variants[${index}][is_active]" value="${status}">
        </td>

        <td class="text-end">
            <i class="feather-edit text-primary me-2" onclick="editVariantRow(this)"></i>
            <i class="feather-trash-2 text-danger" onclick="removeVariantRow(this)"></i>
        </td>
    `;

        // Copy the selected files from original input into the new hidden input
        const newFileInput = newRow.querySelector('input[type="file"]');
        if (imageInput.files.length > 0) {
            const dataTransfer = new DataTransfer();
            Array.from(imageInput.files).forEach(file => dataTransfer.items.add(file));
            newFileInput.files = dataTransfer.files;
        }

        // Insert row into table
        tbody.insertBefore(newRow, row);

        // Reset form inputs (except file input, because files are copied already)
        row.querySelectorAll('input, select').forEach(el => {
            if (el.type !== 'file') el.value = '';
        });

        refreshVariantDropdowns();
    }

    /* ================= DELETE ================= */
    function removeVariantRow(btn) {
        if (confirm('Delete this variant?')) {
            btn.closest('tr').remove();
            rebuildVariantIndexes();
        }
    }

    /* ================= EDIT ================= */

    function editVariantRow(btn) {

        const row = btn.closest('tr');
        const tds = row.children;

        const formRow = document.querySelector('#variantTableBody tr.bg-light');

        formRow.querySelector('[name="new_variant[name]"]').value =
            tds[1].innerText.trim() !== '-' ? tds[1].innerText.trim() : '';

        formRow.querySelector('[name="new_variant[sku]"]').value =
            tds[2].innerText.trim() !== '-' ? tds[2].innerText.trim() : '';

        formRow.querySelector('[name="new_variant[variant_type]"]').value =
            tds[3].innerText.trim() !== '-' ? tds[3].innerText.trim() : '';

        formRow.querySelector('[name="new_variant[variant_value]"]').value =
            tds[4].innerText.trim() !== '-' ? tds[4].innerText.trim() : '';

        formRow.querySelector('[name="new_variant[barcode]"]').value =
            tds[5].querySelector('span') ?
            tds[5].querySelector('span').innerText.trim() :
            tds[5].innerText.trim();

        const statusText = tds[7].innerText.trim();
        formRow.querySelector('[name="new_variant[is_active]"]').value =
            statusText === 'Active' ? 1 : 0;

        // remove old row
        row.remove();
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

    function generateCode() {
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const numbers = '0123456789';

        let part1 = '';
        let part2 = '';
        let part3 = '';

        for (let i = 0; i < 4; i++) {
            part1 += letters.charAt(Math.floor(Math.random() * letters.length));
        }

        for (let i = 0; i < 3; i++) {
            part2 += numbers.charAt(Math.floor(Math.random() * numbers.length));
        }

        for (let i = 0; i < 3; i++) {
            part3 += letters.charAt(Math.floor(Math.random() * letters.length));
        }

        return `${part1}-${part2}${part3}`;
    }

    function setItemAndBarcodeCode() {
        const code = generateCode();

        const itemCodeInput = document.getElementById('item_code');
        const barcodeInput = document.getElementById('barcode_no');

        if (itemCodeInput) itemCodeInput.value = code;
        if (barcodeInput) barcodeInput.value = code;
    }

    document.addEventListener('DOMContentLoaded', function() {
        setItemAndBarcodeCode();
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

</script>

<script>
    function refreshVariantDropdowns() {

        let variants = [];

        document.querySelectorAll('.variant-name').forEach(el => {
            if (el.value.trim() !== '') {
                variants.push(el.value.trim());
            }
        });

        document.querySelectorAll('.stock-variant,.price-variant').forEach(select => {

            let selected = select.value;

            select.innerHTML = '<option value="">Select Variant</option>';

            variants.forEach(name => {
                let opt = document.createElement('option');
                opt.value = name; // TEMP VALUE
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


    function previewThumbnail(input) {
        const preview = document.getElementById('thumbnailPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }


    function openBarcodeModal() {
        const barcodeValue = document.getElementById('barcode_no').value.trim();

        if (!barcodeValue) {
            alert('Please enter a barcode number');
            return;
        }

        JsBarcode("#barcodePreview", barcodeValue, {
            format: "CODE128"
            , width: 2
            , height: 60
            , displayValue: true
        });

        document.getElementById('barcodeText').innerText = barcodeValue;
        document.getElementById('barcodeModal').style.display = 'flex';
    }

    function closeBarcodeModal() {
        document.getElementById('barcodeModal').style.display = 'none';
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
        const svg = document.getElementById('barcodePreview');
        const svgData = new XMLSerializer().serializeToString(svg);
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            const link = document.createElement('a');
            link.download = 'barcode.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        };

        img.src = 'data:image/svg+xml;base64,' + btoa(svgData);
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


    /* CUSTOM MODAL */
    .custom-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }

    .custom-modal-content {
        background: #fff;
        width: 350px;
        border-radius: 8px;
        padding: 15px;
        animation: fadeIn 0.2s ease-in-out;
    }

    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
    }

    .custom-modal-body {
        text-align: center;
        padding: 15px 0;
    }

    .custom-modal-footer {
        display: flex;
        justify-content: space-between;
        gap: 8px;
    }

    .close-btn {
        cursor: pointer;
        font-size: 22px;
    }

    .barcode-text {
        font-size: 12px;
        color: #777;
    }

    @keyframes fadeIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

</style>
@endsection
