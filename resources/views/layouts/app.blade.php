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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- GRIT Custom CSS -->
    <link href="{{ asset('css/grit-style.css') }}" rel="stylesheet">
    
    <!-- AOS (Animate on Scroll) CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet"

    @stack('styles')
</head>
<body class="bg-light-custom"> {{-- Memberi background abu-abu muda untuk layout --}}
    
    @include('components.navbar')
    
    {{-- Container untuk Flash Messages (dari notification-banner) --}}
    {{-- Ini akan mengambil pesan sukses/error dari banner --}}
    @include('components.notification-banner')
    
    <main>
        {{-- Tambahan: Flash message dari session (untuk redirect middleware) --}}
        @if (session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <a href="{{ route('member.payment') }}" class="btn btn-primary btn-sm ms-2">Perpanjang Membership Sekarang</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @elseif (session('info'))
            <div class="container mt-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <a href="{{ route('member.payment') }}" class="btn btn-primary btn-sm ms-2">Cek Status Pembayaran</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        {{-- JS optional untuk auto-dismiss setelah 5 detik --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    document.querySelectorAll('.alert-dismissible').forEach(function(alert) {
                        var bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    });
                }, 5000);
            });
        </script>

        @yield('content')
    </main>
    
    @include('components.footer')
    
    <!-- Bootstrap 5.3 JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate on Scroll) JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Durasi animasi dalam milidetik
            once: true, // Apakah animasi hanya terjadi sekali
            offset: 50, // Trigger animasi sedikit lebih awal
        });
    </script>

    @stack('scripts')
</body>
</html>