<header class="main-header" id="main-header">
    <div class="d-flex align-items-center w-100">
        <!-- Mobile Sidebar Toggle -->
        <button class="header-toggle d-md-none me-3" id="mobile-sidebar-toggle" type="button">
            <i class="bi bi-list"></i>
        </button>

        <!-- Desktop Sidebar Toggle -->
        <button class="header-toggle d-none d-md-block me-3" id="sidebar-toggle" type="button">
            <i class="bi bi-list"></i>
        </button>

        <!-- Header Right -->
        <div class="ms-auto d-flex align-items-center gap-3">
            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none text-dark p-2" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;margin-top: 12px;margin-left: -9px;">
                        3
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    <li><a class="dropdown-item" href="#">New order received</a></li>
                    <li><a class="dropdown-item" href="#">Payment received</a></li>
                    <li><a class="dropdown-item" href="#">Low stock alert</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-center" href="#">View all</a></li>
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none text-dark p-2 d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.875rem;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="bi bi-chevron-down d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

