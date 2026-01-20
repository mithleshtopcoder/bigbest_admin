<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'label' => 'Dashboard',
        'icon'  => 'bi bi-speedometer2',
        'route' => 'home',
        'permission' => 'dashboard.view',
    ],

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */
    'pos-orders' => [
        'label' => 'POS Order',
        'icon'  => 'bi bi-plus-square',
        'route' => 'new-order.index',
        'params'=> 'pos',
        'permission' => 'pos-orders.list',
        'group' => 'New Order',
    ],

    'online-orders' => [
        'label' => 'Online Order',
        'icon'  => 'bi bi-plus-square',
        'route' => 'new-order.index',
        'params'=> 'online',
        'permission' => 'online-orders.list',
        'group' => 'New Order',
    ],

    /*
    |--------------------------------------------------------------------------
    | Order Status
    |--------------------------------------------------------------------------
    */
    'order-status-accepted' => [
        'label' => 'Accepted',
        'icon'  => 'bi bi-list-check',
        'route' => 'order-status.index',
        'params'=> 'accepted',
        'permission' => 'order-status-accepted.list',
        'group' => 'Order Status',
    ],

    'order-status-preparing' => [
        'label' => 'Preparing',
        'route' => 'order-status.index',
        'params'=> 'preparing',
        'permission' => 'order-status-preparing.list',
        'group' => 'Order Status',
    ],

    'order-status-ready-to-ship' => [
        'label' => 'Ready To Ship',
        'route' => 'order-status.index',
        'params'=> 'ready',
        'permission' => 'order-status-ready-to-ship.list',
        'group' => 'Order Status',
    ],

    'order-status-shipped' => [
        'label' => 'Shipped',
        'route' => 'order-status.index',
        'params'=> 'shipped',
        'permission' => 'order-status-shipped.list',
        'group' => 'Order Status',
    ],

    'order-status-out-for-delivery' => [
        'label' => 'Out for Delivery',
        'route' => 'order-status.index',
        'params'=> 'out-for-delivery',
        'permission' => 'order-status-out-for-delivery.list',
        'group' => 'Order Status',
    ],

    'order-status-completed' => [
        'label' => 'Completed',
        'route' => 'order-status.index',
        'params'=> 'completed',
        'permission' => 'order-status-completed.list',
        'group' => 'Order Status',
    ],

    'order-status-cancelled' => [
        'label' => 'Cancelled',
        'route' => 'order-status.index',
        'params'=> 'cancelled',
        'permission' => 'order-status-cancelled.list',
        'group' => 'Order Status',
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Management
    |--------------------------------------------------------------------------
    */
    'product-master' => [
        'label' => 'Product Master',
        'icon'  => 'bi bi-box',
        'route' => 'manage-product.product-master.index',
        'permission' => 'product-master.list',
        'group' => 'Manage Product',
    ],

    'product-category' => [
        'label' => 'Categories',
        'route' => 'manage-product.category.index',
        'permission' => 'product-category.list',
        'group' => 'Manage Product',
    ],

    'product-sub-category' => [
        'label' => 'Sub Categories',
        'route' => 'manage-product.sub-category.index',
        'permission' => 'product-sub-category.list',
        'group' => 'Manage Product',
    ],

    'brand' => [
        'label' => 'Brands',
        'route' => 'manage-product.brands.index',
        'permission' => 'brand.list',
        'group' => 'Manage Product',
    ],

    /*
    |--------------------------------------------------------------------------
    | Offers
    |--------------------------------------------------------------------------
    */
    'coupon' => [
        'label' => 'Coupons',
        'icon'  => 'bi bi-box',
        'route' => 'manage-product.offer.index',
        'params'=> 'coupons',
        'permission' => 'coupon.list',
        'group' => 'Offers',
    ],

    'discount' => [
        'label' => 'Discounts',
        'route' => 'manage-product.offer.index',
        'params'=> 'discounts',
        'permission' => 'discount.list',
        'group' => 'Offers',
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */
    'store-wise-inventory' => [
        'label' => 'Store Wise Stock',
        'icon'  => 'bi bi-layers',
        'route' => 'inventory-management.store-wise-stock',
        'permission' => 'store-wise-inventory.list',
        'group' => 'Inventory Management',
    ],

    'stock-in-stock-out' => [
        'label' => 'Stock In / Out',
        'route' => 'inventory-management.stock-in-stock-out',
        'permission' => 'stock-in-stock-out.list',
        'group' => 'Inventory Management',
    ],

    'stock-adjustment' => [
        'label' => 'Stock Adjustment',
        'route' => 'inventory-management.stock-adjustment',
        'permission' => 'stock-adjustment.list',
        'group' => 'Inventory Management',
    ],

    'transfer-request' => [
        'label' => 'Transfer Request',
        'route' => 'inventory-management.stock-transfer.transfer-request',
        'permission' => 'transfer-request.list',
        'group' => 'Inventory Management',
    ],

    'transfer-approval' => [
        'label' => 'Approval',
        'route' => 'inventory-management.stock-transfer.approval',
        'permission' => 'transfer-approval.list',
        'group' => 'Inventory Management',
    ],

    'transfer-in-transit-stock' => [
        'label' => 'In-Transit Stock',
        'route' => 'inventory-management.stock-transfer.in-transit',
        'permission' => 'transfer-in-transit-stock.list',
        'group' => 'Inventory Management',
    ],

    /*
    |--------------------------------------------------------------------------
    | Finance & Accounting
    |--------------------------------------------------------------------------
    */
    'store-ledger' => [
        'label' => 'Store Ledger',
        'icon'  => 'bi bi-currency-dollar',
        'route' => 'finance-accounting.store-ledger',
        'permission' => 'store-ledger.list',
        'group' => 'Finance & Accounting',
    ],

    'customer-ledger' => [
        'label' => 'Customer Ledger',
        'route' => 'finance-accounting.customer-ledger',
        'permission' => 'customer-ledger.list',
        'group' => 'Finance & Accounting',
    ],

    'supplier-ledger' => [
        'label' => 'Supplier Ledger',
        'route' => 'finance-accounting.supplier-ledger',
        'permission' => 'supplier-ledger.list',
        'group' => 'Finance & Accounting',
    ],

    'expenses' => [
        'label' => 'Expenses',
        'route' => 'finance-accounting.expenses.index',
        'permission' => 'expenses.list',
        'group' => 'Finance & Accounting',
    ],

    'payments-receipts' => [
        'label' => 'Payments & Receipts',
        'route' => 'finance-accounting.payments-receipts',
        'permission' => 'payments-receipts.list',
        'group' => 'Finance & Accounting',
    ],

    /*
    |--------------------------------------------------------------------------
    | Support
    |--------------------------------------------------------------------------
    */
    'support-tickets' => [
        'label' => 'Tickets',
        'icon'  => 'bi bi-headset',
        'route' => 'support.tickets',
        'permission' => 'support-tickets.list',
        'group' => 'Support',
    ],

];
