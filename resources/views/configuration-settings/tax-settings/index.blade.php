@extends('layouts.app')

@section('title', 'Tax Settings')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Tax Settings</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Configuration Settings</li>
            <li class="breadcrumb-item">Tax Settings</li>
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
                <a href="javascript:void(0);" class="btn btn-primary btn-sm">
                    <i class="feather-plus me-2"></i>Add Tax
                </a>
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
                    <h5 class="card-title">Tax Settings List</h5>
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
                                    <th scope="row">Tax Name</th>
                                    <th>Tax Type</th>
                                    <th>Rate (%)</th>
                                    <th>CGST (%)</th>
                                    <th>SGST (%)</th>
                                    <th>IGST (%)</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-percent"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">GST 5%</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-primary text-primary">GST</span></td>
                                    <td>5.00%</td>
                                    <td>2.50%</td>
                                    <td>2.50%</td>
                                    <td>5.00%</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-percent"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">GST 12%</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-primary text-primary">GST</span></td>
                                    <td>12.00%</td>
                                    <td>6.00%</td>
                                    <td>6.00%</td>
                                    <td>12.00%</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-percent"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">GST 18%</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-primary text-primary">GST</span></td>
                                    <td>18.00%</td>
                                    <td>9.00%</td>
                                    <td>9.00%</td>
                                    <td>18.00%</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-percent"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">GST 28%</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-primary text-primary">GST</span></td>
                                    <td>28.00%</td>
                                    <td>14.00%</td>
                                    <td>14.00%</td>
                                    <td>28.00%</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-percent"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">No Tax</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-secondary text-secondary">None</span></td>
                                    <td>0.00%</td>
                                    <td>0.00%</td>
                                    <td>0.00%</td>
                                    <td>0.00%</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View">
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

