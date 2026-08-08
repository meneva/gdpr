@props(['active' => false])

@php
$classes = $active
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-ink-700 text-start text-base font-medium text-ink-900 bg-parchment focus:outline-none focus:text-ink-900 focus:bg-parchment focus:border-ink-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-ink-900 hover:bg-parchment hover:border-ink-200 focus:outline-none focus:text-ink-900 focus:bg-parchment focus:border-ink-200 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
