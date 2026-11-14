@props(['label', 'value', 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-surface rounded-2xl shadow-card p-6']) }}>
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm text-muted">{{ $label }}</div>
            <div class="text-3xl font-bold mt-1">{{ $value }}</div>
        </div>
        @if ($icon)
            <x-icon name="{{ $icon }}" class="size-6 text-brand-600" />
        @endif
    </div>
</div>
