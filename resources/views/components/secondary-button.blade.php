@props(['type' => 'button'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-ink-200 rounded-md font-mono text-xs text-ink-700 uppercase tracking-widest shadow-sm hover:bg-parchment focus:outline-none focus:ring-2 focus:ring-ink-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
