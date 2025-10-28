<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GRIT Fitness')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- GRIT Custom CSS (Inline) -->
    <style>
        /* Import GRIT Design System Variables */
        :root {
            --grit-primary: #2B3282;
            --grit-primary-dark: #1f2461;
            --grit-accent: #E51B83;
            --grit-accent-dark: #c01669;
            --grit-background: #F5F5F7;
            --grit-text: #333333;
            --grit-text-light: #717182;
            --grit-border: #E0E0E0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--grit-text);
        }

        /* Custom Bootstrap Override */
        .btn-primary {
            background-color: var(--grit-primary);
            border-color: var(--grit-primary);
        }
        .btn-primary:hover {
            background-color: var(--grit-primary-dark);
            border-color: var(--grit-primary-dark);
        }

        .btn-accent {
            background-color: var(--grit-accent);
            border-color: var(--grit-accent);
            color: white;
        }
        .btn-accent:hover {
            background-color: var(--grit-accent-dark);
            border-color: var(--grit-accent-dark);
            color: white;
        }

        .text-primary { color: var(--grit-primary) !important; }
        .text-accent { color: var(--grit-accent) !important; }
        .bg-primary { background-color: var(--grit-primary) !important; }
        .bg-accent { background-color: var(--grit-accent) !important; }
        .bg-light-custom { background-color: var(--grit-background) !important; }

        .navbar-brand {
            font-weight: 700;
            font-size: 24px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .hero-section {
            background-size: cover;
            background-position: center;
            min-height: 600px;
            display: flex;
            align-items: center;
        }

        /* Membership Card Styling */
        .membership-card {
            border: 2px solid var(--grit-border);
            border-radius: 16px;
            padding: 32px;
            transition: all 0.3s ease;
        }
        .membership-card:hover {
            border-color: var(--grit-primary);
            transform: translateY(-8px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.16);
        }

        .membership-card.featured {
            border-color: var(--grit-accent);
            position: relative;
        }
        .membership-card.featured::before {
            content: 'TERPOPULER';
            position: absolute;
            top: -12px;
            right: 24px;
            background-color: var(--grit-accent);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Additional GRIT Styles */
        /* Anda bisa copy dari LARAVEL_CUSTOM_CSS.css */
    </style>
    
    @stack('styles')
</head>
<body>
    @include('components.navbar')
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-0 rounded-0">
            <div class="container">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Bootstrap 5.3 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>