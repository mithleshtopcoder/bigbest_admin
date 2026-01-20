<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\Admin\ConfigurationSettingsController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeManagement\AttendanceController;
use App\Http\Controllers\Admin\EmployeeManagement\HolidayController;
use App\Http\Controllers\Admin\EmployeeManagement\LeaveController;
use App\Http\Controllers\Admin\EmployeeManagement\PayrollController;
use App\Http\Controllers\Admin\EmployeeManagement\SalaryStructureController;
use App\Http\Controllers\Admin\EmployeeManagement\ShiftAssignmentController;
use App\Http\Controllers\Admin\EmployeeManagement\ShiftController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\HoldResumeBillController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\LogsAuditController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductStockController;
use App\Http\Controllers\Admin\ProductMasterController;
use App\Http\Controllers\Admin\ProductPriceController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\RecruitmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReturnsRefundsController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\UnitAttributeController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewOrder\NewOrderController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OptionMasterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Kreait\Firebase\Factory;

Route::middleware('auth')->group(function () {



    Route::get('/firebase-test', function () {
        $credentials = config('firebase.projects.app.credentials.file');

        if ($credentials && !str_starts_with($credentials, DIRECTORY_SEPARATOR)) {
            $credentials = base_path($credentials);
        }

        if (!$credentials) {
            $credentials = storage_path('app/firebase/firebase-adminsdk.json');
        }

        if (!$credentials || !file_exists($credentials)) {
            return response()->json([
                'error' => 'Firebase credentials file not found',
                'path' => $credentials,
            ], 500);
        }

        $factory = (new Factory)->withServiceAccount($credentials);
        $firestore = $factory->createFirestore();

        return 'Firebase Connected Successfully';
    });

    

    // ==================== BASIC ROUTES ====================
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/blank', [ProfileController::class, 'blank'])->name('blank');
    Route::get('/blank/bills', [ProfileController::class, 'getBills'])->name('blank.bills');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== NEW ORDER ROUTES ====================
    Route::prefix('new-order')->group(function () {
        // Static routes first
        Route::get('/pos/create', [NewOrderController::class, 'create'])->name('new-order.pos.create');
        Route::post('/pos/store', [NewOrderController::class, 'store'])->name('new-order.pos.store');
        Route::get('/search-customers', [NewOrderController::class, 'searchCustomers'])->name('new-order.search-customers');
        Route::get('/search-products', [NewOrderController::class, 'searchProducts'])->name('new-order.search-products');
        Route::post('/payment/verify', [NewOrderController::class, 'verify'])->name('razorpay.verify');
        Route::post('/payment/webhook', [NewOrderController::class, 'webhook'])->name('razorpay.webhook');
        Route::post('/{order}/refund', [NewOrderController::class, 'refund'])->name('orders.refund');
        Route::post('/online/{id}/accept', [NewOrderController::class, 'accept'])->name('new-order.online.accept');
        Route::post('/online/{id}/reject', [NewOrderController::class, 'reject'])->name('new-order.online.reject');
        Route::get('/pos/orders/datatable', [NewOrderController::class, 'datatable'])->name('pos.orders.datatable');
        Route::get('/online/datatable', [NewOrderController::class, 'datatableonline'])->name('online.orders.datatable');
        Route::get('/{order}/invoice', [NewOrderController::class, 'invoice'])->name('new-order.invoice');
        Route::get('/pos/resume/{order}', [NewOrderController::class, 'resume'])->name('new-order.resume');
        Route::get('/pos/{order}/edit', [NewOrderController::class, 'edit'])->name('new-order.pos.edit');
        
        // Dynamic routes last
        Route::get('/{type}/list', [NewOrderController::class, 'index'])->name('new-order.index');
        Route::get('/{type}/{id}/view', [NewOrderController::class, 'show'])->name('new-order.view');
        Route::get('/{type}/{id}/viewonline', [NewOrderController::class, 'showonline'])->name('new-order.viewonline');

        });

    // ==================== ORDER STATUS ROUTES ====================
    Route::prefix('order-status')->group(function () {
        Route::get('/{status}/list', [OrderStatusController::class, 'index'])->name('order-status.index');
    });
    Route::patch('/orders/{order}/status', [OrderStatusController::class, 'updateStatus'])->name('orders.update-status');

    // ==================== PRODUCT MANAGEMENT ROUTES ====================
    Route::prefix('manage-product')->group(function () {
        // Product Master
        Route::prefix('product-master')->group(function () {
            Route::get('/list', [ProductMasterController::class, 'index'])->name('manage-product.product-master.index');
            Route::get('/create', [ProductMasterController::class, 'create'])->name('manage-product.product-master.create');
            Route::get('/edit/{id}', [ProductMasterController::class, 'edit'])->name('manage-product.product-master.edit');
            Route::post('/store', [ProductMasterController::class, 'store'])->name('manage-product.product-master.store');
            Route::put('/update/{id}', [ProductMasterController::class, 'update'])->name('manage-product.product-master.update');
            Route::put('/update-info/{id}', [ProductMasterController::class, 'updateInfo'])->name('manage-product.product-master.updateinfo');
            Route::delete('/delete/{id}', [ProductMasterController::class, 'destroy'])->name('manage-product.product-master.destroy');
            Route::get('/datatable', [ProductMasterController::class, 'datatable'])->name('manage-product.product-master.datatable');
            Route::put('/update-item-info/{id}', [ProductMasterController::class, 'updateItemInfo']) ->name('manage-product.product-master.update-item-info');
            // Product Variants // ok done
            Route::prefix('product/{product_id}')->group(function () {
                Route::get('/variants', [ProductVariantController::class, 'index'])->name('manage-product.product-master.variants.index');
                Route::post('/variants/store', [ProductVariantController::class, 'store'])->name('manage-product.product-master.variants.store');
                Route::put('/variants/update/{id}', [ProductVariantController::class, 'update'])->name('manage-product.product-master.variants.update');
                Route::delete('/variants/delete/{id}', [ProductVariantController::class, 'delete'])->name('manage-product.product-master.variants.delete');
                
                // Variant Images
                Route::prefix('variants/{variant_id}')->group(function () {
                    Route::get('/images', [ProductImageController::class, 'index'])->name('manage-product.product-master.variants.images.index');
                    Route::post('/images/store', [ProductImageController::class, 'store'])->name('manage-product.product-master.variants.images.store');
                    Route::put('/images/{id}/set-primary', [ProductImageController::class, 'setPrimary'])->name('manage-product.product-master.variants.images.set-primary');
                    Route::delete('/images/{id}/delete', [ProductImageController::class, 'delete'])->name('manage-product.product-master.variants.images.delete');
                });
            });
            Route::prefix('product/{product_id}')->group(function () {
                Route::get('/prices', [ProductPriceController::class, 'index'])->name('manage-product.product-master.prices.index');
                Route::post('/prices/store', [ProductPriceController::class, 'store'])->name('manage-product.product-master.prices.store');
                Route::put('/prices/update/{id}', [ProductPriceController::class, 'update'])->name('manage-product.product-master.prices.update');
                Route::delete('/prices/delete/{id}', [ProductPriceController::class, 'delete'])->name('manage-product.product-master.prices.delete');
            });
            Route::prefix('product/{product_id}')->group(function () {
                Route::get('/stocks', [ProductStockController::class, 'index'])->name('manage-product.product-master.stocks.index');
                Route::post('/stocks/store', [ProductStockController::class, 'store'])->name('manage-product.product-master.stocks.store');
                Route::put('/stocks/update/{id}', [ProductStockController::class, 'update'])->name('manage-product.product-master.stocks.update');
                Route::delete('/stocks/delete/{id}', [ProductStockController::class, 'delete'])->name('manage-product.product-master.stocks.delete');
                Route::get('/variants/{variant_id}/stocks', [ProductStockController::class, 'variantStocks'])->name('manage-product.product-master.stocks.variant');
            });
            Route::prefix('product/{product_id}')->group(function () {
                Route::get('/discounts', [ProductMasterController::class, 'discounts'])->name('manage-product.product-master.discounts.index');
            });
        });

        // Category
        Route::prefix('category')->group(function () {
            Route::get('/list', [CategoryController::class, 'index'])->name('manage-product.category.index');
            Route::get('/create', [CategoryController::class, 'create'])->name('manage-product.category.create');
            Route::post('/store', [CategoryController::class, 'store'])->name('manage-product.category.store');
            Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('manage-product.category.edit');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('manage-product.category.update');
            Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('manage-product.category.destroy');
            Route::get('/datatable', [CategoryController::class, 'datatable'])
            ->name('manage-product.category.datatable');
            });

        // Sub Category
        Route::prefix('sub-category')->group(function () {
            Route::get('/list', [SubCategoryController::class, 'index'])->name('manage-product.sub-category.index');
            Route::get('/create', [SubCategoryController::class, 'create'])->name('manage-product.sub-category.create');
            Route::post('/store', [SubCategoryController::class, 'store'])->name('manage-product.sub-category.store');
            Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('manage-product.sub-category.edit');
            Route::put('/update/{id}', [SubCategoryController::class, 'update'])->name('manage-product.sub-category.update');
            Route::delete('/delete/{id}', [SubCategoryController::class, 'destroy'])->name('manage-product.sub-category.destroy');
            Route::get('/datatable', [SubCategoryController::class, 'datatable'])
            ->name('manage-product.sub-category.datatable');

        
            });

        // Unit Attribute
        Route::prefix('unit-attribute')->group(function () {
            Route::get('/list', [UnitAttributeController::class, 'index'])->name('manage-product.unit-attribute.index');
            Route::get('/create', [UnitAttributeController::class, 'create'])->name('manage-product.unit-attribute.create');
        });

        // Brands
        Route::prefix('brands')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('manage-product.brands.index');
            Route::get('/create', [BrandController::class, 'create'])->name('manage-product.brands.create');
            Route::post('/', [BrandController::class, 'store'])->name('manage-product.brands.store');
            Route::get('/{id}/edit', [BrandController::class, 'edit'])->name('manage-product.brands.edit');
            Route::put('/{id}', [BrandController::class, 'update'])->name('manage-product.brands.update');
            Route::delete('/{id}', [BrandController::class, 'destroy'])->name('manage-product.brands.destroy');
        Route::get('/datatable', [BrandController::class, 'datatable'])
    ->name('manage-product.brands.datatable');
        
            });

        // Offers
        Route::prefix('offer')->group(function () {
            Route::get('/{type}/list', [OfferController::class, 'index'])->name('manage-product.offer.index');
            Route::get('/{type}/create', [OfferController::class, 'create'])->name('manage-product.offer.create');
            Route::post('/{type}/store', [OfferController::class, 'store'])->name('manage-product.offer.store');
            Route::get('/{type}/edit/{id}', [OfferController::class, 'edit'])->name('manage-product.offer.edit');
            Route::put('/{type}/{id}/update', [OfferController::class, 'update'])->name('manage-product.offer.update');
            Route::delete('/{type}/{id}/destroy', [OfferController::class, 'destroy'])->name('manage-product.offer.destroy');
            Route::get('/get-subcategories/{categoryId}', [OfferController::class, 'getSubCategories'])->name('manage-product.offer.get-subcategories');
            Route::get('/get-products/{categoryId}/{subCategoryId?}', [OfferController::class, 'getProducts'])->name('manage-product.offer.get.products');
            Route::get('/offer/{type}/datatable', [OfferController::class, 'datatable'])->name('manage-product.offer.datatable');
        });
    });

    // ==================== INVENTORY MANAGEMENT ROUTES ====================
    Route::prefix('inventory-management')->group(function () {
        Route::get('/store-wise-stock', [InventoryController::class, 'storeWiseStock'])->name('inventory-management.store-wise-stock');
        Route::get('/store-wise-stock/{product}/stocks', [InventoryController::class, 'storeWiseStockProductStocks'])
            ->name('inventory-management.store-wise-stock.stocks');
        Route::get('/stock-in-stock-out', [InventoryController::class, 'stockInStockOut'])->name('inventory-management.stock-in-stock-out');
        Route::get('/stock-in-stock-out/create', [InventoryController::class, 'createStockInStockOut'])->name('inventory-management.stock-in-stock-out.create');
        Route::post('/stock-in-stock-out', [InventoryController::class, 'storeStockInStockOut'])->name('inventory-management.stock-in-stock-out.store');
        Route::get('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('inventory-management.stock-adjustment');
        Route::get('/stock-adjustment/create', [InventoryController::class, 'createStockAdjustment'])->name('inventory-management.stock-adjustment.create');
        Route::post('/stock-adjustment', [InventoryController::class, 'storeStockAdjustment'])->name('inventory-management.stock-adjustment.store');
        Route::get('/stock-adjustment/{id}/view', [InventoryController::class, 'viewStockAdjustment'])->name('inventory-management.stock-adjustment.view');
        Route::post('/stock-adjustment/{id}/approve', [InventoryController::class, 'approveStockAdjustment'])->name('inventory-management.stock-adjustment.approve');
        Route::post('/stock-adjustment/{id}/reject', [InventoryController::class, 'rejectStockAdjustment'])->name('inventory-management.stock-adjustment.reject');
        Route::get('/stock-transfer/products', [InventoryController::class, 'getProducts'])->name('inventory-management.stock-transfer.products');
        Route::get('/search-variants', [InventoryController::class, 'searchProductVariants'])->name('inventory-management.search-variants');

        // Stock Transfer
        Route::prefix('stock-transfer')->group(function () {
            // Transfer Request
            Route::get('/transfer-request', [TransferController::class, 'request'])->name('inventory-management.stock-transfer.transfer-request');
            Route::get('/transfer-request/create', [TransferController::class, 'createRequest'])->name('inventory-management.stock-transfer.transfer-request.create');
            Route::post('/transfer-request', [TransferController::class, 'storeRequest'])->name('inventory-management.stock-transfer.transfer-request.store');
            Route::get('/transfer-request/{id}/view', [TransferController::class, 'viewRequest'])->name('inventory-management.stock-transfer.transfer-request.view');
            Route::get('/transfer-request/{id}/edit', [TransferController::class, 'editRequest'])->name('inventory-management.stock-transfer.transfer-request.edit');
            Route::put('/transfer-request/{id}', [TransferController::class, 'updateRequest'])->name('inventory-management.stock-transfer.transfer-request.update');
            Route::post('/transfer-request/{id}/cancel', [TransferController::class, 'cancelRequest'])->name('inventory-management.stock-transfer.transfer-request.cancel');

            // Approval
            Route::get('/approval', [TransferController::class, 'approval'])->name('inventory-management.stock-transfer.approval');
            Route::get('/approval/{id}/view', [TransferController::class, 'viewApproval'])->name('inventory-management.stock-transfer.approval.view');
            Route::post('/approval/{id}/approve', [TransferController::class, 'approveTransfer'])->name('inventory-management.stock-transfer.approval.approve');
            Route::post('/approval/{id}/reject', [TransferController::class, 'rejectTransfer'])->name('inventory-management.stock-transfer.approval.reject');

            // In-Transit
            Route::get('/in-transit', [TransferController::class, 'inTransit'])->name('inventory-management.stock-transfer.in-transit');
            Route::get('/in-transit/{id}/view', [TransferController::class, 'viewInTransit'])->name('inventory-management.stock-transfer.in-transit.view');
            Route::post('/in-transit/{id}/mark', [TransferController::class, 'markInTransit'])->name('inventory-management.stock-transfer.in-transit.mark');
            Route::post('/in-transit/{id}/receive', [TransferController::class, 'receiveTransfer'])->name('inventory-management.stock-transfer.in-transit.receive');

            // Product Variant Search
            Route::get('/search-variants', [TransferController::class, 'searchProductVariants'])->name('inventory-management.stock-transfer.search-variants');
        });
    });

    // ==================== PARTY MANAGEMENT ROUTES ====================
    Route::prefix('party-management')->group(function () {
        // Customers
        Route::prefix('customers')->group(function () {
            Route::get('/list', [CustomerController::class, 'index'])->name('party-management.customers.list');
            Route::get('/list/get', [CustomerController::class, 'getCustomers'])->name('customers.get');
            Route::get('/list/create', [CustomerController::class, 'create'])->name('party-management.customers.list.create');
            Route::get('/addresses', [CustomerController::class, 'addresses'])->name('party-management.customers.addresses');
            Route::get('/addresses/create', [CustomerController::class, 'createAddress'])->name('party-management.customers.addresses.create');
            Route::post('/addresses', [CustomerController::class, 'storeAddress'])->name('party-management.customers.addresses.store');
            Route::get('/addresses/{address}/view', [CustomerController::class, 'viewAddress'])->name('party-management.customers.addresses.view');
            Route::put('/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('party-management.customers.addresses.update');
            Route::delete('/addresses/{address}', [CustomerController::class, 'deleteAddress'])->name('party-management.customers.addresses.delete');
            Route::get('/{customer}', [CustomerController::class, 'show'])->name('party-management.customers.show');
            Route::get('/wallet', [CustomerController::class, 'wallet'])->name('party-management.customers.wallet');
            Route::get('/wallet/create', [CustomerController::class, 'createWallet'])->name('party-management.customers.wallet.create');
            Route::get('/loyalty-points', [CustomerController::class, 'loyaltyPoints'])->name('party-management.customers.loyalty-points');
            Route::get('/loyalty-points/create', [CustomerController::class, 'createLoyaltyPoints'])->name('party-management.customers.loyalty-points.create');
            Route::get('/feedback', [CustomerController::class, 'feedback'])->name('party-management.customers.feedback');
        });
        

        // Suppliers & Vendors
        Route::prefix('suppliers-vendors')->group(function () {
            Route::get('/{type}/list', [SupplierController::class, 'index'])->name('party-management.suppliers-vendors.index');
            Route::get('/{type}/list/data', [SupplierController::class, 'getSuppliers'])->name('party-management.suppliers-vendors.data');
            Route::get('/{type}/create', [SupplierController::class, 'create'])->name('party-management.suppliers-vendors.create');
            Route::post('/{type}', [SupplierController::class, 'store'])->name('party-management.suppliers-vendors.store');
            Route::get('/{type}/{id}/view', [SupplierController::class, 'show'])->name('party-management.suppliers-vendors.show');
            Route::get('/{type}/{id}/edit', [SupplierController::class, 'edit'])->name('party-management.suppliers-vendors.edit');
            Route::put('/{type}/{id}', [SupplierController::class, 'update'])->name('party-management.suppliers-vendors.update');
            Route::delete('/{type}/{id}', [SupplierController::class, 'destroy'])->name('party-management.suppliers-vendors.destroy');
        });
    });

    // ==================== PROCUREMENT ROUTES ====================
    Route::prefix('procurement')->group(function () {
        // Purchase Orders
        Route::get('/purchase-orders', [PurchaseController::class, 'index'])->name('procurement.purchase-orders');
        Route::get('/purchase-orders/data', [PurchaseController::class, 'getPurchaseOrders'])->name('procurement.purchase-orders.data');
        Route::get('/purchase-orders/create', [PurchaseController::class, 'createPo'])->name('procurement.purchase-orders.create');
        Route::post('/purchase-orders', [PurchaseController::class, 'storePo'])->name('procurement.purchase-orders.store');
        Route::get('/purchase-orders/{id}/view', [PurchaseController::class, 'view'])->name('procurement.purchase-orders.view');
        Route::get('/purchase-orders/{id}/items', [PurchaseController::class, 'getPoItems'])->name('procurement.purchase-orders.items');
        Route::post('/purchase-orders/{id}/approve', [PurchaseController::class, 'approvePo'])->name('procurement.purchase-orders.approve');
        Route::post('/purchase-orders/{id}/cancel', [PurchaseController::class, 'cancelPo'])->name('procurement.purchase-orders.cancel');
        Route::get('/purchase-orders/search-variants', [PurchaseController::class, 'searchProductVariants'])->name('procurement.purchase-orders.search-variants');
        Route::get('/purchase-orders/datatable', [PurchaseController::class, 'datatable'])->name('purchase-orders.datatable');

        // GRN (Purchase Receipts)
        Route::get('/grn', [PurchaseController::class, 'grn'])->name('procurement.grn');
        Route::get('/grn/data', [PurchaseController::class, 'getGrn'])->name('procurement.grn.data');
        Route::get('/grn/create', [PurchaseController::class, 'createGrn'])->name('procurement.grn.create');
        Route::post('/grn', [PurchaseController::class, 'storeGrn'])->name('procurement.grn.store');

        // Purchase Invoices
        Route::get('/purchase-invoices', [PurchaseController::class, 'invoices'])->name('procurement.purchase-invoices');
        Route::get('/purchase-invoices/data', [PurchaseController::class, 'getInvoices'])->name('procurement.purchase-invoices.data');
        Route::get('/purchase-invoices/create', [PurchaseController::class, 'createInvoice'])->name('procurement.purchase-invoices.create');
        Route::post('/purchase-invoices', [PurchaseController::class, 'storeInvoice'])->name('procurement.purchase-invoices.store');

        // Purchase Returns
        Route::get('/purchase-returns', [PurchaseController::class, 'returns'])->name('procurement.purchase-returns');
        Route::get('/purchase-returns/products', [PurchaseController::class, 'getProductsByStore'])->name('procurement.purchase-returns.products');
        Route::get('/purchase-returns/grn-items', [PurchaseController::class, 'getGrnItems'])->name('procurement.purchase-returns.grn-items');
        Route::get('/purchase-returns/data', [PurchaseController::class, 'getReturns'])->name('procurement.purchase-returns.data');
        Route::get('/purchase-returns/create', [PurchaseController::class, 'createReturn'])->name('procurement.purchase-returns.create');
        Route::post('/purchase-returns', [PurchaseController::class, 'storeReturn'])->name('procurement.purchase-returns.store');
    });

    // ==================== STORE MANAGEMENT ROUTES ====================
    Route::prefix('store-management')->group(function () {
        // Manage Store
        Route::get('/manage-store', [StoreController::class, 'index'])->name('store-management.manage-store');
        Route::get('/manage-store/data', [StoreController::class, 'getStores'])->name('store-management.manage-store.data');
        Route::get('/manage-store/create', [StoreController::class, 'create'])->name('store-management.manage-store.create');
        Route::post('/manage-store', [StoreController::class, 'store'])->name('store-management.manage-store.store');
        Route::get('/manage-store/{id}/edit', [StoreController::class, 'edit'])->name('store-management.manage-store.edit');
        Route::put('/manage-store/{id}', [StoreController::class, 'update'])->name('store-management.manage-store.update');
        Route::post('/manage-store/{id}/assign-users', [StoreController::class, 'assignUsers'])->name('store-management.manage-store.assign-users');
        Route::post('/manage-store/{id}/add-user', [StoreController::class, 'addUser'])->name('store-management.manage-store.add-user');
        Route::delete('/manage-store/{storeId}/remove-user/{userId}', [StoreController::class, 'removeUser'])->name('store-management.manage-store.remove-user');
        Route::delete('/manage-store/{id}', [StoreController::class, 'destroy'])->name('store-management.manage-store.destroy');

        // Service Radius
        Route::get('/service-radius', [StoreController::class, 'serviceRadius'])->name('store-management.service-radius');
        Route::get('/service-radius/data', [StoreController::class, 'getServiceAreas'])->name('store-management.service-radius.data');
        Route::get('/service-radius/create', [StoreController::class, 'createServiceArea'])->name('store-management.service-radius.create');
        Route::post('/service-radius', [StoreController::class, 'storeServiceArea'])->name('store-management.service-radius.store');
        Route::get('/service-radius/{id}/edit', [StoreController::class, 'editServiceArea'])->name('store-management.service-radius.edit');
        Route::put('/service-radius/{id}', [StoreController::class, 'updateServiceArea'])->name('store-management.service-radius.update');
        Route::delete('/service-radius/{id}', [StoreController::class, 'destroyServiceArea'])->name('store-management.service-radius.destroy');
    });

    // ==================== EMPLOYEE MANAGEMENT ROUTES ====================
    Route::prefix('employee-management')->group(function () {
        // Manage Employee
        Route::get('/manage-employee', [EmployeeController::class, 'index'])->name('employee-management.manage-employee');
        Route::get('/manage-employee/data', [EmployeeController::class, 'getEmployees'])->name('employee-management.manage-employee.data');
        Route::get('/manage-employee/create', [EmployeeController::class, 'create'])->name('employee-management.manage-employee.create');
        Route::post('/manage-employee', [EmployeeController::class, 'store'])->name('employee-management.manage-employee.store');
        Route::get('/manage-employee/{uuid}/edit', [EmployeeController::class, 'edit'])->name('employee-management.manage-employee.edit');
        Route::put('/manage-employee/{uuid}', [EmployeeController::class, 'update'])->name('employee-management.manage-employee.update');
        Route::post('/manage-employee/{uuid}/status', [EmployeeController::class, 'updateStatus'])->name('employee-management.manage-employee.status');
        Route::delete('/manage-employee/{uuid}', [EmployeeController::class, 'destroy'])->name('employee-management.manage-employee.destroy');
        Route::get('/manage-employee/designations', [EmployeeController::class, 'getDesignations'])->name('employee-management.manage-employee.designations');
        Route::post('/manage-employee/{uuid}/memo', [EmployeeController::class, 'updateMemo'])->name('employee-management.manage-employee.memo');

        // Educational Info
        Route::prefix('educational-info/{employee_uuid}')->group(function () {
            Route::post('/store', [EmployeeController::class, 'storeEducationalInfo'])->name('employee-management.educational-info.store');
            Route::put('/update/{id}', [EmployeeController::class, 'updateEducationalInfo'])->name('employee-management.educational-info.update');
            Route::delete('/delete/{id}', [EmployeeController::class, 'destroyEducationalInfo'])->name('employee-management.educational-info.destroy');
        });

        // Experience
        Route::prefix('experience/{employee_uuid}')->group(function () {
            Route::post('/store', [EmployeeController::class, 'storeExperience'])->name('employee-management.experience.store');
            Route::put('/update/{id}', [EmployeeController::class, 'updateExperience'])->name('employee-management.experience.update');
            Route::delete('/delete/{id}', [EmployeeController::class, 'destroyExperience'])->name('employee-management.experience.destroy');
        });

        // Documents
        Route::prefix('documents/{employee_uuid}')->group(function () {
            Route::post('/store', [EmployeeController::class, 'storeDocument'])->name('employee-management.documents.store');
            Route::put('/update/{id}', [EmployeeController::class, 'updateDocument'])->name('employee-management.documents.update');
            Route::delete('/delete/{id}', [EmployeeController::class, 'destroyDocument'])->name('employee-management.documents.destroy');
        });

        // Salary Structure
        Route::prefix('salary-structure')->group(function () {
            Route::get('/list', [SalaryStructureController::class, 'employeeList'])->name('employee-management.salary-structure.list');
            Route::get('/list/data', [SalaryStructureController::class, 'getEmployees'])->name('employee-management.salary-structure.list.data');
            Route::get('/{uuid}', [SalaryStructureController::class, 'index'])->name('employee-management.salary-structure.index');
            Route::get('/{uuid}/data', [SalaryStructureController::class, 'getSalaryStructures'])->name('employee-management.salary-structure.data');
            Route::get('/{uuid}/create', [SalaryStructureController::class, 'create'])->name('employee-management.salary-structure.create');
            Route::post('/{uuid}/store', [SalaryStructureController::class, 'storeSalaryStructure'])->name('employee-management.salary-structure.store');
            Route::get('/{uuid}/edit/{id}', [SalaryStructureController::class, 'editSalaryStructure'])->name('employee-management.salary-structure.edit');
            Route::put('/{uuid}/update/{id}', [SalaryStructureController::class, 'updateSalaryStructure'])->name('employee-management.salary-structure.update');
            Route::post('/{uuid}/status/{id}', [SalaryStructureController::class, 'updateStatus'])->name('employee-management.salary-structure.status');
            Route::delete('/{uuid}/delete/{id}', [SalaryStructureController::class, 'destroySalaryStructure'])->name('employee-management.salary-structure.destroy');
        });

        Route::get('/store-wise-mapping', [EmployeeController::class, 'storeWiseMapping'])->name('employee-management.store-wise-mapping');
        Route::get('/store-wise-mapping/create', [EmployeeController::class, 'createStoreWiseMapping'])->name('employee-management.store-wise-mapping.create');
        Route::get('/salary', [EmployeeController::class, 'salary'])->name('employee-management.salary');
        Route::get('/salary/create', [EmployeeController::class, 'createSalary'])->name('employee-management.salary.create');
        Route::get('/recruitment', [EmployeeController::class, 'recruitment'])->name('employee-management.recruitment');
        Route::get('/recruitment/create', [EmployeeController::class, 'createRecruitment'])->name('employee-management.recruitment.create');
        Route::get('/training', [EmployeeController::class, 'training'])->name('employee-management.training');
        Route::get('/training/create', [EmployeeController::class, 'createTraining'])->name('employee-management.training.create');

        // Attendance
        Route::prefix('attendance')->group(function () {
            Route::get('/', [AttendanceController::class, 'index'])->name('employee-management.attendance');
            Route::get('/data', [AttendanceController::class, 'getAttendances'])->name('employee-management.attendance.data');
            Route::get('/create', [AttendanceController::class, 'create'])->name('employee-management.attendance.create');
            Route::post('/store', [AttendanceController::class, 'store'])->name('employee-management.attendance.store');
            Route::get('/edit/{id}', [AttendanceController::class, 'edit'])->name('employee-management.attendance.edit');
            Route::put('/update/{id}', [AttendanceController::class, 'update'])->name('employee-management.attendance.update');
            Route::delete('/delete/{id}', [AttendanceController::class, 'destroy'])->name('employee-management.attendance.destroy');
            Route::post('/punch-in', [AttendanceController::class, 'punchIn'])->name('employee-management.attendance.punch-in');
            Route::post('/punch-out', [AttendanceController::class, 'punchOut'])->name('employee-management.attendance.punch-out');
        });

        // Leave
        Route::prefix('leave')->group(function () {
            Route::get('/', [LeaveController::class, 'index'])->name('employee-management.leave');
            Route::get('/data', [LeaveController::class, 'getLeaves'])->name('employee-management.leave.data');
            Route::get('/create', [LeaveController::class, 'create'])->name('employee-management.leave.create');
            Route::post('/store', [LeaveController::class, 'store'])->name('employee-management.leave.store');
            Route::get('/edit/{id}', [LeaveController::class, 'edit'])->name('employee-management.leave.edit');
            Route::put('/update/{id}', [LeaveController::class, 'update'])->name('employee-management.leave.update');
            Route::post('/status/{id}', [LeaveController::class, 'updateStatus'])->name('employee-management.leave.status');
            Route::delete('/delete/{id}', [LeaveController::class, 'destroy'])->name('employee-management.leave.destroy');
        });

        // Holiday
        Route::prefix('holiday')->group(function () {
            Route::get('/', [HolidayController::class, 'index'])->name('employee-management.holiday');
            Route::get('/data', [HolidayController::class, 'getHolidays'])->name('employee-management.holiday.data');
            Route::get('/create', [HolidayController::class, 'create'])->name('employee-management.holiday.create');
            Route::post('/store', [HolidayController::class, 'store'])->name('employee-management.holiday.store');
            Route::get('/edit/{id}', [HolidayController::class, 'edit'])->name('employee-management.holiday.edit');
            Route::put('/update/{id}', [HolidayController::class, 'update'])->name('employee-management.holiday.update');
            Route::post('/status/{id}', [HolidayController::class, 'updateStatus'])->name('employee-management.holiday.status');
            Route::delete('/delete/{id}', [HolidayController::class, 'destroy'])->name('employee-management.holiday.destroy');
        });

        // Shift
        Route::prefix('shift')->group(function () {
            Route::get('/', [ShiftController::class, 'index'])->name('employee-management.shift');
            Route::get('/data', [ShiftController::class, 'getShifts'])->name('employee-management.shift.data');
            Route::get('/create', [ShiftController::class, 'create'])->name('employee-management.shift.create');
            Route::post('/store', [ShiftController::class, 'store'])->name('employee-management.shift.store');
            Route::get('/edit/{id}', [ShiftController::class, 'edit'])->name('employee-management.shift.edit');
            Route::put('/update/{id}', [ShiftController::class, 'update'])->name('employee-management.shift.update');
            Route::post('/status/{id}', [ShiftController::class, 'updateStatus'])->name('employee-management.shift.status');
            Route::delete('/delete/{id}', [ShiftController::class, 'destroy'])->name('employee-management.shift.destroy');
        });

        // Shift Assignment
        Route::prefix('shift-assignment')->group(function () {
            Route::get('/', [ShiftAssignmentController::class, 'index'])->name('employee-management.shift-assignment');
            Route::get('/data', [ShiftAssignmentController::class, 'getShiftAssignments'])->name('employee-management.shift-assignment.data');
            Route::get('/create', [ShiftAssignmentController::class, 'create'])->name('employee-management.shift-assignment.create');
            Route::post('/store', [ShiftAssignmentController::class, 'store'])->name('employee-management.shift-assignment.store');
            Route::get('/edit/{id}', [ShiftAssignmentController::class, 'edit'])->name('employee-management.shift-assignment.edit');
            Route::put('/update/{id}', [ShiftAssignmentController::class, 'update'])->name('employee-management.shift-assignment.update');
            Route::post('/status/{id}', [ShiftAssignmentController::class, 'updateStatus'])->name('employee-management.shift-assignment.status');
            Route::delete('/delete/{id}', [ShiftAssignmentController::class, 'destroy'])->name('employee-management.shift-assignment.destroy');
        });

        // Payroll
        Route::prefix('payroll')->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('employee-management.payroll');
            Route::get('/data', [PayrollController::class, 'getPayrolls'])->name('employee-management.payroll.data');
            Route::get('/create', [PayrollController::class, 'create'])->name('employee-management.payroll.create');
            Route::post('/process', [PayrollController::class, 'processPayroll'])->name('employee-management.payroll.process');
            Route::get('/show/{id}', [PayrollController::class, 'show'])->name('employee-management.payroll.show');
            Route::get('/download-payslip/{id}', [PayrollController::class, 'downloadPayslip'])->name('employee-management.payroll.download-payslip');
            Route::get('/view-payslip/{id}', [PayrollController::class, 'viewPayslip'])->name('employee-management.payroll.view-payslip');
            Route::post('/status/{id}', [PayrollController::class, 'updateStatus'])->name('employee-management.payroll.status');
            Route::delete('/delete/{id}', [PayrollController::class, 'destroy'])->name('employee-management.payroll.destroy');
        });
    });

    // ==================== FINANCE & ACCOUNTING ROUTES ====================
    Route::prefix('finance-accounting')->group(function () {
        Route::get('/store-ledger', [LedgerController::class, 'storeLedger'])->name('finance-accounting.store-ledger');
        Route::get('/customer-ledger', [LedgerController::class, 'customerLedger'])->name('finance-accounting.customer-ledger');
        Route::get('/supplier-ledger', [LedgerController::class, 'supplierLedger'])->name('finance-accounting.supplier-ledger');
        Route::get('/payments-receipts', [LedgerController::class, 'payments'])->name('finance-accounting.payments-receipts');
        Route::get('/payments-receipts/{id}', [LedgerController::class, 'showPayment'])->name('finance-accounting.payments-receipts.show');
        Route::get('/payments-receipts/{id}/download', [LedgerController::class, 'downloadPayment'])->name('finance-accounting.payments-receipts.download');
        Route::get('/payments-receipts/{id}/view', [LedgerController::class, 'viewPayment'])->name('finance-accounting.payments-receipts.view');

        // Expenses
        Route::prefix('expenses')->group(function () {
            Route::get('/', [ExpenseController::class, 'index'])->name('finance-accounting.expenses.index');
            Route::get('/data', [ExpenseController::class, 'getExpenses'])->name('finance-accounting.expenses.data');
            Route::get('/create', [ExpenseController::class, 'create'])->name('finance-accounting.expenses.create');
            Route::post('/', [ExpenseController::class, 'store'])->name('finance-accounting.expenses.store');
            Route::get('/{id}', [ExpenseController::class, 'show'])->name('finance-accounting.expenses.show');
            Route::get('/{id}/edit', [ExpenseController::class, 'edit'])->name('finance-accounting.expenses.edit');
            Route::put('/{id}', [ExpenseController::class, 'update'])->name('finance-accounting.expenses.update');
            Route::delete('/{id}', [ExpenseController::class, 'destroy'])->name('finance-accounting.expenses.destroy');
        });
    });

    // ==================== REPORTS & ANALYTICS ROUTES ====================
    Route::prefix('reports-analytics')->group(function () {
        Route::get('/sales-reports', [ReportController::class, 'salesReport'])->name('reports-analytics.sales-reports');
        Route::get('/pos-reports', [ReportController::class, 'posReport'])->name('reports-analytics.pos-reports');
        Route::get('/app-orders-reports', [ReportController::class, 'appOrdersReport'])->name('reports-analytics.app-orders-reports');
        Route::get('/inventory-report', [ReportController::class, 'inventoryReport'])->name('reports-analytics.inventory-report');
        Route::get('/top-selling-products', [ReportController::class, 'topSelling'])->name('reports-analytics.top-selling-products');
        Route::get('/low-selling-products', [ReportController::class, 'lowSelling'])->name('reports-analytics.low-selling-products');
        Route::get('/expense-report', [ReportController::class, 'expenseReport'])->name('reports-analytics.expense-report');
        Route::get('/employee-sales-report', [ReportController::class, 'employeeSales'])->name('reports-analytics.employee-sales-report');
    });

    // ==================== CMS ROUTES ====================
    Route::prefix('cms')->group(function () {
        Route::get('/about-us', [CMSController::class, 'aboutUs'])->name('cms.about-us');
        Route::get('/contact-us', [CMSController::class, 'contactUs'])->name('cms.contact-us');
        Route::get('/policy-master/{type}', [CMSController::class, 'policyMaster'])->name('cms.policy-master');
        Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class);
        Route::get('/social-media', [CMSController::class, 'socialMedia'])->name('cms.social-media');
        Route::get('/faq', [CMSController::class, 'faq'])->name('cms.faq');
    });

    // ==================== CONFIGURATION SETTINGS ROUTES ====================
    Route::prefix('configuration-settings')->group(function () {
        Route::get('/roles-permissions', [ConfigurationSettingsController::class, 'rolesPermissions'])->name('configuration-settings.roles-permissions');
        Route::get('/company-setup', [ConfigurationSettingsController::class, 'companySetup'])->name('configuration-settings.company-setup');
        Route::post('/company-setup', [ConfigurationSettingsController::class, 'storeCompanySetup'])->name('configuration-settings.company-setup.store');
        Route::get('/payment-methods', [ConfigurationSettingsController::class, 'paymentMethods'])->name('configuration-settings.payment-methods');
        Route::get('/tax-settings', [ConfigurationSettingsController::class, 'taxSettings'])->name('configuration-settings.tax-settings');
    });

    // ==================== LOGS & AUDIT ROUTES ====================
    Route::prefix('logs-audit')->group(function () {
        Route::get('/user-logs', [LogsAuditController::class, 'userLogs'])->name('logs-audit.user-logs');
        Route::get('/user-logs/data', [LogsAuditController::class, 'getUserLogs'])->name('logs-audit.user-logs.data');
        Route::get('/login-history', [LogsAuditController::class, 'loginHistory'])->name('logs-audit.login-history');
        Route::get('/login-history/data', [LogsAuditController::class, 'getLoginHistory'])->name('logs-audit.login-history.data');
        Route::get('/failed-login-history', [LogsAuditController::class, 'failedLoginHistory'])->name('logs-audit.failed-login-history');
        Route::get('/failed-login-history/data', [LogsAuditController::class, 'getFailedLoginHistory'])->name('logs-audit.failed-login-history.data');
    });

    // ==================== PAYMENT ROUTES ====================
    Route::prefix('payments')->group(function () {
        Route::post('/', [PaymentController::class, 'store']);
        Route::get('/{id}', [PaymentController::class, 'show']);
        Route::post('/callback', [PaymentController::class, 'callback'])->withoutMiddleware('csrf');
    });

    // ==================== SUPPORT ROUTES ====================
    Route::prefix('support')->group(function () {
        Route::get('/tickets', [SupportController::class, 'tickets'])->name('support.tickets');
        Route::get('/tickets/data', [SupportController::class, 'getTickets'])->name('support.tickets.data');
        Route::get('/tickets/{id}', [SupportController::class, 'showTicket'])->name('support.tickets.show');
        Route::post('/tickets/{id}/assign', [SupportController::class, 'assignTicket'])->name('support.tickets.assign');
        Route::post('/tickets/{id}/status', [SupportController::class, 'updateStatus'])->name('support.tickets.status');
        Route::post('/tickets/{id}/reply', [SupportController::class, 'replyTicket'])->name('support.tickets.reply');
        Route::get('/faqs', [SupportController::class, 'faqs'])->name('support.faqs');
        Route::get('/system-announcements', [SupportController::class, 'announcements'])->name('support.system-announcements');
    });

    // ==================== RECRUITMENT ROUTES ====================
    Route::prefix('recruitment')->group(function () {
        // Candidates
        Route::get('/candidates', [RecruitmentController::class, 'candidates'])->name('recruitment.candidates');
        Route::get('/candidates/data', [RecruitmentController::class, 'getCandidates'])->name('recruitment.candidates.data');
        Route::get('/candidates/create', [RecruitmentController::class, 'createCandidate'])->name('recruitment.candidates.create');
        Route::post('/candidates', [RecruitmentController::class, 'storeCandidate'])->name('recruitment.candidates.store');
        Route::get('/candidates/{id}/edit', [RecruitmentController::class, 'editCandidate'])->name('recruitment.candidates.edit');
        Route::put('/candidates/{id}', [RecruitmentController::class, 'updateCandidate'])->name('recruitment.candidates.update');
        Route::delete('/candidates/{id}', [RecruitmentController::class, 'destroyCandidate'])->name('recruitment.candidates.destroy');

        // Job Openings
        Route::get('/job-openings', [RecruitmentController::class, 'jobOpenings'])->name('recruitment.job-openings');
        Route::get('/job-openings/data', [RecruitmentController::class, 'getJobOpenings'])->name('recruitment.job-openings.data');
        Route::get('/job-openings/create', [RecruitmentController::class, 'createJobOpening'])->name('recruitment.job-openings.create');
        Route::post('/job-openings', [RecruitmentController::class, 'storeJobOpening'])->name('recruitment.job-openings.store');
        Route::get('/job-openings/{id}/edit', [RecruitmentController::class, 'editJobOpening'])->name('recruitment.job-openings.edit');
        Route::put('/job-openings/{id}', [RecruitmentController::class, 'updateJobOpening'])->name('recruitment.job-openings.update');
        Route::delete('/job-openings/{id}', [RecruitmentController::class, 'destroyJobOpening'])->name('recruitment.job-openings.destroy');

        // Interviews
        Route::get('/interviews', [RecruitmentController::class, 'interviews'])->name('recruitment.interviews');
        Route::get('/interviews/data', [RecruitmentController::class, 'getInterviews'])->name('recruitment.interviews.data');
        Route::get('/interviews/create', [RecruitmentController::class, 'createInterview'])->name('recruitment.interviews.create');
        Route::post('/interviews', [RecruitmentController::class, 'storeInterview'])->name('recruitment.interviews.store');
        Route::get('/interviews/{id}/edit', [RecruitmentController::class, 'editInterview'])->name('recruitment.interviews.edit');
        Route::put('/interviews/{id}', [RecruitmentController::class, 'updateInterview'])->name('recruitment.interviews.update');
        Route::delete('/interviews/{id}', [RecruitmentController::class, 'destroyInterview'])->name('recruitment.interviews.destroy');
    });

    // ==================== ONBOARDING ROUTES ====================
    Route::prefix('onboarding')->group(function () {
        // Process
        Route::get('/process', [OnboardingController::class, 'process'])->name('onboarding.process');
        Route::get('/process/data', [OnboardingController::class, 'getProcesses'])->name('onboarding.process.data');
        Route::get('/process/create', [OnboardingController::class, 'createProcess'])->name('onboarding.process.create');
        Route::post('/process', [OnboardingController::class, 'storeProcess'])->name('onboarding.process.store');
        Route::get('/process/{id}/edit', [OnboardingController::class, 'editProcess'])->name('onboarding.process.edit');
        Route::put('/process/{id}', [OnboardingController::class, 'updateProcess'])->name('onboarding.process.update');
        Route::delete('/process/{id}', [OnboardingController::class, 'destroyProcess'])->name('onboarding.process.destroy');

        // Documents
        Route::get('/documents', [OnboardingController::class, 'documents'])->name('onboarding.documents');
        Route::get('/documents/data', [OnboardingController::class, 'getDocuments'])->name('onboarding.documents.data');
        Route::get('/documents/create', [OnboardingController::class, 'createDocument'])->name('onboarding.documents.create');
        Route::post('/documents', [OnboardingController::class, 'storeDocument'])->name('onboarding.documents.store');
        Route::post('/documents/{id}/status', [OnboardingController::class, 'updateDocumentStatus'])->name('onboarding.documents.update-status');
        Route::delete('/documents/{id}', [OnboardingController::class, 'destroyDocument'])->name('onboarding.documents.destroy');

        // Training
        Route::get('/training', [OnboardingController::class, 'training'])->name('onboarding.training');
        Route::get('/training/data', [OnboardingController::class, 'getTrainingModules'])->name('onboarding.training.data');
        Route::get('/training/create', [OnboardingController::class, 'createTraining'])->name('onboarding.training.create');
        Route::post('/training', [OnboardingController::class, 'storeTraining'])->name('onboarding.training.store');
        Route::get('/training/{id}/edit', [OnboardingController::class, 'editTraining'])->name('onboarding.training.edit');
        Route::put('/training/{id}', [OnboardingController::class, 'updateTraining'])->name('onboarding.training.update');
        Route::delete('/training/{id}', [OnboardingController::class, 'destroyTraining'])->name('onboarding.training.destroy');

        // Training Assignments
        Route::get('/training-assignments', [OnboardingController::class, 'trainingAssignments'])->name('onboarding.training-assignments');
        Route::get('/training-assignments/data', [OnboardingController::class, 'getTrainingAssignments'])->name('onboarding.training-assignments.data');
        Route::get('/training-assignments/create', [OnboardingController::class, 'createTrainingAssignment'])->name('onboarding.training-assignments.create');
        Route::post('/training-assignments', [OnboardingController::class, 'storeTrainingAssignment'])->name('onboarding.training-assignments.store');
        Route::get('/training-assignments/{id}/edit', [OnboardingController::class, 'editTrainingAssignment'])->name('onboarding.training-assignments.edit');
        Route::put('/training-assignments/{id}', [OnboardingController::class, 'updateTrainingAssignment'])->name('onboarding.training-assignments.update');
        Route::delete('/training-assignments/{id}', [OnboardingController::class, 'destroyTrainingAssignment'])->name('onboarding.training-assignments.destroy');

        // Checklist
        Route::get('/checklist', [OnboardingController::class, 'checklist'])->name('onboarding.checklist');
        Route::get('/checklist/data', [OnboardingController::class, 'getChecklists'])->name('onboarding.checklist.data');
        Route::get('/checklist/{id}', [OnboardingController::class, 'getChecklist'])->name('onboarding.checklist.show');
        Route::post('/checklist', [OnboardingController::class, 'storeChecklist'])->name('onboarding.checklist.store');
        Route::put('/checklist/{id}', [OnboardingController::class, 'updateChecklist'])->name('onboarding.checklist.update');
        Route::post('/checklist/{id}/status', [OnboardingController::class, 'updateChecklistStatus'])->name('onboarding.checklist.update-status');
        Route::post('/checklist/{id}/assign', [OnboardingController::class, 'assignChecklist'])->name('onboarding.checklist.assign');
        Route::delete('/checklist/{id}', [OnboardingController::class, 'destroyChecklist'])->name('onboarding.checklist.destroy');

        // Checklist Templates
        Route::get('/checklist-templates', [OnboardingController::class, 'checklistTemplates'])->name('onboarding.checklist-templates');
        Route::get('/checklist-templates/data', [OnboardingController::class, 'getChecklistTemplates'])->name('onboarding.checklist-templates.data');
        Route::get('/checklist-templates/create', [OnboardingController::class, 'createChecklistTemplate'])->name('onboarding.checklist-templates.create');
        Route::post('/checklist-templates', [OnboardingController::class, 'storeChecklistTemplate'])->name('onboarding.checklist-templates.store');
        Route::get('/checklist-templates/{id}/edit', [OnboardingController::class, 'editChecklistTemplate'])->name('onboarding.checklist-templates.edit');
        Route::put('/checklist-templates/{id}', [OnboardingController::class, 'updateChecklistTemplate'])->name('onboarding.checklist-templates.update');
        Route::delete('/checklist-templates/{id}', [OnboardingController::class, 'destroyChecklistTemplate'])->name('onboarding.checklist-templates.destroy');
    });

    // ==================== RESOURCE ROUTES ====================
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('options', OptionController::class);
    Route::resource('policies', PolicyController::class);

    // ==================== OPTION MASTER ROUTES ====================
    Route::prefix('option-master')->name('option-master.')->group(function () {
        Route::match(['get', 'post'], '/', [OptionMasterController::class, 'index'])->name('index');
        Route::post('/store', [OptionMasterController::class, 'store'])->name('store');
        Route::post('/update', [OptionMasterController::class, 'update'])->name('update');
        Route::get('/change-status/{id}', [OptionMasterController::class, 'changeStatus'])->name('changeStatus');
        Route::post('/inline-store', [OptionMasterController::class, 'inlineStore'])->name('inline-store');
    });

    // ==================== UTILITY ROUTES ====================
    Route::get('/sub-categories/by-category/{category}', [ProductMasterController::class, 'getSubCategories'])->name('subcategories.by.category');
    Route::get('/test-geocode', function () {
        $response = Http::withHeaders([
            'User-Agent' => 'RGsmartorganic/1.0 (support@rstopcoder.com)',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => 'sector 18, noida, Uttar Pradesh, 201301, India',
            'format' => 'json',
            'limit' => 1,
        ]);
        dd($response->body());
    });
});

require __DIR__.'/auth.php';