@props(['variant' => 'primary', 'type' => 'button'])

@php
    $classes = 'btn';
    $classes .= match($variant) {
        'accent' => ' btn-accent text-white',
        'primary' => ' btn-primary',
        'secondary' => ' btn-outline-primary',
        'danger' => ' btn-danger',
        default => ' btn-primary',
    };
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>
{{-- Modified by: User-Interfaced Team -- }}