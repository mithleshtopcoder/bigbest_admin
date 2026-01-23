<ul class="sidebar-menu">
    <li class="menu-item">
        <label class="menu-label px-3 py-2 mb-0">Navigation</label>
    </li>
    @auth
    @if(Auth::user()->user_type === 'vendor')
    <!-- Vendor Dashboard -->
    <li class="menu-item">
        <a href="{{ route('vendor.dashboard') }}" class="menu-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 menu-icon"></i>
            <span class="menu-text">Dashboard</span>
        </a>
    </li>
    @elseif(Auth::user()->user_type === 'admin')
    @can('dashboard.list')
    <!-- Admin Dashboard -->
    <li class="menu-item">
        <a href="{{ route('home') }}" class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 menu-icon"></i>
            <span class="menu-text">Dashboard</span>
        </a>
    </li>
    @endcan
    @endif
    @endauth


    @if(auth()->check() && (
    auth()->user()->user_type === 'vendor' ||
    auth()->user()->can('pos-orders.list') ||
    auth()->user()->can('online-orders.list')
    ))
    <li class="menu-item has-submenu {{ request()->routeIs('new-order.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('new-order.*') ? 'active' : '' }}">
            <i class="bi bi-plus-square menu-icon"></i>
            <span class="menu-text">New Order</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>

        <ul class="submenu order-status-submenu">

            {{-- POS Order (optional: admin only) --}}
            @if(auth()->user()->user_type !== 'vendor')
            @can('pos-orders.list')
            <li class="menu-item">
                <a href="{{ route('new-order.index', 'pos') }}" class="menu-link {{ request()->routeIs('new-order.index') && request()->route('type') == 'pos' ? 'active' : '' }}">
                    POS Order
                </a>
            </li>
            @endcan
            @endif

            {{-- Online Order (vendor + admin) --}}
            @if(auth()->user()->user_type === 'vendor' || auth()->user()->can('online-orders.list'))
            <li class="menu-item">
                <a href="{{ route('new-order.index', 'online') }}" class="menu-link {{ request()->routeIs('new-order.index') && request()->route('type') == 'online' ? 'active' : '' }}">
                    Online Order
                </a>
            </li>
            @endif

        </ul>
    </li>
    @endif


    @can('online-orders.status')
    <li class="menu-item has-submenu {{ request()->routeIs('order-status.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('order-status.*') ? 'active' : '' }}">
            <i class="bi bi-list-check menu-icon"></i>
            <span class="menu-text">Order Status</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu order-status-submenu">
            @can('order-status-accepted.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'accepted') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'accepted' ? 'active' : '' }}">Accepted</a>
            </li>
            @endcan
            @can('order-status-preparing.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'preparing') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'preparing' ? 'active' : '' }}">Preparing</a>
            </li>
            @endcan
            @can('order-status-ready-to-ship.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'ready') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'ready' ? 'active' : '' }}">Ready
                    To Ship</a></li>
            @endcan
            @can('order-status-shipped.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'shipped') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'shipped' ? 'active' : '' }}">Shipped</a>
            </li>
            @endcan
            @can('order-status-out-for-delivery.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'out-for-delivery') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'out-for-delivery' ? 'active' : '' }}">Out
                    for Delivery</a></li>
            @endcan
            @can('order-status-completed.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'completed') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'completed' ? 'active' : '' }}">Completed</a>
            </li>
            @endcan
            @can('order-status-cancelled.list')
            <li class="menu-item"><a href="{{ route('order-status.index', 'cancelled') }}" class="menu-link {{ request()->routeIs('order-status.index') && request()->route('status') == 'cancelled' ? 'active' : '' }}">Cancelled</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan
    @if(auth()->check() && (
    auth()->user()->user_type === 'vendor' ||
    auth()->user()->can('product-master.list') ||
    auth()->user()->can('product-category.list') ||
    auth()->user()->can('product-sub-category.list') ||
    auth()->user()->can('brand.list')
    ))
    <li class="menu-item has-submenu {{ request()->routeIs('manage-product.*') && !request()->routeIs('manage-product.offer.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('manage-product.*') && !request()->routeIs('manage-product.offer.*') ? 'active' : '' }}">
            <i class="bi bi-box menu-icon"></i>
            <span class="menu-text">Manage Product</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>

        <ul class="submenu order-status-submenu">

            {{-- Product Master (Vendor + Admin) --}}
            @if(auth()->user()->user_type === 'vendor' || auth()->user()->can('product-master.list'))
            <li class="menu-item">
                <a href="{{ route('manage-product.product-master.index') }}" class="menu-link {{ request()->routeIs('manage-product.product-master.*') ? 'active' : '' }}">
                    Product Master
                </a>
            </li>
            @endif

            {{-- Admin / Staff only --}}
            @if(auth()->user()->user_type !== 'vendor')

            @can('product-category.list')
            <li class="menu-item">
                <a href="{{ route('manage-product.category.index') }}" class="menu-link {{ request()->routeIs('manage-product.category.*') ? 'active' : '' }}">
                    Categories
                </a>
            </li>
            @endcan

            @can('product-sub-category.list')
            <li class="menu-item">
                <a href="{{ route('manage-product.sub-category.index') }}" class="menu-link {{ request()->routeIs('manage-product.sub-category.*') ? 'active' : '' }}">
                    Sub-Categories
                </a>
            </li>
            @endcan

            @can('brand.list')
            <li class="menu-item">
                <a href="{{ route('manage-product.brands.index') }}" class="menu-link {{ request()->routeIs('manage-product.brands.*') ? 'active' : '' }}">
                    Brands
                </a>
            </li>
            @endcan

            @endif

        </ul>
    </li>
    @endif


    @can('coupon.list')
    <li class="menu-item has-submenu {{ request()->routeIs('manage-product.offer.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('manage-product.offer.*') ? 'active' : '' }}">
            <i class="bi bi-box menu-icon"></i>
            <span class="menu-text">Offers</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu order-status-submenu">
            <li class="menu-item">
                <a href="{{ route('manage-product.offer.index', 'coupons') }}" class="menu-link {{ request()->routeIs('manage-product.offer.index') && request()->route('type') == 'coupons' ? 'active' : '' }}">Coupons</a>
            </li>
            <li class="menu-item">
                <a href="{{ route('manage-product.offer.index', 'discounts') }}" class="menu-link {{ request()->routeIs('manage-product.offer.index') && request()->route('type') == 'discounts' ? 'active' : '' }}">Discounts</a>
            </li>
            {{-- <li class="menu-item">
                    <a href="{{ route('manage-product.offer.index', 'combo-offers') }}" class="menu-link {{ request()->routeIs('manage-product.offer.index') && request()->route('type') == 'combo-offers' ? 'active' : '' }}">Combo Offers</a>
    </li> --}}
</ul>
</li>
@endcan

{{-- @canany(['store-wise-inventory.list', 'stock-in-stock-out.list', 'stock-adjustment.list', 'transfer-request.list',
        'transfer-request.list', 'transfer-approval.list', 'transfer-in-transit-stock.list'])
        <li class="menu-item has-submenu {{ request()->routeIs('inventory-management.*') ? 'open' : '' }}">
<a href="javascript:void(0);" class="menu-link {{ request()->routeIs('inventory-management.*') ? 'active' : '' }}">
    <i class="bi bi-layers menu-icon"></i>
    <span class="menu-text">Inventory Management</span>
    <i class="bi bi-chevron-right menu-arrow"></i>
</a>
<ul class="submenu order-status-submenu">
    @can('store-wise-inventory.list')
    <li class="menu-item"><a href="{{ route('inventory-management.store-wise-stock') }}" class="menu-link {{ request()->routeIs('inventory-management.store-wise-stock*') ? 'active' : '' }}">Store-Wise
            Stock</a></li>
    @endcan
    @can('stock-in-stock-out.list')
    <li class="menu-item"><a href="{{ route('inventory-management.stock-in-stock-out') }}" class="menu-link {{ request()->routeIs('inventory-management.stock-in-stock-out*') ? 'active' : '' }}">Stock
            In / Stock Out</a></li>
    @endcan
    @can('stock-adjustment.list')
    <li class="menu-item"><a href="{{ route('inventory-management.stock-adjustment') }}" class="menu-link {{ request()->routeIs('inventory-management.stock-adjustment*') ? 'active' : '' }}">Stock
            Adjustment</a></li>
    @endcan
    @can('transfer-request.list')
    <li class="menu-item has-submenu {{ request()->routeIs('inventory-management.stock-transfer.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('inventory-management.stock-transfer.*') ? 'active' : '' }}">
            <span class="menu-text">Stock Transfer</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu order-status-submenu">
            @can('transfer-request.list')
            <li class="menu-item"><a href="{{ route('inventory-management.stock-transfer.transfer-request') }}" class="menu-link {{ request()->routeIs('inventory-management.stock-transfer.transfer-request*') ? 'active' : '' }}">Transfer
                    Request</a></li>
            @endcan
            @can('transfer-approval.list')
            <li class="menu-item"><a href="{{ route('inventory-management.stock-transfer.approval') }}" class="menu-link {{ request()->routeIs('inventory-management.stock-transfer.approval*') ? 'active' : '' }}">Approval</a>
            </li>
            @endcan
            @can('transfer-in-transit-stock.list')
            <li class="menu-item"><a href="{{ route('inventory-management.stock-transfer.in-transit') }}" class="menu-link {{ request()->routeIs('inventory-management.stock-transfer.in-transit*') ? 'active' : '' }}">In-Transit
                    Stock</a></li>
            @endcan
        </ul>
    </li>
    @endcan
</ul>
</li>
@endcanany --}}

@canany(['customer-master.list', 'customer-address.list', 'customer-feedback.list', 'supplier-master.list',
'vendor-master.list'])
<li class="menu-item has-submenu {{ request()->routeIs('party-management.*') ? 'open' : '' }}">
    <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('party-management.*') ? 'active' : '' }}">
        <i class="bi bi-people menu-icon"></i>
        <span class="menu-text">Party Management</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('customer-master.list')
        <li class="menu-item"><a href="{{ route('party-management.customers.list') }}" class="menu-link {{ request()->routeIs('party-management.customers.list*') ? 'active' : '' }}">Customers</a>
        </li>
        @endcan
        @can('customer-address.list')
        <li class="menu-item"><a href="{{ route('party-management.customers.addresses') }}" class="menu-link {{ request()->routeIs('party-management.customers.addresses*') ? 'active' : '' }}">Addresses</a>
        </li>
        @endcan
        @can('customer-feedback.list')
        <li class="menu-item"><a href="{{ route('party-management.customers.feedback') }}" class="menu-link {{ request()->routeIs('party-management.customers.feedback*') ? 'active' : '' }}">Feedback</a>
        </li>
        @endcan
        {{-- @can('supplier-master.list')
                    <li
                        class="menu-item has-submenu {{ request()->routeIs('party-management.suppliers-vendors.*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('party-management.suppliers-vendors.*') ? 'active' : '' }}">
            <span class="menu-text">Suppliers / Vendors</span>
            <i class="bi bi-chevron-right menu-arrow"></i>
        </a>
        <ul class="submenu order-status-submenu">
            @can('supplier-master.list')
            <li class="menu-item"><a href="{{ route('party-management.suppliers-vendors.index', ['type' => 'suppliers']) }}" class="menu-link {{ request()->routeIs('party-management.suppliers-vendors.*') && request()->route('type') == 'suppliers' ? 'active' : '' }}">Suppliers</a>
            </li>
            @endcan
            @can('vendor-master.list')
            <li class="menu-item"><a href="{{ route('party-management.suppliers-vendors.index', ['type' => 'vendors']) }}" class="menu-link {{ request()->routeIs('party-management.suppliers-vendors.*') && request()->route('type') == 'vendors' ? 'active' : '' }}">Vendors</a>
            </li>
            @endcan
        </ul>
</li>
@endcan --}}
</ul>
</li>
@endcanany

@if(auth()->check() && auth()->user()->user_type !== 'vendor')
<li class="menu-item has-submenu {{ request()->routeIs('vendors.*') ? 'open' : '' }}">
    <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
        <i class="bi bi-shop menu-icon"></i>
        <span class="menu-text">Vendor Management</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>

    <ul class="submenu">
        <li class="menu-item">
            <a href="{{ route('vendors.index') }}" class="menu-link {{ request()->routeIs('vendors.index') ? 'active' : '' }}">
                Vendor List
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('vendor-payouts.index') }}" class="menu-link {{ request()->routeIs('vendors.payouts*') ? 'active' : '' }}">
                Vendor Payouts
            </a>
        </li>
    </ul>
</li>
@endif


@canany(['manage-store.list', 'service-radius.list'])
<li class="menu-item has-submenu {{ request()->routeIs('store-management.*') ? 'open' : '' }}">
    <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('store-management.*') ? 'active' : '' }}">
        <i class="bi bi-house menu-icon"></i>
        <span class="menu-text">Store Management</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('manage-store.list')
        <li class="menu-item">
            <a href="{{ route('store-management.manage-store') }}" class="menu-link {{ request()->routeIs('store-management.manage-store*') ? 'active' : '' }}">
                Manage Store
            </a>
        </li>
        @endcan
        @can('service-radius.list')
        <li class="menu-item">
            <a href="{{ route('store-management.service-radius') }}" class="menu-link {{ request()->routeIs('store-management.service-radius*') ? 'active' : '' }}">
                Service Radius
            </a>
        </li>
        @endcan
    </ul>
