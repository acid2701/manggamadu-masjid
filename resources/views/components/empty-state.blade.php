@props([
    'icon' => '📂',
    'title' => 'Belum ada data',
    'description' => 'Data tidak ditemukan atau belum ditambahkan.',
    'actionHref' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-xxl text-center bg-canvas border border-hairline border-dashed rounded-lg']) }}>
    <div class="h-16 w-16 bg-surface-soft border border-hairline rounded-full flex items-center justify-center text-heading-2 mb-md shadow-level-1">
        {{ $icon }}
    </div>
    <h3 class="text-heading-5 font-bold text-ink mb-xs">{{ $title }}</h3>
    <p class="text-body-sm text-steel max-w-sm mb-lg leading-relaxed">{{ $description }}</p>
    
    @if($actionHref && $actionLabel)
        <a href="{{ $actionHref }}" class="btn-primary" wire:navigate>
            {{ $actionLabel }}
        </a>
    @else
        {{ $slot }}
    @endif
</div>
