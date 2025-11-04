@props([
    'type' => 'button', // Default type is 'button'
    'variant' => 'primary' // Default variant
])

@php
    // Tentukan class warna berdasarkan variant
    $colorClass = '';
    switch ($variant) {
        case 'accent':
            $colorClass = 'btn-accent'; // Asumsi Anda punya class .btn-accent
            break;
        case 'danger':
            $colorClass = 'btn-danger';
            break;
        case 'secondary':
            $colorClass = 'btn-secondary';
            break;
        default:
            $colorClass = 'btn-primary';
    }
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn ' . $colorClass . ' btn-lg']) }}
>
    {{ $slot }}
</button>