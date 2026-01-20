@extends('layouts.app')

@section('title', 'Hold / Resume Bills')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Hold / Resume Bills</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Hold / Resume Bills</li>
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
                                <input type="checkbox" class="custom-control-input" id="StoreFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="StoreFilter">Store</label>
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
                                <input type="checkbox" class="custom-control-input" id="EmployeeFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="EmployeeFilter">Employee</label>
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
        <!-- [Hold / Resume Bills List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Hold / Resume Bills List</h5>
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
                                    <th scope="row">Bill ID</th>
                                    <th>Date & Time</th>
                                    <th>Store</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                    <th>Held By</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-001</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">10:30 AM</span>
                                    </td>
                                    <td>Main Store</td>
                                    <td>Rajesh Kumar</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">3 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹45,680</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">John Doe</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '001']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-002</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">11:15 AM</span>
                                    </td>
                                    <td>Branch Store</td>
                                    <td>Priya Sharma</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">2 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹32,450</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">Jane Smith</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '002']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-003</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">12:45 PM</span>
                                    </td>
                                    <td>Main Store</td>
                                    <td>Amit Patel</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">4 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹67,800</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">John Doe</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '003']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-004</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">02:20 PM</span>
                                    </td>
                                    <td>Branch Store</td>
                                    <td>Sneha Reddy</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">1 item</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹28,900</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">Jane Smith</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '004']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-005</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">03:10 PM</span>
                                    </td>
                                    <td>Main Store</td>
                                    <td>Vikram Singh</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">5 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹89,500</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">John Doe</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '005']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-006</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">04:30 PM</span>
                                    </td>
                                    <td>Branch Store</td>
                                    <td>Meera Joshi</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">2 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹34,600</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">Jane Smith</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '006']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-007</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">05:15 PM</span>
                                    </td>
                                    <td>Main Store</td>
                                    <td>Rahul Verma</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">3 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹56,750</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">John Doe</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '007']) }}" class="avatar-text avatar-sm" title="View Bill">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-pause-circle"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#BILL-008</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="d-block">2024-01-15</span>
                                        <span class="fs-12 d-block fw-normal text-muted">06:00 PM</span>
                                    </td>
                                    <td>Branch Store</td>
                                    <td>Anjali Desai</td>
                                    <td>
                                        <span class="badge bg-gray-200 text-dark">4 items</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark">₹72,300</span>
                                    </td>
                                    <td>
                                        <span class="text-sm">Jane Smith</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-play me-2 text-success"></i>Resume</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="{{ route('new-order.view', ['type' => 'pos', 'id' => '008']) }}" class="avatar-text avatar-sm" title="View Bill">
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
        <!-- [Hold / Resume Bills List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

