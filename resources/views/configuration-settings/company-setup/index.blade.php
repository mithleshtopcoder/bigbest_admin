@extends('layouts.app')

@section('title', 'Company Setup')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Company Setup</h5>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item">Configuration</li>
                <li class="breadcrumb-item active">Company Setup</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">

        {{-- TABS --}}
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#general">General</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#localization">Localization</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#social">Social</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#content">Content</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#delivery">Delivery</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment">Payment</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#mail">Mail</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#sms">SMS</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pos">POS</a></li>
            <li class="nav-item"><a class="nav-link text-danger" data-bs-toggle="tab" href="#maintenance">Maintenance</a></li>
        </ul>
        <div class="tab-content">

            {{-- ================= GENERAL ================= --}}
            <div class="tab-pane fade show active" id="general">
                <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tab" value="general">

                    <div class="row">
                        {{-- LEFT COLUMN --}}
                        <div class="col-md-6">
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Company Info</h6>

                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="form-label text-md col-md-4 custom-label">Company Name</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="text" name="company_name" class="form-control form-control-sm" value="{{ $settings->company_name ?? '' }}" placeholder="Enter company name">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="form-label text-md col-md-4 custom-label">Company Title</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="text" name="company_title" class="form-control form-control-sm" value="{{ $settings->company_title ?? '' }}" placeholder="Enter company title">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="form-label text-md col-md-4 custom-label">Website</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="text" name="url" class="form-control form-control-sm" value="{{ $settings->url ?? '' }}" placeholder="https://example.com">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2 align-items-start">
                                        <label class="form-label text-md col-md-4 custom-label">Address</label>
                                        <div class="col-md-8 pl-1">
                                            <textarea name="address" rows="7" class="form-control form-control-sm" placeholder="Enter address">{{ $settings->address ?? '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 align-items-start">
                                        <label class="form-label text-md col-md-4 custom-label">Header</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="file" name="header" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'headerPreview')">
                                            <div class="mt-2">
                                                <img id="headerPreview" src="{{ $settings->header ? asset('images/settings/' . $settings->header) : '' }}" class="border rounded" style="width:150px; height:80px; object-fit:cover; {{ $settings->header ? '' : 'display:none;' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Template Image -->
                                    <div class="form-group row mb-3 align-items-start">
                                        <label class="form-label text-md col-md-4 custom-label">Template</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="file" name="template" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'templatePreview')">
                                            <div class="mt-2">
                                                <img id="templatePreview" src="{{ $settings->template ? asset('images/settings/' . $settings->template) : '' }}" class="border rounded" style="width:150px; height:80px; object-fit:cover; {{ $settings->template ? '' : 'display:none;' }}">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- RIGHT COLUMN --}}
                        <div class="col-md-6">
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Contact & Branding</h6>

                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="form-label text-md col-md-4 custom-label">Email</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $settings->email ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-2 align-items-center">
                                        <label class="form-label text-md col-md-4 custom-label">Phone</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="text" name="phone" class="form-control form-control-sm" value="{{ $settings->phone ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row mb-3 align-items-start">
                                        <label class="form-label text-md col-md-4 custom-label">Logo</label>
                                        <div class="col-md-8 pl-1">
                                            <input type="file" name="logo" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                            {{-- <div class="border rounded p-2 text-center" style="height: 100px;">
                                                <img id="logoPreview" src="{{ $settings->logo ?? '' }}" style="max-height: 80px; max-width: 100%; {{ empty($settings->logo) ? 'display:none;' : 'display:block;' }}">
                                            <span class="text-muted small" id="logoPreviewPlaceholder" style="{{ empty($settings->logo) ? 'display:block;' : 'display:none;' }}">
                                                Logo preview
                                            </span>
                                        </div> --}}

                                        <div class="mt-2">
                                            <img id="logoPreview" src="{{ $settings->logo ? asset('images/settings/' . $settings->logo) : '' }}" class="border rounded" style="width:80px; height:80px; object-fit:contain; {{ $settings->logo ? '' : 'display:none;' }}">
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-start">
                                    <label class="form-label text-md col-md-4 custom-label">Favicon</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="file" name="favicon" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'faviconPreview')">
                                        <div class="mt-2">
                                            <img id="logoPreview" src="{{ $settings->small_logo ? asset('images/settings/' . $settings->small_logo) : '' }}" class="border rounded" style="width:80px; height:80px; object-fit:contain; {{ $settings->logo ? '' : 'display:none;' }}">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>

            <div class="text-end mt-3">
                <button class="btn btn-primary btn-sm">
                    <i class="feather-save me-1"></i> Save General
                </button>
            </div>
            </form>
        </div>

        {{-- ================= LOCALIZATION ================= --}}
        <div class="tab-pane fade" id="localization">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="localization">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Currency & Timezone</h6>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Currency</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="currency" class="form-control form-control-sm" placeholder="Currency" value="{{ $settings->currency ?? 'INR' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Symbol</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="currency_symbol" class="form-control form-control-sm" placeholder="Symbol" value="{{ $settings->currency_symbol ?? '₹' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Language & Timezone</h6>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Timezone</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="timezone" class="form-control form-control-sm" placeholder="Timezone" value="{{ $settings->timezone ?? 'Asia/Kolkata' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Language</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="language" class="form-control form-control-sm" placeholder="Language" value="{{ $settings->language ?? 'en' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm">
                        <i class="feather-save me-1"></i> Save Localization
                    </button>
                </div>
            </form>
        </div>

        {{-- ================= SOCIAL ================= --}}
        <div class="tab-pane fade" id="social">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="social">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Social Links (Left)</h6>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Facebook</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="facebook_url" placeholder="Facebook URL" value="{{ $settings->facebook_url ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Instagram</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="instagram_url" placeholder="Instagram URL" value="{{ $settings->instagram_url ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">LinkedIn</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="linkedin_url" placeholder="LinkedIn URL" value="{{ $settings->linkedin_url ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Social Links (Right)</h6>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Twitter</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="twitter_url" placeholder="Twitter URL" value="{{ $settings->twitter_url ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">YouTube</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="youtube_url" placeholder="YouTube URL" value="{{ $settings->youtube_url ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">WhatsApp</label>
                                    <div class="col-md-8 pl-1">
                                        <input class="form-control form-control-sm" name="whatsapp_number" placeholder="WhatsApp Number" value="{{ $settings->whatsapp_number ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save Social</button>
                </div>
            </form>
        </div>

        {{-- ================= CONTENT ================= --}}
        <div class="tab-pane fade" id="content">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">About Company</h6>
                                <div class="form-group">
                                    <textarea class="form-control" name="about_us" rows="8" placeholder="About Company">{{ $settings->about_us ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Terms & Conditions</h6>
                                <div class="form-group">
                                    <textarea class="form-control" name="description" rows="8" placeholder="Terms & Conditions">{{ $settings->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save Content</button>
                </div>
            </form>
        </div>

        {{-- ================= DELIVERY ================= --}}
        <div class="tab-pane fade" id="delivery">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="delivery">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Delivery Settings</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Minimum Order Amount</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="number" name="min_order_amount" class="form-control form-control-sm" value="{{ $settings->min_order_amount ?? 0 }}" placeholder="Minimum order amount">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">shipping Charge</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="number" name="delivery_charge" class="form-control form-control-sm" value="{{ $settings->delivery_charge ?? 0 }}" placeholder="Delivery charge">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Free Delivery Threshold</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="number" name="free_delivery_threshold" class="form-control form-control-sm" value="{{ $settings->free_delivery_threshold ?? 0 }}" placeholder="Free delivery above amount">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row mb-2 align-items-center">
                            <label class="form-label text-md col-md-4 custom-label">
                                Tax (%)
                            </label>
                            <div class="col-md-8 pl-1">
                                <input type="number" name="delivery_tax_percent" class="form-control form-control-sm" value="{{ $settings->delivery_tax_percent ?? 0 }}" placeholder="Tax percentage" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save Delivery</button>
                </div>
            </form>
        </div>

        {{-- ================= PAYMENT ================= --}}
        <div class="tab-pane fade" id="payment">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="payment">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Razorpay Settings</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Razorpay Key</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="razorpay_key" class="form-control form-control-sm" value="{{ $settings->razorpay_key ?? '' }}" placeholder="Razorpay Key">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Razorpay Secret</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="razorpay_secret" class="form-control form-control-sm" value="{{ $settings->razorpay_secret ?? '' }}" placeholder="Razorpay Secret">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Webhook Secret</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="razorpay_webhook_secret" class="form-control form-control-sm" value="{{ $settings->razorpay_webhook_secret ?? '' }}" placeholder="Webhook Secret">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Reserved for future payment fields --}}
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save Payment</button>
                </div>
            </form>
        </div>

        {{-- ================= MAIL ================= --}}
        <div class="tab-pane fade" id="mail">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="mail">

                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Mail Settings</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Mailer</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="mail_mailer" class="form-control form-control-sm" value="{{ $settings->mail_mailer ?? 'smtp' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Host</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="mail_host" class="form-control form-control-sm" value="{{ $settings->mail_host ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Port</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="number" name="mail_port" class="form-control form-control-sm" value="{{ $settings->mail_port ?? 587 }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Encryption</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="mail_encryption" class="form-control form-control-sm" value="{{ $settings->mail_encryption ?? 'tls' }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Credentials & From Address</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Username</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="mail_username" class="form-control form-control-sm" value="{{ $settings->mail_username ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Password</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="password" name="mail_password" class="form-control form-control-sm" value="{{ $settings->mail_password ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">From Address</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="email" name="mail_from_address" class="form-control form-control-sm" value="{{ $settings->mail_from_address ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">From Name</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="mail_from_name" class="form-control form-control-sm" value="{{ $settings->mail_from_name ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">BCC Address</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="email" name="mail_bcc_address" class="form-control form-control-sm" value="{{ $settings->mail_bcc_address ?? '' }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save Mail</button>
                </div>
            </form>
        </div>
        {{-- ================= SMS ================= --}}
        <div class="tab-pane fade" id="sms">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="sms">

                <div class="row">

                    {{-- LEFT COLUMN --}}
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">SMS Provider Settings</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Provider</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="sms_provider" class="form-control form-control-sm" value="{{ $settings->sms_provider ?? '' }}" placeholder="SMS Provider Name">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">API Key</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="sms_key" class="form-control form-control-sm" value="{{ $settings->sms_key ?? '' }}" placeholder="API Key">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">API Secret</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="sms_secret" class="form-control form-control-sm" value="{{ $settings->sms_secret ?? '' }}" placeholder="API Secret / Token">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Sender & Template</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Sender ID</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="sms_sender_id" class="form-control form-control-sm" value="{{ $settings->sms_sender_id ?? '' }}" placeholder="Sender ID">
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Template ID</label>
                                    <div class="col-md-8 pl-1">
                                        <input type="text" name="sms_template_id" class="form-control form-control-sm" value="{{ $settings->sms_template_id ?? '' }}" placeholder="Template ID">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save SMS</button>
                </div>
            </form>
        </div>

        {{-- ================= POS ================= --}}
        <div class="tab-pane fade" id="pos">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="pos">

                <div class="row">

                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">POS Settings</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">POS Status</label>
                                    <div class="col-md-8 pl-1">
                                        <select name="pos_enabled" class="form-control form-control-sm">
                                            <option value="1" {{ ($settings->pos_enabled ?? '') == 1 ? 'selected' : '' }}>Enabled</option>
                                            <option value="0" {{ ($settings->pos_enabled ?? '') == 0 ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Reserved for future POS fields --}}
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save POS</button>
                </div>
            </form>
        </div>

        {{-- ================= MAINTENANCE ================= --}}
        <div class="tab-pane fade" id="maintenance">
            <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="maintenance">

                <div class="row">

                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Maintenance Mode</h6>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Status</label>
                                    <div class="col-md-8 pl-1">
                                        <select name="maintenance_mode" class="form-control form-control-sm">
                                            <option value="0" {{ ($settings->maintenance_mode ?? 0) == 0 ? 'selected' : '' }}>Disable</option>
                                            <option value="1" {{ ($settings->maintenance_mode ?? 0) == 1 ? 'selected' : '' }}>Enable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-2 align-items-center">
                                    <label class="form-label text-md col-md-4 custom-label">Message</label>
                                    <div class="col-md-8 pl-1">
                                        <textarea name="maintenance_message" class="form-control form-control-sm" rows="4" placeholder="Maintenance message">{{ $settings->maintenance_message ?? '' }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        {{-- Reserved for future maintenance fields --}}
                    </div>

                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-danger btn-sm"><i class="feather-save me-1"></i> Save Maintenance</button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
@section('scripts')



<script>
    function previewImage(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(previewId + 'Placeholder');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.style.display = 'none';
            if (placeholder) placeholder.style.display = 'block';
        }
    }

</script>


@endsection
