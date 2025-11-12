{{-- File: resources/views/components/status-label.blade.php --}}

@props(['status'])

@php
    $colorClass = '';
    $text = strtolower($status ?? 'unknown');

    switch ($text) {
        case 'active':
        case 'approved':
            $colorClass = 'bg-success text-white';
            break;
        case 'pending':
            $colorClass = 'bg-warning text-dark';
            break;
        case 'expired':
        case 'rejected':
        case 'inactive':
            $colorClass = 'bg-danger text-white';
            break;
        default:
            $colorClass = 'bg-secondary text-white';
            $text = 'unknown';
    }
@endphp

<span class="badge rounded-pill {{ $colorClass }}" style="font-size: 0.8rem; padding: 0.4em 0.8em;">
    {{ ucfirst($text) }}
</span>
{{-- Modified by: User-Interfaced Team -- }}