@props(['active' => false])

@php
$classes = $active
            ? 'flex items-center w-full gap-3 px-3 py-2 rounded-md text-sm font-medium text-white bg-white/10 transition duration-150 ease-in-out'
            : 'flex items-center w-full gap-3 px-3 py-2 rounded-md text-sm font-medium text-ink-200 hover:text-white hover:bg-white/5 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
