@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'green', // green, orange, purple, teal
    'trend' => null, // e.g., '+12%', '-5%', etc.
    'trendUp' => true, // default positive trend color
])

@php
    $colorClasses = [
        'green' => 'bg-brand-green-soft text-brand-green-dark border-brand-green/20',
        'orange' => 'bg-orange-100 text-orange-700 border-orange-200',
        'purple' => 'bg-purple-100 text-purple-700 border-purple-200',
        'teal' => 'bg-brand-green/10 text-brand-teal-mid border-brand-teal-mid/10',
    ][$color] ?? 'bg-brand-green-soft text-brand-green-dark border-brand-green/20';
@endphp

<div {{ $attributes->merge(['class' => 'bg-canvas p-xl rounded-lg border border-hairline shadow-level-1 flex items-center justify-between']) }}>
    <div class="flex flex-col gap-xxs">
        <span class="text-micro-uppercase font-bold text-steel tracking-wider">{{ $title }}</span>
        <span class="text-heading-3 font-bold text-ink leading-tight">{{ $value }}</span>
        
        @if($trend)
            <div class="flex items-center gap-xxs mt-xxs">
                <span class="text-micro font-bold {{ $trendUp ? 'text-brand-green-dark bg-brand-green-soft' : 'text-red-700 bg-red-100' }} px-[6px] py-[2px] rounded-full">
                    {{ $trend }}
                </span>
                <span class="text-[11px] text-stone">bulan ini</span>
            </div>
        @endif
    </div>

    @if($icon)
        <div class="h-12 w-12 rounded-full border flex items-center justify-center text-heading-4 shadow-level-1 {{ $colorClasses }}">
            {!! $icon !!}
        </div>
    @endif
</div>
