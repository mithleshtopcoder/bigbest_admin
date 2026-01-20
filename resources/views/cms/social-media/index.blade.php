@extends('layouts.app')

@section('title', 'Social Media')

@section('content')
<!-- [ page-header ] start -->
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Social Media</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item">CMS</li>
            <li class="breadcrumb-item">Social Media</li>
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
                    <h5 class="card-title">Social Media Links</h5>
                </div>
                <div class="card-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">Facebook URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-facebook"></i></span>
                                    <input type="url" class="form-control" id="facebook" name="facebook" value="https://facebook.com/realgoldforming" placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-instagram"></i></span>
                                    <input type="url" class="form-control" id="instagram" name="instagram" value="https://instagram.com/realgoldforming" placeholder="https://instagram.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter" class="form-label">Twitter URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-twitter"></i></span>
                                    <input type="url" class="form-control" id="twitter" name="twitter" value="https://twitter.com/realgoldforming" placeholder="https://twitter.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-linkedin"></i></span>
                                    <input type="url" class="form-control" id="linkedin" name="linkedin" value="https://linkedin.com/company/realgoldforming" placeholder="https://linkedin.com/company/yourcompany">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="youtube" class="form-label">YouTube URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-youtube"></i></span>
                                    <input type="url" class="form-control" id="youtube" name="youtube" value="https://youtube.com/@realgoldforming" placeholder="https://youtube.com/@yourchannel">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pinterest" class="form-label">Pinterest URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-pinterest"></i></span>
                                    <input type="url" class="form-control" id="pinterest" name="pinterest" value="https://pinterest.com/realgoldforming" placeholder="https://pinterest.com/yourpage">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-message-circle"></i></span>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="+91 98765 43210" placeholder="+91 98765 43210">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telegram" class="form-label">Telegram URL</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="feather-send"></i></span>
                                    <input type="url" class="form-control" id="telegram" name="telegram" value="https://t.me/realgoldforming" placeholder="https://t.me/yourchannel">
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" checked>
                                    <label class="form-check-label" for="status">Show Social Media Links</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-save me-2"></i>Save Changes
                            </button>
                            <button type="reset" class="btn btn-light">
                                <i class="feather-refresh-cw me-2"></i>Reset
                            </button>
                        </div>
                    </form>
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

