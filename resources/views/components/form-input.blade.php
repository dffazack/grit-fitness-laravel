@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false
])

<div class="mb-3">
    {{-- Label --}}
    <label for="{{ $name }}" class="form-label grit-label">{{ $label }}</label>

    {{-- Input --}}
    <input 
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-control grit-input @error($name) is-invalid @enderror"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes }} 
    >

    {{-- Error Message --}}
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
{{-- Modified by: User-Interfaced Team -- }}