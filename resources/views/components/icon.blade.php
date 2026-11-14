@props(['name', 'label' => null, 'class' => ''])

@php
    $path = resource_path('svg/feather/' . $name . '.svg');
    $svg = is_file($path) ? file_get_contents($path) : '';
    $svg = preg_replace('/<svg\b/', '<svg class="' . e($class) . '"', $svg, 1);
    $svg = str_replace('stroke="currentColor"', 'stroke="currentColor"', $svg);
    if ($label) {
        $svg = str_replace('<svg ', '<svg role="img" aria-label="' . e($label) . '" ', $svg, 1);
    } else {
        $svg = str_replace('<svg ', '<svg aria-hidden="true" ', $svg, 1);
    }
@endphp

{!! $svg !!}