</li>
@endcanany

@canany(['store-ledger.list', 'customer-ledger.list', 'supplier-ledger.list', 'expenses.list',
'payments-receipts.list'])
<li class="menu-item has-submenu">
    <a href="javascript:void(0);" class="menu-link">
        <i class="bi bi-currency-dollar menu-icon"></i>
        <span class="menu-text">Finance & Accounting</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('store-ledger.list')
        <li class="menu-item"><a href="{{ route('finance-accounting.store-ledger') }}" class="menu-link">Store
                Ledger</a></li>
        @endcan
        @can('customer-ledger.list')
        <li class="menu-item"><a href="{{ route('finance-accounting.customer-ledger') }}" class="menu-link">Customer Ledger</a></li>
        @endcan
        @can('supplier-ledger.list')
        <li class="menu-item"><a href="{{ route('finance-accounting.supplier-ledger') }}" class="menu-link">Supplier Ledger</a></li>
        @endcan
        @can('expenses.list')
        <li class="menu-item"><a href="{{ route('finance-accounting.expenses.index') }}" class="menu-link">Expenses</a></li>
        @endcan
        @can('payments-receipts.list')
        <li class="menu-item"><a href="{{ route('finance-accounting.payments-receipts') }}" class="menu-link">Payments & Receipts</a></li>
        @endcan
    </ul>
