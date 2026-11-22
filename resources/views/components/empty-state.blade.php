@props(['title' => 'Belum ada data', 'description' => null])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    <div class="text-muted text-lg font-semibold">{{ $title }}</div>
    @if ($description)
        <div class="text-sm text-subtle mt-2">{{ $description }}</div>
    @endif
    @if (isset($slot) && trim($slot) !== '')
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
