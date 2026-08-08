@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-mono text-xs uppercase tracking-wide text-ink-500']) }}>
    {{ $value ?? $slot }}
</label>
