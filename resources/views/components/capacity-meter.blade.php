@props(['capacity'])

@php
if ($capacity instanceof \App\Models\Event) {
    $capacity = $capacity->capacity();
}
$percent = $capacity->percent;
$state = $capacity->state;
$color = match ($state->value) {
    'available' => 'bg-success',
    'almost_full' => 'bg-warning',
    'full' => 'bg-danger',
    'closed' => 'bg-subtle',
    default => 'bg-brand-600',
};
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    <div class="h-2 rounded-full bg-surface-soft overflow-hidden">
        <div class="{{ $color }} h-full rounded-full" style="width: {{ $percent }}%"></div>
    </div>
    <div class="mt-1 flex justify-between text-xs text-muted">
        <span>Terisi {{ $capacity->filled }}</span>
        <span>Tersisa {{ $capacity->remaining }}</span>
        <span>Total {{ $capacity->total }}</span>
    </div>
</div>
