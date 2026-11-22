@props(['name', 'label' => null, 'class' => ''])

@php
    $name = preg_replace('/[^a-z0-9-]/', '', strtolower((string) $name));
    $path = resource_path('svg/feather/' . $name . '.svg');

    if ($name === '' || ! is_file($path)) {
        $path = resource_path('svg/feather/tag.svg');
    }

    $svg = is_file($path) ? file_get_contents($path) : '';
    $svg = preg_replace('/<svg\b/', '<svg class="' . e($class) . '"', $svg, 1);
    if ($label) {
        $svg = str_replace('<svg ', '<svg role="img" aria-label="' . e($label) . '" ', $svg);
    } else {
        $svg = str_replace('<svg ', '<svg aria-hidden="true" ', $svg);
    }
@endphp

{!! $svg !!}
