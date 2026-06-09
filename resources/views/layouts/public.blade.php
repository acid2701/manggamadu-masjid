@php
    $settingService = app(\App\Services\SettingService::class);
    $masjidName = $settingService->get('masjid_name', 'Masjid Sholikin');
    $masjidAddress = $settingService->get('masjid_address', 'Boyolali, Jawa Tengah');
    $masjidPhone = $settingService->get('phone', '');
    $masjidEmail = $settingService->get('email', '');
    $facebookUrl = $settingService->get('facebook', '');
    $instagramUrl = $settingService->get('instagram', '');
    $youtubeUrl = $settingService->get('youtube', '');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="@yield('meta_description', $settingService->get('masjid_description', 'Website Resmi ' . $masjidName))" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="@yield('title') | {{ $masjidName }}" />
    <meta property="og:description" content="@yield('meta_description', $settingService->get('masjid_description', 'Website Resmi ' . $masjidName))" />
    <meta property="og:image" content="@yield('og_image', $settingService->get('masjid_photo', '/images/default-masjid.jpg'))" />

    <title>@yield('title') | {{ $masjidName }}</title>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    
    <!-- Alpine JS (already included via Livewire/Vite usually, but standard for standalone blade is fine) -->
</head>
<body class="min-h-screen bg-canvas text-ink antialiased font-sans flex flex-col">

    <!-- Top Promo Banner (MongoDB inspired promo-banner) -->
    <div class="bg-brand-teal-deep text-on-dark py-2 px-md text-center text-xs font-semibold tracking-wide border-b border-hairline-dark">
        Selamat Datang di Website Resmi {{ $masjidName }} Potronayan, Boyolali
    </div>

    <!-- Sticky Header (Navbar) -->
    <header class="sticky top-0 z-50 bg-canvas/95 backdrop-blur-md border-b border-hairline transition-all duration-300" 
            x-data="{ isOpen: false, isScrolled: false }" 
            x-init="window.addEventListener('scroll', () => { isScrolled = window.scrollY > 10 })"
            :class="isScrolled ? 'shadow-level-2' : ''">
        <div class="max-w-7xl mx-auto px-md lg:px-xl h-16 flex items-center justify-between">
            <!-- Logo Section -->
            <a href="{{ route('home') }}" class="flex items-center gap-sm" wire:navigate>
                <div class="h-9 w-9 bg-brand-green-dark rounded-full flex items-center justify-center text-brand-green font-bold shadow-level-1">
                    🕌
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-ink text-body-md tracking-tight leading-tight">{{ $masjidName }}</span>
                    <span class="text-steel text-[10px] uppercase font-bold tracking-widest leading-none">Boyolali</span>
                </div>
            </a>

            <!-- Desktop Nav Items -->
            <nav class="hidden lg:flex items-center gap-md">
                @php
                    $menuItems = [
                        ['route' => 'home', 'label' => 'Beranda'],
                        ['route' => 'profile', 'label' => 'Profil'],
                        ['route' => 'prayer-time', 'label' => 'Jadwal Sholat'],
                        ['route' => 'events.index', 'label' => 'Kegiatan'],
                        ['route' => 'posts.index', 'label' => 'Berita'],
                        ['route' => 'gallery', 'label' => 'Galeri'],
                        ['route' => 'donation', 'label' => 'Donasi'],
                        ['route' => 'contact', 'label' => 'Kontak'],
                    ];
                @endphp
                @foreach($menuItems as $item)
                    <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                       class="font-semibold text-body-sm transition-colors duration-150 py-2 border-b-2 {{ request()->routeIs($item['route']) ? 'border-brand-green-dark text-brand-green-dark' : 'border-transparent text-slate hover:text-ink' }}"
                       wire:navigate>
                        {{ $item['label'] }}
                    </a>
                @endforeach
                
                @if($settingService->isEnabled('show_finance_public'))
                    <a href="{{ route('finance') }}" 
                       class="font-semibold text-body-sm transition-colors duration-150 py-2 border-b-2 {{ request()->routeIs('finance') ? 'border-brand-green-dark text-brand-green-dark' : 'border-transparent text-slate hover:text-ink' }}"
                       wire:navigate>
                        Keuangan
                    </a>
                @endif
            </nav>

            <!-- Desktop CTA -->
            <div class="hidden lg:flex items-center gap-sm">
                <a href="{{ Route::has('donation') ? route('donation') : '#' }}" class="btn-primary" wire:navigate>
                    Donasi Sekarang
                </a>
            </div>

            <!-- Hamburger (Mobile) -->
            <button class="lg:hidden p-xs text-ink focus:outline-none" @click="isOpen = !isOpen" aria-label="Toggle Menu">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                </svg>
            </button>
        </div>

        <!-- Mobile Drawer Menu (Slide-in) -->
        <div class="fixed inset-0 z-50 lg:hidden" x-show="isOpen" style="display: none;">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-brand-teal-deep/50 backdrop-blur-xs transition-opacity" @click="isOpen = false"></div>

            <!-- Drawer Content -->
            <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-canvas shadow-level-4 flex flex-col p-lg border-l border-hairline"
                 x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                <div class="flex items-center justify-between pb-md border-b border-hairline">
                    <span class="font-bold text-ink text-body-md">{{ $masjidName }}</span>
                    <button class="p-xs text-ink" @click="isOpen = false">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex flex-col gap-sm mt-md overflow-y-auto flex-1">
                    @foreach($menuItems as $item)
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" 
                           class="font-semibold text-body-md py-sm px-md rounded-md transition-colors {{ request()->routeIs($item['route']) ? 'bg-surface-feature text-brand-green-dark' : 'text-slate hover:bg-surface' }}"
                           @click="isOpen = false"
                           wire:navigate>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    
                    @if($settingService->isEnabled('show_finance_public'))
                        <a href="{{ route('finance') }}" 
                           class="font-semibold text-body-md py-sm px-md rounded-md transition-colors {{ request()->routeIs('finance') ? 'bg-surface-feature text-brand-green-dark' : 'text-slate hover:bg-surface' }}"
                           @click="isOpen = false"
                           wire:navigate>
                            Keuangan
                        </a>
                    @endif
                </nav>

                <div class="pt-md border-t border-hairline mt-auto">
                    <a href="{{ Route::has('donation') ? route('donation') : '#' }}" class="btn-primary w-full text-center" @click="isOpen = false" wire:navigate>
                        Donasi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer Region (brand-teal-deep) -->
    <footer class="bg-brand-teal-deep text-on-dark border-t border-hairline-dark">
        <div class="max-w-7xl mx-auto px-md lg:px-xl py-xxxl lg:py-section grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-xxl">
            <!-- Kolom 1: Profil & Alamat -->
            <div class="flex flex-col gap-md">
                <div class="flex items-center gap-sm">
                    <div class="h-8 w-8 bg-brand-green/20 rounded-full flex items-center justify-center text-brand-green font-bold">
                        🕌
                    </div>
                    <span class="font-bold text-on-dark text-body-md tracking-tight">{{ $masjidName }}</span>
                </div>
                <p class="text-on-dark-muted text-body-sm leading-relaxed">
                    {{ $settingService->get('masjid_description', 'Wadah Ibadah, Pendidikan, Sosial, dan Dakwah Islamiah di Potronayan, Nogosari, Boyolali.') }}
                </p>
                <div class="text-on-dark-muted text-body-sm flex flex-col gap-xxs mt-sm">
                    <span class="font-bold text-on-dark text-micro-uppercase">Alamat:</span>
                    <span>{{ $masjidAddress }}</span>
                </div>
            </div>

            <!-- Kolom 2: Link Cepat -->
            <div class="flex flex-col gap-md">
                <span class="font-semibold text-on-dark text-body-sm-medium tracking-wide uppercase">Link Cepat</span>
                <nav class="flex flex-col gap-xxs">
                    @foreach($menuItems as $item)
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}" class="text-on-dark-muted hover:text-brand-green text-body-sm py-xxs transition-colors" wire:navigate>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Kolom 3: Hubungi Kami -->
            <div class="flex flex-col gap-md">
                <span class="font-semibold text-on-dark text-body-sm-medium tracking-wide uppercase">Kontak</span>
                <ul class="flex flex-col gap-sm text-on-dark-muted text-body-sm">
                    @if($masjidPhone)
                        <li class="flex items-start gap-xs">
                            <span class="text-brand-green">📞</span>
                            <div>
                                <span class="block text-micro font-bold text-on-dark">Telepon / WhatsApp</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $masjidPhone) }}" target="_blank" class="hover:text-brand-green transition-colors">{{ $masjidPhone }}</a>
                            </div>
                        </li>
                    @endif
                    @if($masjidEmail)
                        <li class="flex items-start gap-xs">
                            <span class="text-brand-green">✉️</span>
                            <div>
                                <span class="block text-micro font-bold text-on-dark">Email</span>
                                <a href="mailto:{{ $masjidEmail }}" class="hover:text-brand-green transition-colors">{{ $masjidEmail }}</a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Kolom 4: Sosial Media & Bank -->
            <div class="flex flex-col gap-md">
                <span class="font-semibold text-on-dark text-body-sm-medium tracking-wide uppercase">Donasi & Sosial Media</span>
                
                @if($settingService->get('rekening_number'))
                    <div class="bg-brand-teal p-md rounded-lg border border-hairline-dark">
                        <span class="block text-micro font-bold text-on-dark">Informasi Rekening</span>
                        <span class="block text-brand-green font-bold text-body-sm">{{ $settingService->get('bank_name') }}</span>
                        <span class="block text-on-dark font-mono text-body-sm select-all">{{ $settingService->get('rekening_number') }}</span>
                        <span class="block text-[11px] text-on-dark-muted">a.n. {{ $settingService->get('rekening_name') }}</span>
                    </div>
                @endif

                <div class="flex items-center gap-md mt-xs">
                    @if($facebookUrl)
                        <a href="{{ $facebookUrl }}" target="_blank" class="text-on-dark-muted hover:text-brand-green transition-colors text-body-md" aria-label="Facebook">
                            🌐 Facebook
                        </a>
                    @endif
                    @if($instagramUrl)
                        <a href="{{ $instagramUrl }}" target="_blank" class="text-on-dark-muted hover:text-brand-green transition-colors text-body-md" aria-label="Instagram">
                            📸 Instagram
                        </a>
                    @endif
                    @if($youtubeUrl)
                        <a href="{{ $youtubeUrl }}" target="_blank" class="text-on-dark-muted hover:text-brand-green transition-colors text-body-md" aria-label="YouTube">
                            🎥 YouTube
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Copyright Region -->
        <div class="bg-brand-teal-deep py-md border-t border-hairline-dark text-center text-xs text-on-dark-muted">
            <div class="max-w-7xl mx-auto px-md flex flex-col sm:flex-row justify-between items-center gap-xs">
                <span>© 2026 {{ $masjidName }}. Hak Cipta Dilindungi.</span>
                <span class="flex gap-xs text-[10px]">
                    <a href="/login" class="hover:text-brand-green transition-colors" wire:navigate>Admin Panel</a>
                </span>
            </div>
        </div>
    </footer>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
