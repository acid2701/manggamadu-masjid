@props([
    'variant' => 'base', // base, feature, dark
    'padding' => null,
])

@php
    $baseClasses = 'rounded-lg border';
    
    $variants = [
        'base' => 'bg-canvas border-hairline shadow-level-1',
        'feature' => 'bg-canvas border-hairline shadow-level-2',
        'dark' => 'bg-brand-teal-deep text-on-dark border-hairline-dark shadow-level-2',
    ];

    $paddings = [
        'base' => 'p-xl',
        'feature' => 'p-xxl',
        'dark' => 'p-xxl',
    ];

    $pad = $padding ?? $paddings[$variant] ?? 'p-xl';
    $classes = "{$baseClasses} {$variants[$variant]} {$pad}";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
