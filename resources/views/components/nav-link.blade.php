@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 rounded-full border border-yellow-400/30 bg-yellow-400/15 text-sm font-semibold leading-5 text-yellow-100 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-3 py-2 rounded-full border border-transparent text-sm font-semibold leading-5 text-yellow-100/75 hover:text-yellow-100 hover:bg-yellow-400/10 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
