@extends('layouts.app')

@section('title', 'Loyalty Points')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Loyalty Points</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Party Management</li>
            <li class="breadcrumb-item">Customers</li>
            <li class="breadcrumb-item">Loyalty Points</li>
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
                                <input type="checkbox" class="custom-control-input" id="CustomerFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="CustomerFilter">Customer</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="TypeFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="TypeFilter">Transaction Type</label>
                            </div>
                        </div>
                        <div class="dropdown-item">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="DateFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="DateFilter">Date</label>
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
        <!-- [Loyalty Points List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Loyalty Points List</h5>
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
                                    <th scope="row">Transaction ID</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Points</th>
                                    <th>Balance</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-001</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Rajesh Kumar</td>
                                    <td><span class="badge bg-soft-success text-success">Earned</span></td>
                                    <td><span class="fw-bold text-success">+500</span></td>
                                    <td><span class="fw-bold text-dark">500 points</span></td>
                                    <td>2024-01-15</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-002</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Priya Sharma</td>
                                    <td><span class="badge bg-soft-danger text-danger">Redeemed</span></td>
                                    <td><span class="fw-bold text-danger">-250</span></td>
                                    <td><span class="fw-bold text-dark">250 points</span></td>
                                    <td>2024-01-14</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-003</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Amit Patel</td>
                                    <td><span class="badge bg-soft-success text-success">Earned</span></td>
                                    <td><span class="fw-bold text-success">+750</span></td>
                                    <td><span class="fw-bold text-dark">1,000 points</span></td>
                                    <td>2024-01-13</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-004</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Sneha Reddy</td>
                                    <td><span class="badge bg-soft-danger text-danger">Redeemed</span></td>
                                    <td><span class="fw-bold text-danger">-100</span></td>
                                    <td><span class="fw-bold text-dark">150 points</span></td>
                                    <td>2024-01-12</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-005</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Vikram Singh</td>
                                    <td><span class="badge bg-soft-success text-success">Earned</span></td>
                                    <td><span class="fw-bold text-success">+1,000</span></td>
                                    <td><span class="fw-bold text-dark">1,000 points</span></td>
                                    <td>2024-01-11</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-006</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Meera Joshi</td>
                                    <td><span class="badge bg-soft-danger text-danger">Redeemed</span></td>
                                    <td><span class="fw-bold text-danger">-500</span></td>
                                    <td><span class="fw-bold text-dark">500 points</span></td>
                                    <td>2024-01-10</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-007</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Rahul Verma</td>
                                    <td><span class="badge bg-soft-success text-success">Earned</span></td>
                                    <td><span class="fw-bold text-success">+600</span></td>
                                    <td><span class="fw-bold text-dark">600 points</span></td>
                                    <td>2024-01-09</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-star"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#LP-008</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Anjali Desai</td>
                                    <td><span class="badge bg-soft-danger text-danger">Redeemed</span></td>
                                    <td><span class="fw-bold text-danger">-300</span></td>
                                    <td><span class="fw-bold text-dark">200 points</span></td>
                                    <td>2024-01-08</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-trash-2 me-2 text-danger"></i>Delete</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Transaction">
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
        <!-- [Loyalty Points List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

