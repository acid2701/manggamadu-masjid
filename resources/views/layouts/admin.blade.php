<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'MasjidKu Admin' }} | {{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico" sizes="any">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-surface-soft text-ink antialiased font-sans flex" x-data="{ mobileSidebarOpen: false }">

    <!-- Sidebar overlay for mobile -->
    <div class="fixed inset-0 z-40 bg-brand-teal-deep/50 backdrop-blur-xs lg:hidden" 
         x-show="mobileSidebarOpen" 
         @click="mobileSidebarOpen = false"
         style="display: none;"></div>

    <!-- Sidebar (Left, fixed) -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-brand-teal-deep text-on-dark border-r border-hairline-dark flex flex-col transition-transform duration-300 transform lg:translate-x-0 lg:static lg:flex"
           :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Sidebar Header / Logo -->
        <div class="h-16 border-b border-hairline-dark flex items-center justify-between px-lg">
            <a href="{{ route('home') }}" class="flex items-center gap-sm" target="_blank">
                <span class="text-brand-green text-body-lg">🕌</span>
                <div class="flex flex-col">
                    <span class="font-bold text-on-dark leading-tight">MasjidKu</span>
                    <span class="text-brand-green font-bold text-[10px] tracking-widest uppercase">Admin Panel</span>
                </div>
            </a>
            <button class="lg:hidden text-on-dark-muted hover:text-on-dark focus:outline-none" @click="mobileSidebarOpen = false">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto px-sm py-md space-y-xxs">
            
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('dashboard') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
               wire:navigate>
                <span>📊</span>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()->can('manage donations') || auth()->user()->can('manage donation categories') || auth()->user()->can('manage finance'))
                <div class="pt-sm pb-xxs px-md text-[10px] font-bold text-on-dark-muted uppercase tracking-wider">
                    Keuangan
                </div>
            @endif

            @can('manage donations')
                <a href="{{ Route::has('admin.donations.index') ? route('admin.donations.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.donations.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>💰</span>
                    <span>Donasi</span>
                </a>
            @endcan

            @can('manage donation categories')
                <a href="{{ Route::has('admin.donation-categories.index') ? route('admin.donation-categories.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.donation-categories.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>🗂️</span>
                    <span>Kategori Donasi</span>
                </a>
            @endcan

            @can('manage finance')
                <a href="{{ Route::has('admin.finance.index') ? route('admin.finance.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.finance.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>💵</span>
                    <span>Keuangan</span>
                </a>
            @endcan

            @if(auth()->user()->can('manage posts') || auth()->user()->can('manage events') || auth()->user()->can('manage gallery'))
                <div class="pt-sm pb-xxs px-md text-[10px] font-bold text-on-dark-muted uppercase tracking-wider">
                    Konten
                </div>
            @endif

            @can('manage posts')
                <a href="{{ Route::has('admin.posts.index') ? route('admin.posts.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.posts.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>📰</span>
                    <span>Berita</span>
                </a>
            @endcan

            @can('manage events')
                <a href="{{ Route::has('admin.events.index') ? route('admin.events.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.events.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>📅</span>
                    <span>Kegiatan</span>
                </a>
            @endcan

            @can('manage gallery')
                <a href="{{ Route::has('admin.gallery.index') ? route('admin.gallery.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.gallery.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>🖼️</span>
                    <span>Galeri</span>
                </a>
            @endcan

            @if(auth()->user()->can('manage committee') || auth()->user()->can('manage users') || auth()->user()->can('manage settings'))
                <div class="pt-sm pb-xxs px-md text-[10px] font-bold text-on-dark-muted uppercase tracking-wider">
                    Sistem
                </div>
            @endif

            @can('manage committee')
                <a href="{{ Route::has('admin.committee.index') ? route('admin.committee.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.committee.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>👥</span>
                    <span>Pengurus</span>
                </a>
            @endcan

            @can('manage users')
                <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.users.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>👤</span>
                    <span>Pengguna</span>
                </a>
            @endcan

            @can('manage settings')
                <a href="{{ Route::has('admin.settings.index') ? route('admin.settings.index') : '#' }}" 
                   class="flex items-center gap-sm px-md py-sm rounded-md font-semibold text-body-sm transition-colors border-l-4 {{ request()->routeIs('admin.settings.*') ? 'border-brand-green bg-brand-teal text-on-dark' : 'border-transparent text-on-dark-muted hover:bg-brand-teal/50 hover:text-on-dark' }}"
                   wire:navigate>
                    <span>⚙️</span>
                    <span>Pengaturan</span>
                </a>
            @endcan

        </nav>

        <!-- Sidebar Footer (Logged-in User Profile) -->
        <div class="p-md border-t border-hairline-dark bg-brand-teal-deep/50 flex items-center justify-between">
            <div class="flex items-center gap-xs overflow-hidden">
                <div class="h-9 w-9 bg-brand-green-mid rounded-full flex items-center justify-center text-on-dark font-bold text-body-sm shrink-0">
                    {{ auth()->user()->initials() }}
                </div>
                <div class="flex flex-col truncate">
                    <span class="font-bold text-on-dark text-body-sm truncate">{{ auth()->user()->name }}</span>
                    <span class="text-on-dark-muted text-micro truncate">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">
        
        <!-- Header Bar -->
        <header class="h-16 bg-canvas border-b border-hairline flex items-center justify-between px-md lg:px-xl shrink-0">
            <!-- Left Header -->
            <div class="flex items-center gap-sm">
                <button class="lg:hidden p-xs text-ink hover:bg-surface rounded-md focus:outline-none" @click="mobileSidebarOpen = true">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="hidden sm:block text-slate text-body-sm font-semibold">
                    @yield('breadcrumb')
                </div>
            </div>

            <!-- Right Header / Dropdown -->
            <div class="flex items-center gap-sm">
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                        class="cursor-pointer"
                    />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <flux:avatar
                                        :name="auth()->user()->name"
                                        :initials="auth()->user()->initials()"
                                    />

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                        <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Pengaturan Profil') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full cursor-pointer text-red-600 dark:text-red-400"
                            >
                                {{ __('Keluar') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </header>

        <!-- Main Body Content -->
        <main class="flex-1 p-md lg:p-xl overflow-y-auto">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
