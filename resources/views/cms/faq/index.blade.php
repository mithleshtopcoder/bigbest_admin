@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Frequently Asked Questions (FAQ)</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">CMS</li>
            <li class="breadcrumb-item">FAQ</li>
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
                    <i class="feather-plus me-2"></i>Add FAQ
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
                    <h5 class="card-title">FAQ List</h5>
                </div>
                <div class="card-body custom-card-action p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="border-b">
                                    <th scope="row">S.No.</th>
                                    <th>Question</th>
                                    <th>Category</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>What is the purity of gold used in your jewelry?</td>
                                    <td><span class="badge bg-soft-primary text-primary">Product</span></td>
                                    <td>1</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Delete">
                                                <i class="feather-trash-2 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Do you provide certificates of authenticity?</td>
                                    <td><span class="badge bg-soft-info text-info">General</span></td>
                                    <td>2</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Delete">
                                                <i class="feather-trash-2 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>What is your return and exchange policy?</td>
                                    <td><span class="badge bg-soft-warning text-warning">Policy</span></td>
                                    <td>3</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Delete">
                                                <i class="feather-trash-2 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>How long does delivery take?</td>
                                    <td><span class="badge bg-soft-success text-success">Shipping</span></td>
                                    <td>4</td>
                                    <td><span class="badge bg-soft-success text-success">Active</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Delete">
                                                <i class="feather-trash-2 text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Do you offer customization services?</td>
                                    <td><span class="badge bg-soft-primary text-primary">Product</span></td>
                                    <td>5</td>
                                    <td><span class="badge bg-soft-warning text-warning">Inactive</span></td>
                                    <td class="text-end">
                                        <div class="d-flex align-items-center gap-2 justify-content-end">
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Edit">
                                                <i class="feather-edit text-primary"></i>
                                            </a>
                                            <a href="javascript:void(0);" class="avatar-text avatar-sm" title="Delete">
                                                <i class="feather-trash-2 text-danger"></i>
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

