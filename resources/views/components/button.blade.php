@props(['variant' => 'primary'])

@php
$classes = match ($variant) {
    'primary' => 'bg-brand-600 text-white hover:bg-brand-700 shadow-sm',
    'secondary' => 'bg-surface text-ink border border-line hover:bg-surface-soft',
    'ghost' => 'bg-transparent text-ink hover:bg-surface-soft',
    'danger' => 'bg-danger text-white hover:opacity-90',
    default => 'bg-brand-600 text-white hover:bg-brand-700 shadow-sm',
};
@endphp

<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold transition-colors ' . $classes]) }}>
    {{ $slot }}
</button>
