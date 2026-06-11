@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-yellow-400 text-start text-base font-semibold text-yellow-100 bg-yellow-400/15 focus:outline-none focus:bg-yellow-400/20 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-yellow-100/75 hover:text-yellow-100 hover:bg-yellow-400/10 hover:border-yellow-400/40 focus:outline-none focus:bg-yellow-400/10 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
