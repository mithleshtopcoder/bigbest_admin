@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Roles & Permissions</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Configuration Settings</li>
            <li class="breadcrumb-item">Roles & Permissions</li>
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
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Roles & Permissions</h5>
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
                                    <th scope="row">Role ID</th>
                                    <th>Role Name</th>
                                    <th>Users</th>
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-001</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Super Admin</td>
                                    <td><span class="badge bg-gray-200 text-dark">2 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">All Permissions</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-002</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Store Manager</td>
                                    <td><span class="badge bg-gray-200 text-dark">15 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Store Management</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-003</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Sales Executive</td>
                                    <td><span class="badge bg-gray-200 text-dark">25 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Sales & Orders</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-004</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Inventory Manager</td>
                                    <td><span class="badge bg-gray-200 text-dark">8 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Inventory Management</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-005</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Cashier</td>
                                    <td><span class="badge bg-gray-200 text-dark">30 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">POS Operations</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-006</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Accountant</td>
                                    <td><span class="badge bg-gray-200 text-dark">5 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Finance & Reports</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-007</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>HR Manager</td>
                                    <td><span class="badge bg-gray-200 text-dark">3 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Employee Management</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">#ROLE-008</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Viewer</td>
                                    <td><span class="badge bg-gray-200 text-dark">10 users</span></td>
                                    <td><span class="badge bg-gray-200 text-dark">Read Only</span></td>
                                    <td><span class="badge bg-soft-warning text-warning">Inactive</span></td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Role">
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
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

