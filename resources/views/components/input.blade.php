@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-ink-200 focus:border-ink-500 focus:ring-ink-500 rounded-md shadow-sm text-sm']) }}>
