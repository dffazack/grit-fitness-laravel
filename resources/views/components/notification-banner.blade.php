{{-- File: resources/views/components/notification-banner.blade.php --}}
@php
    // NOTE: lebih baik memindahkan query ke View Composer / Controller.
    // Untuk compatibility jika belum dipindahkan, gunakan try/catch seperti sebelumnya.
    try {
        $activeNotifications = $activeNotifications ?? \App\Models\Notification::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
    } catch (\Exception $e) {
        $activeNotifications = collect();
    }

    // Ambil array id yang sudah ditutup user (dari localStorage via JS) - server-side hanya placeholder.
@endphp

@if ($activeNotifications->isNotEmpty())
    <div id="notification-rotator" class="notification-rotator fixed-top" 
         data-min-interval="5000"  {{-- ms: default 5000 = 5s --}}
         data-max-interval="10000" {{-- ms: default 10000 = 10s --}}
         role="region" aria-label="Notification banners">
        <div class="container d-flex align-items-center justify-content-center">
            <div class="notification-inner w-100 d-flex align-items-center justify-content-center">
                @foreach ($activeNotifications as $i => $notification)
                    <div class="notif-slide" data-notif-id="{{ $notification->id }}" aria-hidden="true">
                        <div class="d-flex align-items-center gap-3 w-100">
                            <i class="bi bi-bell-fill fs-4"></i>
                            <div class="notif-content text-start">
                                <div class="fw-bold notif-title">{{ $notification->title }}</div>
                                <div class="notif-message d-none d-md-block small">{{ $notification->content ?? $notification->message }}</div>
                            </div>
                            <div class="ms-auto d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-light btn-dismiss-notif" aria-label="Tutup notifikasi">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

{{-- Flash messages (tetap seperti sebelumnya) --}}
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

<style>
    /* Styling notifikasi rotator */
    .notification-rotator {
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1060;
        pointer-events: auto;
        background: linear-gradient(90deg, rgba(229,27,131,0.95), rgba(43,50,130,0.95));
        color: #fff;
    }
    .notification-rotator .container { padding: 8px 16px; }
    .notif-slide { 
        display: none;
        opacity: 0;
        transform: translateY(-4px);
        transition: opacity 400ms ease, transform 400ms ease;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }
    .notif-slide.active {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }
    .notif-title { font-size: 1rem; color: #fff; }
    .notif-message { color: rgba(255,255,255,0.95); }
    .btn-dismiss-notif {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
    }
    .btn-dismiss-notif:hover { color: #fff; background: rgba(255,255,255,0.06); }
    /* jika ada navbar fixed, pastikan offset agar tidak menimpa konten */
    body { --notif-rotator-height: 56px; } /* opsional; adapt jika navbar beda */
</style>

<script>
(function() {
    // Delay execution sampai DOM ready
    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function() {
        const rotator = document.getElementById('notification-rotator');
        if (!rotator) return;

        // ambil interval minimal & maksimal (ms)
        const minInterval = parseInt(rotator.dataset.minInterval || 5000, 10);
        const maxInterval = parseInt(rotator.dataset.maxInterval || 10000, 10);
        const slides = Array.from(rotator.querySelectorAll('.notif-slide'));

        // Baca dismissed ids dari localStorage
        const dismissedKey = 'dismissedNotifications_v1';
        const dismissed = (() => {
            try {
                const raw = localStorage.getItem(dismissedKey);
                return raw ? JSON.parse(raw) : [];
            } catch(e) { return []; }
        })();

        // Filter slides yang sudah di-dismiss
        let visibleSlides = slides.filter(s => !dismissed.includes(s.dataset.notifId));

        if (visibleSlides.length === 0) {
            // Jika tidak ada slide tersisa, hapus rotator
            rotator.remove();
            return;
        }

        let currentIndex = 0;
        let timer = null;
        let paused = false;

        function pickInterval() {
            if (minInterval >= maxInterval) return minInterval;
            return Math.floor(Math.random() * (maxInterval - minInterval + 1)) + minInterval;
        }

        function showSlide(index) {
            // Guard
            if (!visibleSlides.length) return;
            visibleSlides.forEach((s, i) => {
                s.classList.toggle('active', i === index);
                s.setAttribute('aria-hidden', i === index ? 'false' : 'true');
            });
            currentIndex = index;
        }

        function nextSlide() {
            if (!visibleSlides.length) return;
            const next = (currentIndex + 1) % visibleSlides.length;
            showSlide(next);
            scheduleNext();
        }

        function scheduleNext() {
            clearTimeout(timer);
            if (paused) return;
            const interval = pickInterval();
            timer = setTimeout(nextSlide, interval);
        }

        // Pause on hover
        rotator.addEventListener('mouseenter', () => {
            paused = true;
            clearTimeout(timer);
        });
        rotator.addEventListener('mouseleave', () => {
            paused = false;
            scheduleNext();
        });

        // Dismiss button handler
        rotator.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-dismiss-notif');
            if (!btn) return;
            const slide = btn.closest('.notif-slide');
            if (!slide) return;

            const id = slide.dataset.notifId;
            // simpan ke localStorage agar tidak muncul lagi
            try {
                const arr = Array.isArray(dismissed) ? dismissed : [];
                if (!arr.includes(id)) arr.push(id);
                localStorage.setItem(dismissedKey, JSON.stringify(arr));
            } catch (err) { /* ignore */ }

            // remove slide from visibleSlides and DOM
            const idx = visibleSlides.indexOf(slide);
            if (idx > -1) visibleSlides.splice(idx, 1);
            slide.remove();

            if (visibleSlides.length === 0) {
                rotator.remove();
                return;
            }

            // jika yang dihapus index <= currentIndex maka geser index mundur 1
            if (idx <= currentIndex) currentIndex = Math.max(0, currentIndex - 1);
            showSlide(currentIndex);
            scheduleNext();
        });

        // Inisialisasi: tampilkan slide pertama & mulai rotasi
        showSlide(0);
        scheduleNext();
    });
})();
</script>
{{-- Modified by: User-Interfaced Team --}}