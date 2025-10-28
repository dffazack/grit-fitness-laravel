<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard - GRIT Fitness')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css'])
    
    @stack('styles')
</head>
<body class="bg-light-custom">
    <div class="d-flex">
        <!-- Sidebar -->
        @include('components.admin-sidebar')
        
        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Top Header -->
            <header class="bg-white border-bottom py-3 sticky-top">
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-primary">@yield('page-title')</h4>
                            <p class="text-muted small mb-0">@yield('page-subtitle')</p>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-bell"></i>
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>