</li>
@endcanany

@canany(['sales-reports.list', 'pos-reports.list', 'app-orders-reports.list', 'inventory-reports.list',
'expense-reports.list', 'employee-sales-reports.list'])
<li class="menu-item has-submenu">
    <a href="javascript:void(0);" class="menu-link">
        <i class="bi bi-bar-chart menu-icon"></i>
        <span class="menu-text">Reports & Analytics</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('sales-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.sales-reports') }}" class="menu-link">Sales
                Reports</a></li>
        @endcan
        @can('pos-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.pos-reports') }}" class="menu-link">POS
                Reports</a></li>
        @endcan
        @can('app-orders-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.app-orders-reports') }}" class="menu-link">App Orders Reports</a></li>
        @endcan
        @can('inventory-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.inventory-report') }}" class="menu-link">Inventory Report</a></li>
        @endcan
        @can('expense-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.expense-report') }}" class="menu-link">Expense Report</a></li>
        @endcan
        @can('employee-sales-reports.list')
        <li class="menu-item"><a href="{{ route('reports-analytics.employee-sales-report') }}" class="menu-link">Employee Sales Report</a></li>
        @endcan
    </ul>
</li>
@endcanany

@canany(['about-us.list', 'contact-us.list', 'manage-policies.list', 'banners.list', 'social-media.list',
'faqs.list'])
<li class="menu-item has-submenu">
    <a href="javascript:void(0);" class="menu-link">
        <i class="bi bi-gear menu-icon"></i>
        <span class="menu-text">CMS</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('about-us.list')
        <li class="menu-item"><a href="{{ route('cms.about-us') }}" class="menu-link">About Us</a></li>
        @endcan
        @can('contact-us.list')
        <li class="menu-item"><a href="{{ route('cms.contact-us') }}" class="menu-link">Contact Us</a></li>
        @endcan
        @can('manage-policies.list')
        <li class="menu-item"><a href="{{ route('policies.index') }}" class="menu-link">Manage Policies</a></li>
        @endcan
        @can('banners.list')
        <li class="menu-item"><a href="{{ route('banners.index') }}" class="menu-link">Banners</a></li>
        @endcan
        @can('social-media.list')
        <li class="menu-item"><a href="{{ route('cms.social-media') }}" class="menu-link">Social Media</a></li>
        @endcan
        @can('faqs.list')
        <li class="menu-item"><a href="{{ route('cms.faq') }}" class="menu-link">FAQ</a></li>
        @endcan
    </ul>
