@extends('layouts.app')

@section('title', 'Returns & Refunds')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Returns & Refunds</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Returns & Refunds</li>
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
            <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                    <span class="reportrange-picker-field"></span>
                </div>
                <div class="dropdown filter-dropdown">
                    <a class="btn btn-md btn-light-brand" style="padding: 5px 8px;" data-bs-toggle="dropdown" data-bs-offset="0, 10" data-bs-auto-close="outside">
                        <i class="feather-filter me-2"></i>
                        <span>Filter</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="StatusFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="StatusFilter">Status</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="DateFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="DateFilter">Date</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="StoreFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="StoreFilter">Store</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-md-none d-flex align-items-center">
            <a href="javascript:void(0)" class="page-header-right-open-toggle">
                <i class="feather-align-right fs-20"></i>
            </a>
        </div>
    </div>
</div>
<!-- [ page-header ] end -->
<!-- [ Main Content ] start -->
<div class="main-body">
    <div class="row">
        <!-- [Returns & Refunds List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Returns & Refunds List</h5>
                    <div class="card-header-action">
                        <div class="card-header-btn">
                            <div data-bs-toggle="tooltip" title="Delete">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-danger" data-bs-toggle="remove"> </a>
                            </div>
                            <div data-bs-toggle="tooltip" title="Refresh">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-warning" data-bs-toggle="refresh"> </a>
                            </div>
                            <div data-bs-toggle="tooltip" title="Maximize/Minimize">
                                <a href="javascript:void(0);" class="avatar-text avatar-xs bg-success" data-bs-toggle="expand"> </a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25">
                                <div data-bs-toggle="tooltip" title="Options">
                                    <i class="feather-more-vertical"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download"></i>Export</a>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer"></i>Print</a>
                                <div class="dropdown-divider"></div>
                                <a href="javascript:void(0);" class="dropdown-item"><i class="feather-settings"></i>Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">Return ID</th>
                                    <th>Original Order</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Refund Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-001</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-001</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Rajesh Kumar</span>
                                            <span class="fs-12 d-block fw-normal text-muted">rajesh@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">2 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹12,400</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-warning text-warning">Pending</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">10:30 AM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '001']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-002</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-002</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Priya Sharma</span>
                                            <span class="fs-12 d-block fw-normal text-muted">priya@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">1 item</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹8,750</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-success text-success">Approved</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">11:15 AM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '002']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-003</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-003</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Amit Patel</span>
                                            <span class="fs-12 d-block fw-normal text-muted">amit.patel@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">3 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹18,900</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-success text-success">Refunded</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">12:45 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '003']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-004</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-004</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Sneha Reddy</span>
                                            <span class="fs-12 d-block fw-normal text-muted">sneha.reddy@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">1 item</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹5,600</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-danger text-danger">Rejected</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">02:20 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '004']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-005</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-005</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Vikram Singh</span>
                                            <span class="fs-12 d-block fw-normal text-muted">vikram.singh@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">2 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹25,400</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-warning text-warning">Pending</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">03:10 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '005']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-006</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-006</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Meera Joshi</span>
                                            <span class="fs-12 d-block fw-normal text-muted">meera.joshi@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">1 item</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹8,900</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-success text-success">Refunded</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">04:30 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '006']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-007</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-007</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Rahul Verma</span>
                                            <span class="fs-12 d-block fw-normal text-muted">rahul.verma@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">3 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹22,500</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-success text-success">Approved</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">05:15 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '007']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-rotate-ccw"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#RET-008</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">#ORD-008</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);">
                                            <span class="d-block">Anjali Desai</span>
                                            <span class="fs-12 d-block fw-normal text-muted">anjali.desai@example.com</span>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">2 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹14,200</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-soft-warning text-warning">Pending</span>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">06:00 PM</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Approve</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Reject</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'online', 'id' => '008']) }}" class="avatar-text avatar-sm" title="View Return">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <ul class="list-unstyled d-flex align-items-center gap-2 mb-0 pagination-common-style">
                        <li>
                            <a href="javascript:void(0);"><i class="bi bi-arrow-left"></i></a>
                        </li>
                        <li><a href="javascript:void(0);" class="active">1</a></li>
                        <li><a href="javascript:void(0);">2</a></li>
                        <li>
                            <a href="javascript:void(0);"><i class="bi bi-dot"></i></a>
                        </li>
                        <li><a href="javascript:void(0);">8</a></li>
                        <li><a href="javascript:void(0);">9</a></li>
                        <li>
                            <a href="javascript:void(0);"><i class="bi bi-arrow-right"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- [Returns & Refunds List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

