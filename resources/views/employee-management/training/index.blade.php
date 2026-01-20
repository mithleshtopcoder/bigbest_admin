@extends('layouts.app')

@section('title', 'Training Management')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Training Management</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Employee Management</li>
            <li class="breadcrumb-item">Training</li>
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
                                <input type="checkbox" class="custom-control-input" id="TypeFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="TypeFilter">Training Type</label>
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
        <!-- [Training List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Training Programs List</h5>
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
                                    <th scope="row">Training Program</th>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Sales Training Program</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Rajesh Kumar</td>
                                    <td>Sales</td>
                                    <td>2024-01-20</td>
                                    <td>2024-01-25</td>
                                    <td><span class="badge bg-gray-200 text-dark">5 days</span></td>
                                    <td><span class="badge bg-soft-warning text-warning">Scheduled</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Customer Service Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Priya Sharma</td>
                                    <td>Sales</td>
                                    <td>2024-01-15</td>
                                    <td>2024-01-18</td>
                                    <td><span class="badge bg-gray-200 text-dark">3 days</span></td>
                                    <td><span class="badge bg-soft-success text-success">Completed</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Inventory Management Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Amit Patel</td>
                                    <td>Inventory</td>
                                    <td>2024-01-22</td>
                                    <td>2024-01-26</td>
                                    <td><span class="badge bg-gray-200 text-dark">4 days</span></td>
                                    <td><span class="badge bg-soft-warning text-warning">Scheduled</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">HR Policies Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Sneha Reddy</td>
                                    <td>HR</td>
                                    <td>2024-01-10</td>
                                    <td>2024-01-12</td>
                                    <td><span class="badge bg-gray-200 text-dark">2 days</span></td>
                                    <td><span class="badge bg-soft-success text-success">Completed</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Financial Management Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Vikram Singh</td>
                                    <td>Finance</td>
                                    <td>2024-01-25</td>
                                    <td>2024-01-30</td>
                                    <td><span class="badge bg-gray-200 text-dark">5 days</span></td>
                                    <td><span class="badge bg-soft-warning text-warning">Scheduled</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Product Knowledge Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Meera Joshi</td>
                                    <td>Sales</td>
                                    <td>2024-01-18</td>
                                    <td>2024-01-20</td>
                                    <td><span class="badge bg-gray-200 text-dark">2 days</span></td>
                                    <td><span class="badge bg-soft-success text-success">Completed</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Operations Excellence Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Rahul Verma</td>
                                    <td>Operations</td>
                                    <td>2024-01-28</td>
                                    <td>2024-02-02</td>
                                    <td><span class="badge bg-gray-200 text-dark">5 days</span></td>
                                    <td><span class="badge bg-soft-warning text-warning">Scheduled</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-book"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">POS System Training</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Anjali Desai</td>
                                    <td>Sales</td>
                                    <td>2024-01-12</td>
                                    <td>2024-01-14</td>
                                    <td><span class="badge bg-gray-200 text-dark">2 days</span></td>
                                    <td><span class="badge bg-soft-success text-success">Completed</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <div class="dropdown">
                                                <a href="javascript:void(0);" class="avatar-text avatar-sm" data-bs-toggle="dropdown" data-bs-offset="25, 25" title="Actions">
                                                    <i class="feather-check-circle text-success"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-edit me-2"></i>Edit</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-check me-2 text-success"></i>Complete</a>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i class="feather-x me-2 text-danger"></i>Cancel</a>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Training">
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
        <!-- [Training List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

