@props([
    'variant' => 'primary', // primary, secondary, on-dark, secondary-on-dark, ghost, link, danger
    'size' => 'md', // sm, md, lg
    'href' => null,
    'icon' => null,
    'disabled' => false,
    'type' => 'button',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-sans font-semibold transition-all duration-150 ease-in-out focus:outline-hidden disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';
    
    $variants = [
        'primary' => 'bg-brand-green text-brand-teal-deep hover:bg-brand-green-mid active:bg-brand-green-dark rounded-full',
        'secondary' => 'bg-transparent text-ink border border-hairline-strong hover:bg-surface-soft active:bg-hairline-soft rounded-full',
        'on-dark' => 'bg-brand-green text-brand-teal-deep hover:bg-brand-green-mid active:bg-brand-green-dark rounded-full',
        'secondary-on-dark' => 'bg-transparent text-on-dark border border-hairline-dark hover:bg-white/10 rounded-full',
        'ghost' => 'bg-transparent text-ink hover:bg-surface-soft active:bg-hairline rounded-md',
        'link' => 'bg-transparent text-brand-green-dark hover:underline p-0',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 rounded-full',
    ];

    $sizes = [
        'sm' => 'px-md py-xxs text-micro',
        'md' => 'px-lg py-sm text-body-sm', // 10px 22px maps to px-lg (20px) py-sm (12px)
        'lg' => 'px-xl py-md text-body-md',
    ];

    if ($variant === 'link') {
        $sizes = ['sm' => '', 'md' => '', 'lg' => ''];
    }

    $classes = "{$baseClasses} {$variants[$variant]} {$sizes[$size]}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-xs font-semibold">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-xs font-semibold">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
