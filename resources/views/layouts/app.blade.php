<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .badge-status {
            font-size: 0.75rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            @if(auth()->guard('admin')->check() || auth()->guard('house_owner')->check())
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <div class="text-center mb-4">
                            <h5 class="text-white">{{ config('app.name') }}</h5>
                            <small class="text-white-50">
                                @if(auth()->guard('admin')->check())
                                    Admin Panel
                                @else
                                    House Owner Panel
                                @endif
                            </small>
                        </div>

                        <ul class="nav flex-column">
                            @if(auth()->guard('admin')->check())
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-house-door me-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.house-owners.*') ? 'active' : '' }}" href="{{ route('admin.house-owners.index') }}">
                                        <i class="bi bi-people me-2"></i>
                                        House Owners
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}" href="{{ route('admin.tenants.index') }}">
                                        <i class="bi bi-person-badge me-2"></i>
                                        Residents
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.dashboard') ? 'active' : '' }}" href="{{ route('house_owner.dashboard') }}">
                                        <i class="bi bi-house-door me-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.buildings.*') ? 'active' : '' }}" href="{{ route('house_owner.buildings.index') }}">
                                        <i class="bi bi-building me-2"></i>
                                        Buildings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.flats.*') ? 'active' : '' }}" href="{{ route('house_owner.flats.index') }}">
                                        <i class="bi bi-door-open me-2"></i>
                                        Flats
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.bill-categories.*') ? 'active' : '' }}" href="{{ route('house_owner.bill-categories.index') }}">
                                        <i class="bi bi-tags me-2"></i>
                                        Bill Categories
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.bills.*') ? 'active' : '' }}" href="{{ route('house_owner.bills.index') }}">
                                        <i class="bi bi-receipt me-2"></i>
                                        Bills
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('house_owner.bills.overdue') ? 'active' : '' }}" href="{{ route('house_owner.bills.overdue') }}">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Overdue Bills
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <hr class="text-white-50">
                        
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i>
                                <span>
                                    @if(auth()->guard('admin')->check())
                                        {{ auth()->guard('admin')->user()->name }}
                                    @else
                                        {{ auth()->guard('house_owner')->user()->name }}
                                    @endif
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                                <li>
                                    <form method="POST" action="@if(auth()->guard('admin')->check()) {{ route('admin.logout') }} @else {{ route('house_owner.logout') }} @endif">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            @else
                <!-- Full width for guest pages -->
                <main class="col-12">
            @endif

                <div class="py-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>