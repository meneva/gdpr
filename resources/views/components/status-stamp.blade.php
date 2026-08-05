@props(['tone' => 'grey'])

@php
    $toneClasses = [
        'green' => 'bg-green-50 text-green-700 border-green-300',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-300',
        'red'   => 'bg-red-50 text-red-700 border-red-300',
        'teal'  => 'bg-teal-50 text-teal-700 border-teal-300',
        'grey'  => 'bg-gray-100 text-gray-600 border-gray-300',
    ][$tone] ?? 'bg-gray-100 text-gray-600 border-gray-300';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded border text-xs font-mono font-semibold uppercase tracking-wide whitespace-nowrap $toneClasses"]) }}>
    {{ $slot }}
</span>
