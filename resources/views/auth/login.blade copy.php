<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="WRAPCODERS">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ config('app.url') }}">
    <title> Login - {{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
</head>

<body>
    <main class="auth-creative-wrapper">
        <div class="auth-creative-inner">
            <div class="creative-card-wrapper">
                <div class="card my-4 overflow-hidden" style="z-index: 1">
                    <div class="row flex-1 g-0">
                        <div class="col-lg-6 h-100 my-auto order-1 order-lg-0">
                            <div style="width: 115px;border: 1px solid #1a6708;" class="bg-white p-1 shadow-sm position-absolute translate-middle top-50 start-50 d-none d-lg-block">
                                <img src="{{ asset('images/logo21.png') }}" alt="" class="img-fluid w-100">
                            </div>
                            <div class="creative-card-body card-body p-sm-5">
                                <h2 class="fs-20 fw-bolder mb-4" style="font-size: 42px;
    font-family: cursive;
    text-shadow: 3px 3px 3px #d1b402;
    color: #1a6708;">RG Organic Mart</h2>
                                {{-- <h2 class="fs-20 fw-bolder mb-4" style="background: #FFFFFF;
text-shadow: 0 1px #dabe11, -1px 0 #dabe11, -1px 2px #dabe11, -2px 1px #dabe11, -2px 3px #dabe11, -3px 2px #dabe11, -3px 4px #dabe11, -4px 3px #dabe11, -4px 5px #dabe11, -5px 4px #dabe11, -5px 6px #dabe11, -6px 5px #dabe11, -6px 7px #dabe11, -7px 6px #dabe11, -7px 8px #dabe11, -8px 7px #dabe11;
color: #1a6708;
background: #FFFFFF;font-size: 38px;">RG Organic Mart</h2> --}}
                                <h4 class="fs-13 fw-bold mb-2">Login to your account</h4>
                                <p class="fs-12 fw-medium text-muted">Welcome back! Please login to access the <strong>RG Organic Mart</strong> admin panel.</p>

                                <form action="{{ route('login') }}" method="POST" class="w-100 mt-4 pt-2">
                                    @csrf
                                    <div class="mb-4">
                                        <input type="email" class="form-control" style="border:1px solid #1a6708;height: 39px;" placeholder="Email or Username" name="email" required>
                                    </div>
                                    <div class="mb-3">
                                        <input type="password" class="form-control" style="border:1px solid #1a6708;height: 39px;" placeholder="Password" name="password" required>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="rememberMe">
                                                <label class="custom-control-label c-pointer" for="rememberMe">Remember Me</label>
                                            </div>
                                        </div>
                                        <div>
                                            {{-- <a href="{{ route('password.request') }}" class="fs-11 text-primary">Forget password?</a> --}}
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <button type="submit" class="btn btn-lg btn-primary w-100" style="background: #1a6708; border: 1px solid #1a6708">Login</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                        <div class="col-lg-6 bg-primary order-0 order-lg-1">
                            <div class="h-100 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('images/login1.jpg') }}" alt="" class="img-fluid" style="height: 100%; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script>
</body>

</html>
