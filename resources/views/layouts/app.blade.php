<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="Real Gold Farming" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Page Title -->
    <title>@yield('title', 'Admin Panel - Store Management System')</title>

    <!-- Meta Styles -->
    @include('layouts.meta.meta-style')

    <!-- Additional Styles -->
    @yield('styles')
</head>
<body>
    {{-- <x-flash-message /> <!-- add here --> --}}
    @php
    use App\Helpers\MyHelper;
    @endphp

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Header -->
    @include('layouts.header')

    <!-- Main Content -->
    <main class="main-content" id="main-content">
        <div class="container-fluid">
            <x-flash-message />
            @yield('content')
        </div>

        <!-- Footer -->
        @include('layouts.footer')
    </main>

    <!-- Meta Scripts -->
    @include('layouts.meta.meta-script')

    @yield('scripts')

</body>
</html>
