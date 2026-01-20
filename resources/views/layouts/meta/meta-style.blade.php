<!-- Favicon -->
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}" />

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendors/css/sweetalert2.min.css') }}">

<!-- Custom Admin CSS -->
<style>
    :root {
        --sidebar-width: 260px;
        --sidebar-width-mini: 70px;
        --header-height: 60px;
        --primary-color: #0d6efd;
        --sidebar-bg: #1a5f3f;
        --sidebar-hover: #2d7a5a;
        --sidebar-dark-green: #155d41;
    }

    html,
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background-color: #f8f9fa;
        overflow-x: hidden;
        max-width: 100vw;
        width: 100%;
    }

    .main-body {
        padding: 0.9rem;
    }

    * {
        box-sizing: border-box;
    }

    .container-fluid {
        max-width: 100%;
        overflow-x: hidden;
        padding-left: 0rem;
        padding-right: 0rem;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        max-width: 100%;
    }

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background-color: var(--sidebar-bg);
        background: linear-gradient(180deg, var(--sidebar-dark-green) 0%, var(--sidebar-bg) 100%);
        color: #fff;
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1000;
        transition: width 0.3s ease, transform 0.3s ease;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar.collapsed {
        transform: translateX(-100%);
    }

    .sidebar.mini {
        width: var(--sidebar-width-mini);
    }

    .sidebar-header {
        position: sticky;
        top: 0;
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: var(--header-height);
        background-color: rgb(20 84 58);
        z-index: 10;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        white-space: nowrap;
        overflow: hidden;
    }

    .sidebar-brand-text {
        transition: opacity 0.3s ease;
    }

    .sidebar.mini .sidebar-brand-text {
        opacity: 0;
        width: 0;
        overflow: hidden;
    }

    .sidebar-logo {
        max-height: 80px;
        width: auto;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .sidebar.mini .logo-lg {
        display: none;
    }

    .sidebar.mini .logo-sm {
        display: block !important;
        max-height: 35px;
    }

    .sidebar:not(.mini) .logo-sm {
        display: none !important;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-menu .menu-item {
    /*    border-bottom: 1px solid rgba(255, 255, 255, 0.08); */
    }
    .sidebar-menu .menu-link {
        display: flex;
        align-items: center;
        padding: 0.7rem .4rem;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }
      .sidebar-menu .submenu .menu-link {
        padding: 0.6rem .4rem;
        margin:.0rem .6rem;
        border-radius: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .sidebar-menu .menu-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .sidebar-menu .menu-link.active {
        background-color: rgb(5 69 43);
        color: #fff;
        border-left: 3px solid #fff;
    }

    .sidebar-menu .submenu .menu-link.active {
        background-color: rgb(64 58 1);
        color: #fff;
        border-left: 3px solid #fff;
    }
    .sidebar-menu .menu-icon {
        width: 20px;
        min-width: 20px;
        margin-right: 0.5rem;
        text-align: center;
        flex-shrink: 0;
    }

    .sidebar-menu .menu-text {
        white-space: nowrap;
        transition: opacity 0.3s ease;
    }

    .sidebar-menu .menu-arrow {
        margin-left: auto;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .sidebar.mini .menu-text,
    .sidebar.mini .menu-arrow,
    .sidebar.mini .menu-label {
        opacity: 0;
        width: 0;
        overflow: hidden;
        margin: 0;
    }

    .sidebar.mini .menu-icon {
        margin-right: 0;
    }

    .sidebar.mini .menu-link {
        justify-content: center;
        padding: 0.75rem;
    }

    .sidebar-menu .menu-item.has-submenu.open>.menu-link .menu-arrow {
        transform: rotate(90deg);
    }

    .sidebar-menu .submenu {
        list-style: none;
        padding: 0;
        margin: 0;
        background-color: rgb(221 193 8 / 83%);
        display: none;
        overflow: hidden;
        max-height: 0;
        opacity: 0;
        transition: max-height 0.3s ease, opacity 0.3s ease;
    }

    .sidebar-menu .menu-item.has-submenu.open>.submenu,
    .sidebar-menu .menu-item.has-submenu.open .submenu {
        display: block !important;
        max-height: 1000px !important;
        opacity: 1 !important;
    }

    .sidebar.mini .submenu {
        position: absolute;
        left: 100%;
        top: 0;
        min-width: 220px;
        background-color: var(--sidebar-bg);
        background: linear-gradient(180deg, var(--sidebar-dark-green) 0%, var(--sidebar-bg) 100%);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        margin-left: 5px;
        border-radius: 0 0.375rem 0.375rem 0;
    }

    .sidebar.mini .menu-item.has-submenu {
        position: relative;
    }

    .sidebar.mini .menu-item.has-submenu:hover>.submenu {
        display: block;
        max-height: 1000px;
    }

    .sidebar.mini .submenu .menu-link {
        padding-left: 1rem;
    }

    .sidebar.mini .submenu .submenu {
        left: 100%;
        top: 0;
    }

    .sidebar-menu .submenu .menu-link {
        padding-left: 2.5rem;
        font-size: 0.9rem;
    }

    .sidebar:not(.mini) .submenu .menu-link {
        padding-left: 2.5rem;
    }

    .sidebar-menu .submenu .submenu .menu-link {
        padding-left: 3.5rem;
    }

    .sidebar-menu .order-status-submenu .menu-link::before {
        content: "\2022";
        display: inline-block;
        left: .8rem;
        margin-top: -2.6rem;
        font-size: 3rem;
        line-height: 1;
        width: 12px;
        height: 12px;
        color: rgba(255, 255, 255, 0.9);
        position: absolute;
    }

    .sidebar-menu .menu-label {
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.7rem;
        transition: opacity 0.3s ease;
    }

    /* Header Styles */
    .main-header {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        height: var(--header-height);
        background-color: #fff;
        border-bottom: 1px solid #dee2e6;
        z-index: 999;
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: left 0.3s ease, width 0.3s ease;
        width: calc(100vw - var(--sidebar-width));
        box-sizing: border-box;
        /* overflow-x: hidden; */
    }

    .main-header.sidebar-collapsed {
        left: 0;
        width: 100vw;
    }

    .main-header.sidebar-mini {
        left: var(--sidebar-width-mini);
        width: calc(100vw - var(--sidebar-width-mini));
    }

    .header-toggle {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: #495057;
        cursor: pointer;
        padding: 0.5rem;
    }

    .header-toggle:hover {
        color: var(--primary-color);
    }

    /* Main Content */
    .main-content {
        margin-left: var(--sidebar-width);
        margin-top: var(--header-height);
        padding: 0px;
        min-height: calc(100vh - var(--header-height));
        transition: margin-left 0.3s ease;
        overflow-x: hidden;
        width: calc(100vw - var(--sidebar-width));
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
    }

    .main-content.sidebar-collapsed {
        margin-left: 0;
        width: 100vw;
    }

    .main-content.sidebar-mini {
        margin-left: var(--sidebar-width-mini);
        width: calc(100vw - var(--sidebar-width-mini));
    }

    .btn-xs {
        padding: 0.25rem 0.3rem;
        font-size: 0.75rem;
        line-height: 11px;
        border-radius: 0.2rem;
    }

    /* Page Header */
    .page-header {
        background-color: #fff;
        padding: .3rem .9rem;
        border-radius: 0.375rem;
        margin-bottom: 0rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        color: #212529;
    }

    .breadcrumb {
        margin: 0.5rem 0 0 0;
        padding: 0;
        background: none;
        font-size: 0.875rem;
    }

    .breadcrumb-item a {
        color: #6c757d;
        transition: color 0.2s ease;
    }

    .breadcrumb-item a:hover {
        color: var(--primary-color);
    }

    .breadcrumb-item.active {
        color: #495057;
    }

    .page-header-right .btn {
        white-space: nowrap;
    }

    .dropdown-item-text {
        padding: 0.5rem 1rem;
    }

    .dropdown-item-text .form-check {
        margin: 0;
    }

    /* Content Card */
    .content-card {
        background-color: #fff;
        border-radius: 0.375rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    /* Footer */
    .main-footer {
        background-color: #fff;
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
        margin-top: auto;
        width: 100%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-header {
            left: 0;
            width: 100vw;
        }

        .main-content {
            margin-left: 0;
            width: 100vw;
        }
    }

    /* Overlay for mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 999;
    }

    .sidebar-overlay.show {
        display: block;
    }
    .form-control:focus{
        border-color: rgb(19 84 58);
        outline: 0;
        /* box-shadow: 0 0 0 .25rem rgb(19 84 58 / 25%); */
        box-shadow: none;
    }
    .form-control{
        height: 28px;
        font-size: 14px;
        border-radius: 0px;
        border: 1px solid #ced4da;
        padding: 0 10px;
        background-color: #fff;
        color: #212529;
        transition: all 0.3s ease;
    }
    .form-label{
        height: 28px;
        background: rgb(31 121 85 / 25%);
        border: 1px solid rgb(19 84 58 / 25%);

    }
    .row-p{
        padding-left: 12px;
    }


    /* Padding Utilities - All sides */
    .p-0 { padding: 0px !important; }
    .p-1 { padding: 6px !important; }
    .p-2 { padding: 12px !important; }
    .p-3 { padding: 18px !important; }
    .p-4 { padding: 24px !important; }
    .p-5 { padding: 30px !important; }
    .p-6 { padding: 36px !important; }
    .p-8 { padding: 48px !important; }
    .p-10 { padding: 10px !important; }
    .p-15 { padding: 15px !important; }
    .p-20 { padding: 20px !important; }
    .p-25 { padding: 25px !important; }
    .p-30 { padding: 30px !important; }

    /* Padding Utilities - Horizontal (left & right) */
    .px-0 { padding-left: 0px !important; padding-right: 0px !important; }
    .px-1 { padding-left: 6px !important; padding-right: 6px !important; }
    .px-2 { padding-left: 12px !important; padding-right: 12px !important; }
    .px-3 { padding-left: 18px !important; padding-right: 18px !important; }
    .px-4 { padding-left: 24px !important; padding-right: 24px !important; }
    .px-5 { padding-left: 30px !important; padding-right: 30px !important; }
    .px-10 { padding-left: 10px !important; padding-right: 10px !important; }
    .px-15 { padding-left: 15px !important; padding-right: 15px !important; }
    .px-20 { padding-left: 20px !important; padding-right: 20px !important; }
    .px-25 { padding-left: 25px !important; padding-right: 25px !important; }
    .px-30 { padding-left: 30px !important; padding-right: 30px !important; }

    /* Padding Utilities - Vertical (top & bottom) */
    .py-0 { padding-top: 0px !important; padding-bottom: 0px !important; }
    .py-1 { padding-top: 6px !important; padding-bottom: 6px !important; }
    .py-2 { padding-top: 12px !important; padding-bottom: 12px !important; }
    .py-3 { padding-top: 18px !important; padding-bottom: 18px !important; }
    .py-4 { padding-top: 24px !important; padding-bottom: 24px !important; }
    .py-5 { padding-top: 30px !important; padding-bottom: 30px !important; }
    .py-10 { padding-top: 10px !important; padding-bottom: 10px !important; }
    .py-15 { padding-top: 15px !important; padding-bottom: 15px !important; }
    .py-20 { padding-top: 20px !important; padding-bottom: 20px !important; }
    .py-25 { padding-top: 25px !important; padding-bottom: 25px !important; }
    .py-30 { padding-top: 30px !important; padding-bottom: 30px !important; }

    /* Padding Utilities - Top */
    .pt-0 { padding-top: 0px !important; }
    .pt-1 { padding-top: 6px !important; }
    .pt-2 { padding-top: 12px !important; }
    .pt-3 { padding-top: 18px !important; }
    .pt-4 { padding-top: 24px !important; }
    .pt-5 { padding-top: 30px !important; }
    .pt-10 { padding-top: 10px !important; }
    .pt-15 { padding-top: 15px !important; }
    .pt-20 { padding-top: 20px !important; }
    .pt-25 { padding-top: 25px !important; }
    .pt-30 { padding-top: 30px !important; }

    /* Padding Utilities - Bottom */
    .pb-0 { padding-bottom: 0px !important; }
    .pb-1 { padding-bottom: 6px !important; }
    .pb-2 { padding-bottom: 12px !important; }
    .pb-3 { padding-bottom: 18px !important; }
    .pb-4 { padding-bottom: 24px !important; }
    .pb-5 { padding-bottom: 30px !important; }
    .pb-10 { padding-bottom: 10px !important; }
    .pb-15 { padding-bottom: 15px !important; }
    .pb-20 { padding-bottom: 20px !important; }
    .pb-25 { padding-bottom: 25px !important; }
    .pb-30 { padding-bottom: 30px !important; }

    /* Padding Utilities - Left */
    .ps-0 { padding-left: 0px !important; }
    .ps-1 { padding-left: 6px !important; }
    .ps-2 { padding-left: 12px !important; }
    .ps-3 { padding-left: 18px !important; }
    .ps-4 { padding-left: 24px !important; }
    .ps-5 { padding-left: 30px !important; }
    .ps-10 { padding-left: 10px !important; }
    .ps-15 { padding-left: 15px !important; }
    .ps-20 { padding-left: 20px !important; }
    .ps-25 { padding-left: 25px !important; }
    .ps-30 { padding-left: 30px !important; }

    /* Padding Utilities - Right */
    .pe-0 { padding-right: 0px !important; }
    .pe-1 { padding-right: 6px !important; }
    .pe-2 { padding-right: 12px !important; }
    .pe-3 { padding-right: 18px !important; }
    .pe-4 { padding-right: 24px !important; }
    .pe-5 { padding-right: 30px !important; }
    .pe-10 { padding-right: 10px !important; }
    .pe-15 { padding-right: 15px !important; }
    .pe-20 { padding-right: 20px !important; }
    .pe-25 { padding-right: 25px !important; }
    .pe-30 { padding-right: 30px !important; }

    /* Margin Utilities - All sides */
    .m-0 { margin: 0px !important; }
    .m-1 { margin: 6px !important; }
    .m-2 { margin: 12px !important; }
    .m-3 { margin: 18px !important; }
    .m-4 { margin: 24px !important; }
    .m-5 { margin: 30px !important; }
    .m-10 { margin: 10px !important; }
    .m-15 { margin: 15px !important; }
    .m-20 { margin: 20px !important; }
    .m-25 { margin: 25px !important; }
    .m-30 { margin: 30px !important; }

    /* Margin Utilities - Horizontal (left & right) */
    .mx-0 { margin-left: 0px !important; margin-right: 0px !important; }
    .mx-1 { margin-left: 6px !important; margin-right: 6px !important; }
    .mx-2 { margin-left: 12px !important; margin-right: 12px !important; }
    .mx-3 { margin-left: 18px !important; margin-right: 18px !important; }
    .mx-4 { margin-left: 24px !important; margin-right: 24px !important; }
    .mx-5 { margin-left: 30px !important; margin-right: 30px !important; }
    .mx-10 { margin-left: 10px !important; margin-right: 10px !important; }
    .mx-15 { margin-left: 15px !important; margin-right: 15px !important; }
    .mx-20 { margin-left: 20px !important; margin-right: 20px !important; }
    .mx-25 { margin-left: 25px !important; margin-right: 25px !important; }
    .mx-30 { margin-left: 30px !important; margin-right: 30px !important; }
    .mx-auto { margin-left: auto !important; margin-right: auto !important; }

    /* Margin Utilities - Vertical (top & bottom) */
    .my-0 { margin-top: 0px !important; margin-bottom: 0px !important; }
    .my-1 { margin-top: 6px !important; margin-bottom: 6px !important; }
    .my-2 { margin-top: 12px !important; margin-bottom: 12px !important; }
    .my-3 { margin-top: 18px !important; margin-bottom: 18px !important; }
    .my-4 { margin-top: 24px !important; margin-bottom: 24px !important; }
    .my-5 { margin-top: 30px !important; margin-bottom: 30px !important; }
    .my-10 { margin-top: 10px !important; margin-bottom: 10px !important; }
    .my-15 { margin-top: 15px !important; margin-bottom: 15px !important; }
    .my-20 { margin-top: 20px !important; margin-bottom: 20px !important; }
    .my-25 { margin-top: 25px !important; margin-bottom: 25px !important; }
    .my-30 { margin-top: 30px !important; margin-bottom: 30px !important; }

    /* Margin Utilities - Top */
    .mt-0 { margin-top: 0px !important; }
    .mt-1 { margin-top: 6px !important; }
    .mt-2 { margin-top: 12px !important; }
    .mt-3 { margin-top: 18px !important; }
    .mt-4 { margin-top: 24px !important; }
    .mt-5 { margin-top: 30px !important; }
    .mt-10 { margin-top: 10px !important; }
    .mt-15 { margin-top: 15px !important; }
    .mt-20 { margin-top: 20px !important; }
    .mt-25 { margin-top: 25px !important; }
    .mt-30 { margin-top: 30px !important; }

    /* Margin Utilities - Bottom */
    .mb-0 { margin-bottom: 0px !important; }
    .mb-1 { margin-bottom: 6px !important; }
    .mb-2 { margin-bottom: 12px !important; }
    .mb-3 { margin-bottom: 18px !important; }
    .mb-4 { margin-bottom: 24px !important; }
    .mb-5 { margin-bottom: 30px !important; }
    .mb-10 { margin-bottom: 10px !important; }
    .mb-15 { margin-bottom: 15px !important; }
    .mb-20 { margin-bottom: 20px !important; }
    .mb-25 { margin-bottom: 25px !important; }
    .mb-30 { margin-bottom: 30px !important; }

    /* Margin Utilities - Left */
    .ms-0 { margin-left: 0px !important; }
    .ms-1 { margin-left: 6px !important; }
    .ms-2 { margin-left: 12px !important; }
    .ms-3 { margin-left: 18px !important; }
    .ms-4 { margin-left: 24px !important; }
    .ms-5 { margin-left: 30px !important; }
    .ms-10 { margin-left: 10px !important; }
    .ms-15 { margin-left: 15px !important; }
    .ms-20 { margin-left: 20px !important; }
    .ms-25 { margin-left: 25px !important; }
    .ms-30 { margin-left: 30px !important; }
    .ms-auto { margin-left: auto !important; }

    /* Margin Utilities - Right */
    .me-0 { margin-right: 0px !important; }
    .me-1 { margin-right: 6px !important; }
    .me-2 { margin-right: 12px !important; }
    .me-3 { margin-right: 18px !important; }
    .me-4 { margin-right: 24px !important; }
    .me-5 { margin-right: 30px !important; }
    .me-10 { margin-right: 10px !important; }
    .me-15 { margin-right: 15px !important; }
    .me-20 { margin-right: 20px !important; }
    .me-25 { margin-right: 25px !important; }
    .me-30 { margin-right: 30px !important; }
    .me-auto { margin-right: auto !important; }

 
.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #ffffff;
    background-color: var(--bs-nav-tabs-link-active-bg);
    border-color: var(--bs-nav-tabs-link-active-border-color);
    background: #218c62;
    border-bottom: 1px solid #228c62;
}
.nav-tabs-custom-style{
    border-bottom: 1px solid #228c62;
}
.nav-tabs .nav-link {
        background: rgb(220 168 11 / 46%);
    border-top: 1px solid rgb(232, 232, 232);
    border-left: 1px solid rgb(232, 232, 232);
    border-right: 1px solid rgb(232, 232, 232);
    padding: 2px 12px;
    font-size: 15px;
    color: #000000;
}

</style>
