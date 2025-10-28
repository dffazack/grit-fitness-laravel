{{-- File: resources/views/components/notification-banner.blade.php --}}

{{-- Menampilkan notifikasi database yang aktif --}}
@php
    // Ambil notifikasi aktif dari database (jika ada, jika tidak variabel $notifications akan kosong)
    // Sebaiknya logika ini ada di Controller/View Composer, tapi ini untuk fix cepat
    try {
        $activeNotifications = \App\Models\Notification::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    } catch (\Exception $e) {
        $activeNotifications = collect(); // Kosongkan jika ada error database
    }
@endphp

@if ($activeNotifications->isNotEmpty())
    <div class="alert alert-warning text-center rounded-0 mb-0 py-2 notification-fixed-top" role="alert" style="background-color: #E51B83; color: white;">
        <div class="container d-flex justify-content-center align-items-center">
            <i class="bi bi-bell-fill me-2"></i>
            @foreach ($activeNotifications as $notification)
                <span class="fw-bold me-4">{{ $notification->title }}:</span>
                <span class="d-none d-md-inline">{{ $notification->content }}</span>
            @endforeach
            {{-- Tombol close bisa ditambahkan jika diinginkan --}}
            {{-- <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert" aria-label="Close"></button> --}}
        </div>
    </div>
@endif

{{-- Menampilkan notifikasi sesi (flash messages) --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-0 rounded-0 fixed-top mt-5 pt-3 pb-3" role="alert" style="z-index: 1050;">
        <div class="container">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-0 rounded-0 fixed-top mt-5 pt-3 pb-3" role="alert" style="z-index: 1050;">
        <div class="container">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show m-0 rounded-0 fixed-top mt-5 pt-3 pb-3" role="alert" style="z-index: 1050;">
        <div class="container">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

{{-- Optional: Add custom CSS for fixed-top banners if needed --}}
<style>
    .notification-fixed-top {
        position: sticky; /* or fixed */
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1040; /* Below navbar potentially */
    }
    /* Adjust margin-top for fixed session alerts if navbar height varies */
    .fixed-top.mt-5 {
        margin-top: 56px !important; /* Adjust based on your actual navbar height */
    }
</style>