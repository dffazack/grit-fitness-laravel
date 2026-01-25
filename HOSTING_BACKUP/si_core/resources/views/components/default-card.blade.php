{{-- File: resources/views/components/default-card.blade.php --}}

@props(['title' => false])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm mb-4']) }}>
    
    {{-- Jika ada atribut 'title', tampilkan header kartu --}}
    @if ($title)
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-semibold">{{ $title }}</h5>
        </div>
    @endif

    {{-- Konten utama kartu --}}
    <div class="card-body p-4">
        {{ $slot }}
    </div>

</div>
{{-- Modified by: User-Interfaced Team -- }}