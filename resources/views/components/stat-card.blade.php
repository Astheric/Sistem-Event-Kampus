@props(['label', 'value', 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-surface p-6']) }}>
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-sm font-medium text-muted">{{ $label }}</div>
            <div class="mt-2 text-3xl font-extrabold tracking-tight">{{ $value }}</div>
        </div>
        @if ($icon)
            <x-icon name="{{ $icon }}" class="size-6 text-accent" />
        @endif
    </div>
</div>
