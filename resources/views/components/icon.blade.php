@props(['name', 'label' => null, 'class' => ''])

@php
    $path = resource_path('svg/feather/' . $name . '.svg');
    $svg = is_file($path) ? file_get_contents($path) : '';
    $svg = preg_replace('/<svg\b/', '<svg class="' . e($class) . '"', $svg, 1);
    if ($label) {
        $svg = str_replace('<svg ', '<svg role="img" aria-label="' . e($label) . '" ', $svg);
    } else {
        $svg = str_replace('<svg ', '<svg aria-hidden="true" ', $svg);
    }
@endphp

{!! $svg !!}
