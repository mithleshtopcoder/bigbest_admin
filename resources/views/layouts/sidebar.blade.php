<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand d-flex align-items-center text-decoration-none text-white">
            <img src="{{ asset('images/logo21.png') }}" alt="Logo" class="sidebar-logo logo-lg" />
            <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="Logo" class="sidebar-logo logo-sm" style="display: none;" />
        </a>
        <button class="btn btn-link text-white p-0 d-md-none" id="close-sidebar" style="font-size: 1.25rem;">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        @include('layouts.menu')
    </nav>
</aside>
