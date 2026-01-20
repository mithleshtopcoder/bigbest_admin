@extends('layouts.app')

@section('title', 'Geo Location')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Geo Location</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">Store Management</li>
            <li class="breadcrumb-item">Geo Location</li>
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
                                <input type="checkbox" class="custom-control-input" id="CityFilter" checked="checked" />
                                <label class="custom-control-label c-pointer" for="CityFilter">City</label>
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
        <!-- [Geo Location List] start -->
        <div class="col-12">
            <div class="card stretch stretch-full">
                <div class="card-header">
                    <h5 class="card-title">Geo Location List</h5>
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
                                    <th scope="row">Store</th>
                                    <th>City</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Main Store - Mumbai</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Mumbai</td>
                                    <td><span class="fw-bold text-dark">19.0760° N</span></td>
                                    <td><span class="fw-bold text-dark">72.8777° E</span></td>
                                    <td>123, MG Road, Near Park</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Delhi</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Delhi</td>
                                    <td><span class="fw-bold text-dark">28.6139° N</span></td>
                                    <td><span class="fw-bold text-dark">77.2090° E</span></td>
                                    <td>456, Business Park, Sector 5</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Bangalore</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Bangalore</td>
                                    <td><span class="fw-bold text-dark">12.9716° N</span></td>
                                    <td><span class="fw-bold text-dark">77.5946° E</span></td>
                                    <td>789, Residency Road, Block A</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Hyderabad</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Hyderabad</td>
                                    <td><span class="fw-bold text-dark">17.3850° N</span></td>
                                    <td><span class="fw-bold text-dark">78.4867° E</span></td>
                                    <td>321, Main Street, Near Temple</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Pune</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Pune</td>
                                    <td><span class="fw-bold text-dark">18.5204° N</span></td>
                                    <td><span class="fw-bold text-dark">73.8567° E</span></td>
                                    <td>654, Corporate Tower, Floor 10</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Ahmedabad</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Ahmedabad</td>
                                    <td><span class="fw-bold text-dark">23.0225° N</span></td>
                                    <td><span class="fw-bold text-dark">72.5714° E</span></td>
                                    <td>987, Garden Colony, Phase 2</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Chennai</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Chennai</td>
                                    <td><span class="fw-bold text-dark">13.0827° N</span></td>
                                    <td><span class="fw-bold text-dark">80.2707° E</span></td>
                                    <td>147, Lake View Apartments, Block B</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
                                                <i class="feather-eye text-primary"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text">
                                                <i class="feather-map-pin"></i>
                                            </div>
                                            <a href="javascript:void(0);">
                                                <span class="d-block">Branch Store - Kolkata</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td>Kolkata</td>
                                    <td><span class="fw-bold text-dark">22.5726° N</span></td>
                                    <td><span class="fw-bold text-dark">88.3639° E</span></td>
                                    <td>258, Tech Park, Building 3</td>
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
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="View Location">
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
        <!-- [Geo Location List] end -->
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection

@section('scripts')
@endsection

@section('styles')
@endsection

