@props(['audience'])

@php
$label = match ($audience) {
    'STUDENT' => 'Mahasiswa',
    'LECTURER' => 'Dosen',
    default => 'Umum',
};
$icon = match ($audience) {
    'STUDENT' => 'book-open',
    'LECTURER' => 'briefcase',
    default => 'globe',
};
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-surface-soft text-muted']) }}>
    <x-icon name="{{ $icon }}" class="size-3" />
    {{ $label }}
</span>
