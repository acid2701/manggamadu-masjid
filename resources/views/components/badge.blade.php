@props([
    'variant' => 'green-soft', // green, green-soft, purple, orange, popular, danger
    'label' => null,
])

@php
    $baseClasses = 'inline-flex items-center font-sans font-bold text-caption-bold';
    
    $variants = [
        'green' => 'bg-brand-green text-brand-teal-deep rounded-sm px-xs py-[2px]',
        'green-soft' => 'bg-brand-green-soft text-brand-green-dark rounded-full px-sm py-[4px]',
        'purple' => 'bg-accent-purple text-white rounded-sm px-xs py-[2px]',
        'orange' => 'bg-accent-orange text-white rounded-sm px-xs py-[2px]',
        'popular' => 'bg-brand-teal-deep text-brand-green border border-brand-green rounded-full px-sm py-[4px]',
        'danger' => 'bg-red-100 text-red-700 rounded-full px-sm py-[4px]',
    ];

    $classes = "{$baseClasses} {$variants[$variant]}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label ?? $slot }}
</span>
