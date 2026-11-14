@props(['variant' => 'primary'])

@php
$classes = match ($variant) {
    'primary' => 'bg-brand-600 text-white hover:bg-brand-700',
    'secondary' => 'bg-surface text-ink border border-line hover:bg-surface-soft',
    'danger' => 'bg-danger text-white hover:opacity-90',
    default => 'bg-brand-600 text-white hover:bg-brand-700',
};
@endphp

<button {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold transition ' . $classes]) }}>
    {{ $slot }}
</button>
