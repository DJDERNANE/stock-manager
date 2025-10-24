<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 60px;
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8fafc;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-height);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            padding: 0 1.5rem;
        }

        .topbar .navbar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        .topbar .navbar-brand:hover {
            color: white;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background-color: var(--sidebar-bg);
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1020;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            margin: 0.25rem 0.75rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .sidebar-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            transform: translateX(3px);
        }

        .sidebar-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar-link i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--topbar-height));
        }

        /* User Dropdown */
        .user-dropdown {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 0.4rem 1rem;
            transition: all 0.3s;
        }

        .user-dropdown:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-dropdown .dropdown-toggle {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-dropdown .dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: var(--topbar-height);
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1010;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Cards */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Footer */
        footer {
            margin-left: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #cbd5e1;
        }

        @media (max-width: 768px) {
            footer {
                margin-left: 0;
            }
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            background: transparent;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-custom .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Topbar -->
    <nav class="topbar navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="sidebar-toggle d-lg-none me-3" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-layers me-2"></i>{{ config('app.name', 'Laravel') }}
            </a>

            <div class="ms-auto">
                <ul class="navbar-nav">
                    @auth
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear me-2"></i>Settings
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm me-2" href="{{ route('login-form') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm" href="{{ route('signup-form') }}">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>


    <!-- Sidebar -->
    @auth
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h6 class="text-white-50 text-uppercase mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">
                Navigation
            </h6>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </div>
            <div class="sidebar-item">
                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                    <span>Products</span>
                </a>
            </div>
        </nav>
    </aside>
    @endauth


    <!-- Main Content -->
    <main class="main-content">
        <!-- Alerts -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif
        <div class="container">
            @yield('content')
        </div>

    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none me-3" style="color: #94a3b8;">Privacy Policy</a>
                    <a href="#" class="text-decoration-none me-3" style="color: #94a3b8;">Terms of Service</a>
                    <a href="#" class="text-decoration-none" style="color: #94a3b8;">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }

        // Close sidebar when clicking on a link (mobile)
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    toggleSidebar();
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>