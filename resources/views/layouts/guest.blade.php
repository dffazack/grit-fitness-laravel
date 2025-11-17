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
    
    <!-- Alpine.js (Jika diperlukan untuk komponen lain) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- GRIT Custom CSS (Inline) -->
    <style>
        :root {
            --grit-primary: #2B3282;
            --grit-primary-dark: #1f2461;
            --grit-accent: #E51B83;
            --grit-accent-dark: #c01669;
            --grit-background: #F5F5F7; /* <-- Warna background abu-abu muda */
            --grit-text: #333333;
            --grit-text-light: #717182;
            --grit-border: #E0E0E0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            color: var(--grit-text);
            background-color: var(--grit-background); /* <-- Terapkan background */
        }
        .text-primary { color: var(--grit-primary) !important; }
        .btn-primary {
            background-color: var(--grit-primary);
            border-color: var(--grit-primary);
        }
        .btn-primary:hover {
            background-color: var(--grit-primary-dark);
            border-color: var(--grit-primary-dark);
        }
    </style>
</head>
<body>
    
    {{-- Konten akan di-yield di sini --}}
    <main>
        @yield('content')
    </main>
    
    <!-- Bootstrap 5.3 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>