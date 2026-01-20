@extends('layouts.app')

@section('title', 'Sales Reports')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Sales Reports</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Reports & Analytics</li>
            <li class="breadcrumb-item">Sales Reports</li>
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
        <!-- [Sales Reports] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Sales Reports</h5>
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
                                    <th scope="row">Date</th>
                                    <th>Store</th>
                                    <th>Total Orders</th>
                                    <th>Total Sales</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                    <th>Net Sales</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-15</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Main Store - Mumbai</td>
                                    <td><span class="badge bg-gray-200 text-dark">45 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹12,45,600</span></td>
                                    <td><span class="fw-bold text-danger">₹15,000</span></td>
                                    <td><span class="fw-bold text-info">₹12,300</span></td>
                                    <td><span class="fw-bold text-primary">₹12,42,900</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-14</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Delhi</td>
                                    <td><span class="badge bg-gray-200 text-dark">38 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹9,85,400</span></td>
                                    <td><span class="fw-bold text-danger">₹12,000</span></td>
                                    <td><span class="fw-bold text-info">₹9,700</span></td>
                                    <td><span class="fw-bold text-primary">₹9,83,100</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-13</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Bangalore</td>
                                    <td><span class="badge bg-gray-200 text-dark">52 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹15,12,800</span></td>
                                    <td><span class="fw-bold text-danger">₹18,000</span></td>
                                    <td><span class="fw-bold text-info">₹15,000</span></td>
                                    <td><span class="fw-bold text-primary">₹15,09,800</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-12</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Hyderabad</td>
                                    <td><span class="badge bg-gray-200 text-dark">42 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹8,24,500</span></td>
                                    <td><span class="fw-bold text-danger">₹10,000</span></td>
                                    <td><span class="fw-bold text-info">₹8,100</span></td>
                                    <td><span class="fw-bold text-primary">₹8,22,600</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-11</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Pune</td>
                                    <td><span class="badge bg-gray-200 text-dark">35 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹7,56,200</span></td>
                                    <td><span class="fw-bold text-danger">₹8,500</span></td>
                                    <td><span class="fw-bold text-info">₹7,400</span></td>
                                    <td><span class="fw-bold text-primary">₹7,55,100</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-10</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Ahmedabad</td>
                                    <td><span class="badge bg-gray-200 text-dark">48 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹11,67,300</span></td>
                                    <td><span class="fw-bold text-danger">₹14,000</span></td>
                                    <td><span class="fw-bold text-info">₹11,500</span></td>
                                    <td><span class="fw-bold text-primary">₹11,64,800</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-09</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Chennai</td>
                                    <td><span class="badge bg-gray-200 text-dark">40 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹9,89,500</span></td>
                                    <td><span class="fw-bold text-danger">₹11,000</span></td>
                                    <td><span class="fw-bold text-info">₹9,700</span></td>
                                    <td><span class="fw-bold text-primary">₹9,88,200</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-bar-chart-2"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">2024-01-08</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Branch Store - Kolkata</td>
                                    <td><span class="badge bg-gray-200 text-dark">33 orders</span></td>
                                    <td><span class="fw-bold text-dark">₹6,45,800</span></td>
                                    <td><span class="fw-bold text-danger">₹7,500</span></td>
                                    <td><span class="fw-bold text-info">₹6,300</span></td>
                                    <td><span class="fw-bold text-primary">₹6,44,600</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-download me-2"></i>Download</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-printer me-2"></i>Print</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Report">
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
        <!-- [Sales Reports] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

