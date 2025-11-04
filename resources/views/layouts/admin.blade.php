<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - GRIT Fitness</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Admin CSS -->
    <style>
        :root {
            --admin-primary: #2B3282; /* Biru tua */
            --admin-accent: #E51B83;  /* Pink */
            --admin-bg: #F4F7FC;      /* Background abu-abu muda */
            --admin-text: #333333;
            --admin-text-light: #717182;
            --admin-border: #E0E0E0;
            --admin-sidebar-bg: #FFFFFF; /* Sidebar putih */
            --admin-sidebar-text: #717182; /* Text abu-abu */
            --admin-sidebar-active: #2B3282; /* Biru untuk link aktif */
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text);
        }
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 260px;
            background-color: var(--admin-sidebar-bg);
            color: var(--admin-sidebar-text);
            padding: 24px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--admin-border);
        }
        .admin-sidebar-header {
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
            color: var(--admin-primary);
        }
        .admin-sidebar-nav {
            flex-grow: 1;
        }
        .admin-sidebar-nav .nav-link {
            color: var(--admin-sidebar-text);
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 400;
        }
        .admin-sidebar-nav .nav-link i {
            margin-right: 1rem;
            font-size: 1.1rem;
            width: 20px;
        }
        .admin-sidebar-nav .nav-link:hover {
            background-color: #F4F7FC;
            color: var(--admin-primary);
        }
        .admin-sidebar-nav .nav-link.active {
            background-color: var(--admin-sidebar-active);
            color: white;
            font-weight: 500;
        }
        .admin-sidebar-footer {
            border-top: 1px solid var(--admin-border);
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .admin-sidebar-footer .nav-link {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: var(--admin-accent);
        }
        .admin-sidebar-footer .nav-link:hover {
            opacity: 0.8;
        }
        .admin-sidebar-footer button {
            padding: 0.75rem 1rem;
            font-weight: 500;
            color: var(--admin-accent);
            display: flex;
            align-items: center;
        }
        .admin-sidebar-footer button i {
            margin-right: 0.5rem;
        }
        .admin-content {
            flex-grow: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        
        /* Custom Card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            background-color: #FFFFFF;
        }
        .card-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--admin-border);
            padding: 1.5rem;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title-custom {
            font-weight: 600;
            color: var(--admin-primary);
            margin-bottom: 0;
        }
        
        /* Custom Table */
        .table {
            vertical-align: middle;
        }
        .table th {
            font-weight: 600;
            color: var(--admin-text-light);
            font-size: 0.8rem;
            text-transform: uppercase;
            border-bottom: 2px solid var(--admin-border);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .table td {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        
        /* Custom Badge */
        .badge.bg-pending {
            background-color: #FFF8E1 !important;
            color: #FFB300 !important;
        }
        .badge.bg-active {
            background-color: #E8F5E9 !important;
            color: #4CAF50 !important;
        }

        /* Custom Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
        }
        .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        .btn-primary:hover {
            background-color: #1f2461;
            border-color: #1f2461;
        }
        .btn-accent {
            background-color: var(--admin-accent);
            border-color: var(--admin-accent);
            color: white;
        }
        .btn-accent:hover {
            background-color: #c01669;
            border-color: #c01669;
            color: white;
        }
        .btn-outline-primary {
            border-color: var(--admin-primary);
            color: var(--admin-primary);
        }
        .btn-outline-primary:hover {
            background-color: var(--admin-primary);
            color: white;
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
        }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }
        
        /* Custom Forms */
        .form-label {
            font-weight: 500;
            color: var(--admin-text);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid var(--admin-border);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(43, 50, 130, 0.1);
        }
        .nav-tabs .nav-link {
            color: var(--admin-text-light);
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: var(--admin-primary);
            border-color: var(--admin-border) var(--admin-border) #fff;
            border-bottom-width: 2px;
        }

    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                Admin Panel
            </div>
            
            <nav class="admin-sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-fill"></i>
                            Laporan Harian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}" href="{{ route('admin.members.index') }}">
                            <i class="bi bi-people-fill"></i>
                            Kelola Member
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <i class="bi bi-credit-card-fill"></i>
                            Validasi Pembayaran
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" href="{{ route('admin.schedules.index') }}">
                            <i class="bi bi-calendar-week-fill"></i>
                            Kelola Jadwal
                        </a>
                    </li>
                    <li class="nav-item">
                        @php
                            $isDataMasterActive = request()->routeIs('admin.trainers.*') || 
                                                  request()->routeIs('admin.homepage.*') || 
                                                  request()->routeIs('admin.notifications.*');
                        @endphp
                        <a class="nav-link {{ $isDataMasterActive ? 'active' : '' }}" 
                           href="{{ route('admin.trainers.index') }}">
                            <i class="bi bi-layers-fill"></i>
                            Data Master
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="admin-sidebar-footer">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-decoration-none text-start w-100">
                                <i class="bi bi-box-arrow-left"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-content">
            
            <!-- Flash Message (Success) -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Flash Message (Error) -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Tampilkan Error Validasi -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Terdapat kesalahan pada input Anda. Silakan periksa kembali form.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>