</li>
@endcanany

@canany(['roles-permissions.list', 'manage-user.list', 'option-master.list', 'company-setup.list',
'tax-settings.list'])
<li class="menu-item has-submenu">
    <a href="javascript:void(0);" class="menu-link">
        <i class="bi bi-sliders menu-icon"></i>
        <span class="menu-text">Configuration Settings</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('manage-user.list')
        <li class="menu-item"><a href="{{ route('users.index') }}" class="menu-link">Manage User</a></li>
        @endcan
        @can('manage-role.list')
        <li class="menu-item"><a href="{{ route('roles.index') }}" class="menu-link">Manage Role</a></li>
        @endcan
        @can('option-master.list')
        <li class="menu-item"><a href="{{ route('option-master.index') }}" class="menu-link">Option Master</a>
        </li>
        @endcan
        @can('company-setup.list')
        <li class="menu-item"><a href="{{ route('configuration-settings.company-setup') }}" class="menu-link">Company Setup</a></li>
        @endcan
        {{-- @can('payment-methods.list')
        <li class="menu-item"><a href="{{ route('configuration-settings.payment-methods') }}" class="menu-link">Payment Methods</a>
</li>
@endcan --}}
@can('tax-settings.list')
<li class="menu-item"><a href="{{ route('configuration-settings.tax-settings') }}" class="menu-link">Tax Settings</a></li>
@endcan
</ul>
</li>
@endcanany

