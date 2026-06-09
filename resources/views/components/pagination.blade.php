@props([
    'paginator',
])

@if($paginator && $paginator->hasPages())
    <div {{ $attributes->merge(['class' => 'py-md px-lg flex items-center justify-between border-t border-hairline bg-canvas rounded-b-lg']) }}>
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-md border border-hairline bg-surface px-md py-xs text-body-sm font-semibold text-muted cursor-default">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-hairline bg-canvas px-md py-xs text-body-sm font-semibold text-ink hover:bg-surface-soft" wire:navigate>
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-md border border-hairline bg-canvas px-md py-xs text-body-sm font-semibold text-ink hover:bg-surface-soft" wire:navigate>
                    Berikutnya
                </a>
            @else
                <span class="relative inline-flex items-center rounded-md border border-hairline bg-surface px-md py-xs text-body-sm font-semibold text-muted cursor-default">
                    Berikutnya
                </span>
            @endif
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-body-sm text-steel">
                    Menampilkan
                    <span class="font-bold text-ink">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-ink">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-ink">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-xs" aria-label="Pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center rounded-l-md px-sm py-xs text-slate bg-surface-soft border border-hairline cursor-default">
                            <span class="sr-only">Sebelumnya</span>
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-sm py-xs text-slate bg-canvas border border-hairline hover:bg-surface-soft" wire:navigate>
                            <span class="sr-only">Sebelumnya</span>
                            ‹
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($paginator->links()->elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="relative inline-flex items-center px-sm py-xs text-slate border border-hairline bg-canvas">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="relative z-10 inline-flex items-center bg-brand-teal-deep px-sm py-xs text-body-sm font-bold text-brand-green border border-brand-teal-deep">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-sm py-xs text-body-sm font-semibold text-slate bg-canvas border border-hairline hover:bg-surface-soft" wire:navigate>
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-sm py-xs text-slate bg-canvas border border-hairline hover:bg-surface-soft" wire:navigate>
                            <span class="sr-only">Berikutnya</span>
                            ›
                        </a>
                    @else
                        <span class="relative inline-flex items-center rounded-r-md px-sm py-xs text-slate bg-surface-soft border border-hairline cursor-default">
                            <span class="sr-only">Berikutnya</span>
                            ›
                        </span>
                    @endif
                </nav>
            </div>
        </div>
    </div>
@endif
