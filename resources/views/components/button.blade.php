@props(['variant' => 'primary'])

@php
$classes = match ($variant) {
    'primary' => 'bg-accent text-accent-ink hover:brightness-95',
    'secondary' => 'bg-surface text-ink border border-line hover:bg-bg',
    'ghost' => 'bg-transparent text-ink underline hover:no-underline',
    'danger' => 'bg-ink text-white hover:bg-accent hover:text-accent-ink',
    default => 'bg-accent text-accent-ink hover:brightness-95',
};
@endphp

<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold transition-colors ' . $classes]) }}>
    {{ $slot }}
</button>