@canany(['user-logs.list', 'login-history.list', 'failed-login-history.list'])
<li class="menu-item has-submenu {{ request()->routeIs('logs-audit.*') ? 'open' : '' }}">
    <a href="javascript:void(0);" class="menu-link {{ request()->routeIs('logs-audit.*') ? 'active' : '' }}">
        <i class="bi bi-file-text menu-icon"></i>
        <span class="menu-text">Logs & Audit</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        @can('user-logs.list')
        <li class="menu-item"><a href="{{ route('logs-audit.user-logs') }}" class="menu-link {{ request()->routeIs('logs-audit.user-logs*') ? 'active' : '' }}">User Logs</a>
        </li>
        @endcan
        @can('login-history.list')
        <li class="menu-item"><a href="{{ route('logs-audit.login-history') }}" class="menu-link {{ request()->routeIs('logs-audit.login-history*') ? 'active' : '' }}">Login
                Reports</a></li>
        @endcan
        @can('failed-login-history.list')
        <li class="menu-item"><a href="{{ route('logs-audit.failed-login-history') }}" class="menu-link {{ request()->routeIs('logs-audit.failed-login-history*') ? 'active' : '' }}">Failed
                Login History</a></li>
        @endcan
    </ul>
</li>
@endcanany

@canany(['support-tickets.list'])
<li class="menu-item has-submenu">
    <a href="javascript:void(0);" class="menu-link">
        <i class="bi bi-headset menu-icon"></i>
        <span class="menu-text">Support</span>
        <i class="bi bi-chevron-right menu-arrow"></i>
    </a>
    <ul class="submenu order-status-submenu">
        <li class="menu-item"><a href="{{ route('support.tickets') }}" class="menu-link">Tickets</a></li>
        <li class="menu-item"><a href="{{ route('support.faqs') }}" class="menu-link">FAQs</a></li>
        <li class="menu-item"><a href="{{ route('support.system-announcements') }}" class="menu-link">System
                Announcements</a></li>
    </ul>
</li>
@endcanany







</ul>
