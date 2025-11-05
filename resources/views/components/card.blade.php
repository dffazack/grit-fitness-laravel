@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'card border-0 shadow-sm']) }}>
    @if($title || $subtitle)
        <div class="card-header bg-white border-bottom">
            @if($title)
                <h5 class="card-title mb-0">{{ $title }}</h5>
            @endif
            @if($subtitle)
                <p class="text-muted small mb-0">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
