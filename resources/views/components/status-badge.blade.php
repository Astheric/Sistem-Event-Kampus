@props(['status'])

@php
$label = $status;
$classes = 'bg-surface-soft text-muted';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' . $classes]) }}>
    {{ $label }}
</span>
