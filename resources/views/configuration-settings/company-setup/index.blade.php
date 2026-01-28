@extends('layouts.app')

@section('title', 'Company Setup')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div class="page-header-left d-flex" style="align-items: baseline;">
            <h1 class="page-title mb-0">Company Setup</h1>
            <nav aria-label="breadcrumb" class="px-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item text-muted">Configuration Settings</li>
                    <li class="breadcrumb-item active" aria-current="page">Company Setup</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="main-body">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#general">General</a></li>
                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#localization">Localization</a></li> --}}
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#social">Social</a></li>
                {{-- <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#content">Content</a></li> --}}
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#delivery">Delivery Settings</a>
                </li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#payment">Payment</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#mail">Mail</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#sms">SMS</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#whatsapp">WhatsApp</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pos">POS</a></li>
                <li class="nav-item"><a class="nav-link text-danger" data-bs-toggle="tab" href="#maintenance">Maintenance</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="general">
                    <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tab" value="general">
                        <div class="row ps-2">
                            <div class="col-md-6">
                                <h6 class="mb-3">Company Info</h6>
                                <div class="form-group row">
                                    <label class="form-label col-md-4">Company Name</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="text" name="company_name" class="form-control form-control-sm" value="{{ $settings->company_name ?? '' }}" placeholder="Enter company name">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-4">Company Title</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="text" name="company_title" class="form-control form-control-sm" value="{{ $settings->company_title ?? '' }}" placeholder="Enter company title">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-4">Website</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="text" name="url" class="form-control form-control-sm" value="{{ $settings->url ?? '' }}" placeholder="https://example.com">
                                    </div>
                                </div>

                                <div class="form-group row align-items-start">
                                    <label class="form-label col-md-4">Address</label>
                                    <div class="col-md-8 ps-1">
                                        <textarea name="address" rows="7" class="form-control form-control-sm" placeholder="Enter address">{{ $settings->address ?? '' }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group row mb-3 align-items-start">
                                    <label class="form-label col-md-4">Header</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="file" name="header" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'headerPreview')">
                                        <div class="mt-2">
                                            <img id="headerPreview" src="{{ $settings->header ? asset('images/settings/' . $settings->header) : '' }}" class="border rounded" style="width:150px; height:80px; object-fit:cover; {{ $settings->header ? '' : 'display:none;' }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Template Image -->
                                <div class="form-group row mb-3 align-items-start">
                                    <label class="form-label col-md-4">Template</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="file" name="template" class="form-control form-control-sm mb-2" accept="image/*" onchange="previewImage(this, 'templatePreview')">
                                        <div class="mt-2">
                                            <img id="templatePreview" src="{{ $settings->template ? asset('images/settings/' . $settings->template) : '' }}" class="border rounded" style="width:150px; height:80px; object-fit:cover; {{ $settings->template ? '' : 'display:none;' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN --}}
                            <div class="col-md-6">
                                <h6 class="mb-3">Contact & Branding</h6>

                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-4">Email</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="email" name="email" class="form-control form-control-sm" value="{{ $settings->email ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-4">Phone</label>
                                    <div class="col-md-8 ps-1">
                                        <input type="text" name="phone" class="form-control form-control-sm" value="{{ $settings->phone ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4 ps-0 pe-0">
                                        <label class="form-label col-md-12 ps-2">Logo</label>
                                        <label class="form-label col-md-12 ps-2">Favicon</label>
                                    </div>
                                    <div class="col-md-4 ps-1 pe-0">
                                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*" onchange="previewImage(this, 'logoPreview')">
                                        <input type="file" name="favicon" class="form-control form-control-sm mt-1" accept="image/*" onchange="previewImage(this, 'faviconPreview')">
                                    </div>
                                    <div class="col-md-4 ps-1 pe-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <img id="logoPreview" src="{{ $settings->logo ? asset('images/settings/' . $settings->logo) : '' }}" class="border rounded" style="width:70px; height:70px; object-fit:contain; {{ $settings->logo ? '' : 'display:none;' }}">
                                            </div>
                                            <div class="col-md-6">
                                                <img id="logoPreview" src="{{ $settings->small_logo ? asset('images/settings/' . $settings->small_logo) : '' }}" class="border rounded" style="width:70px; height:70px; object-fit:contain; {{ $settings->logo ? '' : 'display:none;' }}">
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
                {{-- <div class="tab-pane fade" id="localization">
                        <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                @csrf
                <input type="hidden" name="tab" value="localization">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">Currency & Timezone</h6>
                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-5">Currency</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="currency" class="form-control form-control-sm" placeholder="Currency" value="{{ $settings->currency ?? 'INR' }}">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-5">Symbol</label>
                                    <div class="col-md-7 ps-1">
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
                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-5">Timezone</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="timezone" class="form-control form-control-sm" placeholder="Timezone" value="{{ $settings->timezone ?? 'Asia/Kolkata' }}">
                                    </div>
                                </div>
                                <div class="form-group row align-items-center">
                                    <label class="form-label col-md-5">Language</label>
                                    <div class="col-md-7 ps-1">
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
            </div> --}}

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
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">Facebook</label>
                                        <div class="col-md-7 ps-1">
                                            <input class="form-control form-control-sm" name="facebook_url" placeholder="Facebook URL" value="{{ $settings->facebook_url ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">Instagram</label>
                                        <div class="col-md-7 ps-1">
                                            <input class="form-control form-control-sm" name="instagram_url" placeholder="Instagram URL" value="{{ $settings->instagram_url ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">LinkedIn</label>
                                        <div class="col-md-7 ps-1">
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
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">Twitter</label>
                                        <div class="col-md-7 ps-1">
                                            <input class="form-control form-control-sm" name="twitter_url" placeholder="Twitter URL" value="{{ $settings->twitter_url ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">YouTube</label>
                                        <div class="col-md-7 ps-1">
                                            <input class="form-control form-control-sm" name="youtube_url" placeholder="YouTube URL" value="{{ $settings->youtube_url ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="form-label col-md-5">WhatsApp</label>
                                        <div class="col-md-7 ps-1">
                                            <input class="form-control form-control-sm" name="whatsapp_number" placeholder="WhatsApp Number" value="{{ $settings->whatsapp_number ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save
                            Social</button>
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
                        <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save
                            Content</button>
                    </div>
                </form>
            </div>

            {{-- ================= DELIVERY ================= --}}
            <div class="tab-pane fade" id="delivery">
                <form method="POST" action="{{ route('configuration-settings.company-setup.store') }}">
                    @csrf
                    <input type="hidden" name="tab" value="delivery">

                    <div class="cal-md-12">
                        <div class="col-md-6 mx-auto">
                            <div class="form-group row">
                                <label class="form-label col-md-5">Minimum Order
                                    Amount</label>
                                <div class="col-md-7 ps-1">
                                    <input type="number" name="min_order_amount" class="form-control form-control-sm" value="{{ $settings->min_order_amount ?? 0 }}" placeholder="Minimum order amount">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-5">shipping Charge</label>
                                <div class="col-md-7 ps-1">
                                    <input type="number" name="delivery_charge" class="form-control form-control-sm" value="{{ $settings->delivery_charge ?? 0 }}" placeholder="Delivery charge">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="form-label col-md-5">Free Delivery
                                    Threshold</label>
                                <div class="col-md-7 ps-1">
                                    <input type="number" name="free_delivery_threshold" class="form-control form-control-sm" value="{{ $settings->free_delivery_threshold ?? 0 }}" placeholder="Free delivery above amount">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-label col-md-5">
                                    Tax (%)
                                </label>
                                <div class="col-md-7 ps-1">
                                    <input type="number" name="delivery_tax_percent" class="form-control form-control-sm" value="{{ $settings->delivery_tax_percent ?? 0 }}" placeholder="Tax percentage" step="0.01" min="0">
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-save me-1"></i> Save Delivery
                        </button>
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

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Razorpay Key</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="razorpay_key" class="form-control form-control-sm" value="{{ $settings->razorpay_key ?? '' }}" placeholder="Razorpay Key">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Razorpay Secret</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="razorpay_secret" class="form-control form-control-sm" value="{{ $settings->razorpay_secret ?? '' }}" placeholder="Razorpay Secret">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Webhook Secret</label>
                                        <div class="col-md-7 ps-1">
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
                        <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save
                            Payment</button>
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

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Mailer</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="mail_mailer" class="form-control form-control-sm" value="{{ $settings->mail_mailer ?? 'smtp' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Host</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="mail_host" class="form-control form-control-sm" value="{{ $settings->mail_host ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Port</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="number" name="mail_port" class="form-control form-control-sm" value="{{ $settings->mail_port ?? 587 }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Encryption</label>
                                        <div class="col-md-7 ps-1">
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

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Username</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="mail_username" class="form-control form-control-sm" value="{{ $settings->mail_username ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Password</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="password" name="mail_password" class="form-control form-control-sm" value="{{ $settings->mail_password ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">From Address</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="email" name="mail_from_address" class="form-control form-control-sm" value="{{ $settings->mail_from_address ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">From Name</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="mail_from_name" class="form-control form-control-sm" value="{{ $settings->mail_from_name ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">BCC Address</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="email" name="mail_bcc_address" class="form-control form-control-sm" value="{{ $settings->mail_bcc_address ?? '' }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button class="btn btn-primary btn-sm"><i class="feather-save me-1"></i> Save
                            Mail</button>
                    </div>
                </form>
            </div>
            {{-- ================= SMS ================= --}}
            <div class="tab-pane fade" id="sms">
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="{{ isset($smsProvider) && $smsProvider ? route('configuration-settings.communication-providers.update', $smsProvider->id) : route('configuration-settings.communication-providers.store') }}">
                            @csrf
                            @if(isset($smsProvider) && $smsProvider)
                            @method('PUT')
                            @endif
                            <input type="hidden" name="channel" value="sms">
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">SMS Provider Settings</h6>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Base URL</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="base_url" class="form-control form-control-sm" value="{{ $smsProvider->base_url ?? '' }}" placeholder="https://.../http-api.php">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Provider Name</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="provider_name" class="form-control form-control-sm" value="{{ $smsProvider->provider_name ?? '' }}" placeholder="e.g. WebTechSolution, Twilio">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Username / API Key</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="api_key" class="form-control form-control-sm" value="{{ $smsProvider->api_key ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Password / API Secret</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="api_secret" class="form-control form-control-sm" value="{{ $smsProvider->api_secret ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Sender ID</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="sender_id" class="form-control form-control-sm" value="{{ $smsProvider->sender_id ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Route</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="route" class="form-control form-control-sm" value="{{ $smsProvider->route ?? '' }}" placeholder="e.g. 4">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="form-label col-md-5">Default Template ID</label>
                                        <div class="col-md-7 ps-1">
                                            <input type="text" name="default_template_id" class="form-control form-control-sm" value="{{ $smsProvider->default_template_id ?? '' }}" placeholder="Optional provider template id">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button class="btn btn-primary btn-sm">
                                    <i class="feather-save me-1"></i> Save SMS
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">SMS Templates</h6>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#smsTemplateCreateModal">
                                        <i class="feather-plus me-1"></i> Add Template
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 20%;">Name</th>
                                                <th style="width: 15%;">Key</th>
                                                <th style="width: 15%;">Template ID</th>
                                                <th style="width: 10%;">Status</th>
                                                {{-- <th>Message</th> --}}
                                                <th style="width: 20%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse(($smsTemplates ?? []) as $tpl)
                                            <tr>
                                                <td>{{ $tpl->name }}</td>
                                                <td><code>{{ $tpl->key }}</code></td>
                                                <td>{{ $tpl->provider_template_id ?? '-' }}</td>
                                                <td>
                                                    @if($tpl->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                    @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                {{-- <td style="max-width: 260px;">
                                                                <div class="text-truncate" title="{{ $tpl->message }}">
                                                {{ $tpl->message }}
                                </div>
                                </td> --}}
                                <td>
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#smsTemplateEditModal{{ $tpl->id }}">
                                        Edit
                                    </button>
                                    <form method="POST" action="{{ route('configuration-settings.sms-templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Delete this SMS template?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No templates found.</td>
                                </tr>
                                @endforelse
                                </tbody>
                                </table>
                            </div>

                            {{-- Edit Modals (outside table for valid HTML) --}}
                            @foreach(($smsTemplates ?? []) as $tpl)
                            <div class="modal fade" id="smsTemplateEditModal{{ $tpl->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('configuration-settings.sms-templates.update', $tpl->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="channel" value="sms">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit SMS Template</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $tpl->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Key</label>
                                                            <input type="text" name="key" class="form-control form-control-sm" value="{{ $tpl->key }}" required>
                                                            <small class="text-muted">Use lowercase, numbers and underscore only.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Provider Template ID</label>
                                                            <input type="text" name="provider_template_id" class="form-control form-control-sm" value="{{ $tpl->provider_template_id ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Active</label>
                                                            <select name="is_active" class="form-control form-control-sm">
                                                                <option value="1" {{ $tpl->is_active ? 'selected' : '' }}>Yes</option>
                                                                <option value="0" {{ !$tpl->is_active ? 'selected' : '' }}>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label">Message</label>
                                                    <textarea name="message" class="form-control form-control-sm" rows="5" required>{{ $tpl->message }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Create Modal --}}
            <div class="modal fade" id="smsTemplateCreateModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('configuration-settings.sms-templates.store') }}">
                            @csrf
                            <input type="hidden" name="channel" value="sms">
                            <div class="modal-header">
                                <h5 class="modal-title">Add SMS Template</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">Key</label>
                                            <input type="text" name="key" class="form-control form-control-sm" placeholder="e.g. order_confirmation" required>
                                            <small class="text-muted">Use lowercase, numbers and underscore only.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">Provider Template ID</label>
                                            <input type="text" name="provider_template_id" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <label class="form-label">Active</label>
                                            <select name="is_active" class="form-control form-control-sm">
                                                <option value="1" selected>Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" class="form-control form-control-sm" rows="5" placeholder="Write SMS message with placeholders" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-sm">Create</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= whatsapp ================= --}}
        <div class="tab-pane fade" id="whatsapp">
            <div class="row">
                <div class="col-md-6">
                    <form method="POST" action="{{ isset($whatsappProvider) && $whatsappProvider ? route('configuration-settings.communication-providers.update', $whatsappProvider->id) : route('configuration-settings.communication-providers.store') }}">
                        @csrf
                        @if(isset($whatsappProvider) && $whatsappProvider)
                        @method('PUT')
                        @endif
                        <input type="hidden" name="channel" value="whatsapp">

                        <div class="card border mb-3">
                            <div class="card-body">
                                <h6 class="mb-3">WhatsApp Provider Settings</h6>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Base URL</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="base_url" class="form-control form-control-sm" value="{{ $whatsappProvider->base_url ?? '' }}" placeholder="https://api.whatsapp...">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Provider Name</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="provider_name" class="form-control form-control-sm" value="{{ $whatsappProvider->provider_name ?? '' }}" placeholder="e.g. Cloud API, Gupshup, Interakt">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">API Key / Token</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="api_key" class="form-control form-control-sm" value="{{ $whatsappProvider->api_key ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">API Secret</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="api_secret" class="form-control form-control-sm" value="{{ $whatsappProvider->api_secret ?? '' }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Sender / From</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="sender_id" class="form-control form-control-sm" value="{{ $whatsappProvider->sender_id ?? '' }}" placeholder="Phone number / sender id">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Route / Instance</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="route" class="form-control form-control-sm" value="{{ $whatsappProvider->route ?? '' }}" placeholder="Optional">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Default Template ID</label>
                                    <div class="col-md-7 ps-1">
                                        <input type="text" name="default_template_id" class="form-control form-control-sm" value="{{ $whatsappProvider->default_template_id ?? '' }}" placeholder="Optional provider template id">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button class="btn btn-success btn-sm">
                                <i class="feather-save me-1"></i> Save WhatsApp
                            </button>
                        </div>
                    </form>
                </div>

                <div class="col-md-6">
                    <div class="card border mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">WhatsApp Templates</h6>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#whatsappTemplateCreateModal">
                                    <i class="feather-plus me-1"></i> Add Template
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 20%;">Name</th>
                                            <th style="width: 15%;">Key</th>
                                            <th style="width: 15%;">Template ID</th>
                                            <th style="width: 10%;">Status</th>
                                            <th>Message</th>
                                            <th style="width: 20%;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($whatsappTemplates ?? []) as $tpl)
                                        <tr>
                                            <td>{{ $tpl->name }}</td>
                                            <td><code>{{ $tpl->key }}</code></td>
                                            <td>{{ $tpl->provider_template_id ?? '-' }}</td>
                                            <td>
                                                @if($tpl->is_active)
                                                <span class="badge bg-success">Active</span>
                                                @else
                                                <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td style="max-width: 260px;">
                                                <div class="text-truncate" title="{{ $tpl->message }}">
                                                    {{ $tpl->message }}
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#whatsappTemplateEditModal{{ $tpl->id }}">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('configuration-settings.sms-templates.destroy', $tpl->id) }}" class="d-inline" onsubmit="return confirm('Delete this WhatsApp template?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No templates found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Edit Modals --}}
                            @foreach(($whatsappTemplates ?? []) as $tpl)
                            <div class="modal fade" id="whatsappTemplateEditModal{{ $tpl->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('configuration-settings.sms-templates.update', $tpl->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="channel" value="whatsapp">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit WhatsApp Template</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control form-control-sm" value="{{ $tpl->name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Key</label>
                                                            <input type="text" name="key" class="form-control form-control-sm" value="{{ $tpl->key }}" required>
                                                            <small class="text-muted">Use lowercase, numbers and underscore only.</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Provider Template ID</label>
                                                            <input type="text" name="provider_template_id" class="form-control form-control-sm" value="{{ $tpl->provider_template_id ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label">Active</label>
                                                            <select name="is_active" class="form-control form-control-sm">
                                                                <option value="1" {{ $tpl->is_active ? 'selected' : '' }}>Yes</option>
                                                                <option value="0" {{ !$tpl->is_active ? 'selected' : '' }}>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-2">
                                                    <label class="form-label">Message</label>
                                                    <textarea name="message" class="form-control form-control-sm" rows="5" required>{{ $tpl->message }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Create Modal --}}
                    <div class="modal fade" id="whatsappTemplateCreateModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('configuration-settings.sms-templates.store') }}">
                                    @csrf
                                    <input type="hidden" name="channel" value="whatsapp">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add WhatsApp Template</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control form-control-sm" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">Key</label>
                                                    <input type="text" name="key" class="form-control form-control-sm" placeholder="e.g. order_confirmation" required>
                                                    <small class="text-muted">Use lowercase, numbers and underscore only.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">Provider Template ID</label>
                                                    <input type="text" name="provider_template_id" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-2">
                                                    <label class="form-label">Active</label>
                                                    <select name="is_active" class="form-control form-control-sm">
                                                        <option value="1" selected>Yes</option>
                                                        <option value="0">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Message</label>
                                            <textarea name="message" class="form-control form-control-sm" rows="5" placeholder="Write WhatsApp message with placeholders" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success btn-sm">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <div class="form-group row">
                                    <label class="form-label col-md-5">POS Status</label>
                                    <div class="col-md-7 ps-1">
                                        <select name="pos_enabled" class="form-control form-control-sm">
                                            <option value="1" {{ ($settings->pos_enabled ?? '') == 1 ? 'selected' : '' }}>
                                                Enabled
                                            </option>
                                            <option value="0" {{ ($settings->pos_enabled ?? '') == 0 ? 'selected' : '' }}>
                                                Disabled</option>
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

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Status</label>
                                    <div class="col-md-7 ps-1">
                                        <select name="maintenance_mode" class="form-control form-control-sm">
                                            <option value="0" {{ ($settings->maintenance_mode ?? 0) == 0 ? 'selected' : '' }}>
                                                Disable</option>
                                            <option value="1" {{ ($settings->maintenance_mode ?? 0) == 1 ? 'selected' : '' }}>
                                                Enable</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="form-label col-md-5">Message</label>
                                    <div class="col-md-7 ps-1">
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
                    <button class="btn btn-danger btn-sm"><i class="feather-save me-1"></i> Save
                        Maintenance</button>
                </div>
            </form>
        </div>

    </div>
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

    // Keep selected tab after redirects (?tab=sms etc.)
    (function() {
        const params = new URLSearchParams(window.location.search);
        const tab = params.get('tab');
        if (!tab) return;
        const triggerEl = document.querySelector(`a[data-bs-toggle="tab"][href="#${tab}"]`);
        if (triggerEl && window.bootstrap && window.bootstrap.Tab) {
            new window.bootstrap.Tab(triggerEl).show();
        }
    })();

</script>


@endsection
