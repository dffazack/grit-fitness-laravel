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
    
    <!-- [PERBAIKAN] Bootstrap Icons via CDN (Ini yang hilang) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Alpine.js (Untuk tab di Homepage Admin) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
            --grit-shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--grit-text);
            background-color: #FFFFFF; /* Pastikan background putih */
        }

        /* Custom Bootstrap Override */
        .btn-primary {
            background-color: var(--grit-primary);
            border-color: var(--grit-primary);
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: var(--grit-primary-dark);
            border-color: var(--grit-primary-dark);
        }

        .btn-accent {
            background-color: var(--grit-accent);
            border-color: var(--grit-accent);
            color: white;
            font-weight: 500;
        }
        .btn-accent:hover {
            background-color: var(--grit-accent-dark);
            border-color: var(--grit-accent-dark);
            color: white;
        }
        
        .btn-grit-primary {
            background-color: var(--grit-primary);
            border-color: var(--grit-primary);
            color: white;
        }
        .btn-grit-primary:hover {
            background-color: var(--grit-primary-dark);
            border-color: var(--grit-primary-dark);
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

        /* Input Form Kustom */
        .grit-label {
            font-weight: 500;
            color: var(--grit-text);
            font-size: 0.9rem;
        }
        .grit-input, .form-select.grit-input {
            border-radius: 8px;
            border: 1px solid var(--grit-border);
            padding: 0.75rem 1rem;
        }
        .grit-input:focus, .form-select.grit-input:focus {
            border-color: var(--grit-primary);
            box-shadow: 0 0 0 3px rgba(43, 50, 130, 0.1);
        }
        
    </style>
    
    @stack('styles')
</head>
<body class="bg-light-custom"> {{-- Memberi background abu-abu muda untuk layout --}}
    
    @include('components.navbar')
    
    {{-- Container untuk Flash Messages (dari notification-banner) --}}
    {{-- Ini akan mengambil pesan sukses/error dari banner --}}
    @include('components.notification-banner')
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Bootstrap 5.3 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

