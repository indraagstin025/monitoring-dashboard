@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'monitor-input shadow-sm']) }}>
