@props(['title', 'value', 'icon' => 'bi-graph-up', 'variant' => 'primary'])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body text-center">
        <div class="mb-3">
            <i class="bi {{ $icon }} fs-1 text-{{ $variant }}"></i>
        </div>
        <h3 class="mb-1 fw-bold">{{ $value }}</h3>
        <p class="text-muted mb-0">{{ $title }}</p>
    </div>
</div>
{{-- Modified by: User-Interfaced Team -- }}