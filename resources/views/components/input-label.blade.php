@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm']) }} style="color: var(--md-text-soft)">
    {{ $value ?? $slot }}
</label>
