@props([
    'variant' => 'info', // success, warning, error, info
    'message' => null,
    'dismissible' => false,
])

@php
    $variants = [
        'success' => [
            'bg' => 'bg-brand-green-soft text-brand-green-dark border-brand-green/20',
            'icon' => '✅',
        ],
        'warning' => [
            'bg' => 'bg-semantic-warning-bg text-semantic-warning-text border-yellow-200',
            'icon' => '⚠️',
        ],
        'error' => [
            'bg' => 'bg-red-50 text-red-800 border-red-200',
            'icon' => '🚨',
        ],
        'info' => [
            'bg' => 'bg-blue-50 text-blue-800 border-blue-200',
            'icon' => 'ℹ️',
        ],
    ][$variant] ?? [
        'bg' => 'bg-blue-50 text-blue-800 border-blue-200',
        'icon' => 'ℹ️',
    ];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    {{ $attributes->merge(['class' => "flex items-start gap-sm p-md rounded-lg border {$variants['bg']}"]) }}
>
    <span class="shrink-0 text-body-md">{{ $variants['icon'] }}</span>
    <div class="flex-1 text-body-sm font-medium leading-relaxed">
        {{ $message ?? $slot }}
    </div>
    
    @if($dismissible)
        <button type="button" class="shrink-0 text-slate hover:text-ink focus:outline-none cursor-pointer" @click="show = false">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    @endif
</div>
