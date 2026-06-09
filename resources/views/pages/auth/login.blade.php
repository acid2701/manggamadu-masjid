<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-lg bg-brand-teal-deep text-on-dark p-xl sm:p-xxl rounded-lg border border-hairline-dark shadow-level-3">
        
        <!-- Header -->
        <div class="flex flex-col gap-xxs text-center">
            <div class="flex items-center justify-center gap-xs mb-xs">
                <span class="text-brand-green text-heading-3">🕌</span>
                <span class="font-bold text-on-dark text-heading-4 tracking-tight">MasjidKu</span>
            </div>
            <h1 class="text-heading-5 font-bold text-on-dark">{{ __('Masuk ke Akun Anda') }}</h1>
            <p class="text-body-sm text-on-dark-muted">{{ __('Masukkan email dan password Anda di bawah ini') }}</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-md">
            @csrf

            <!-- Email Address -->
            <div class="flex flex-col gap-xxs">
                <label for="email" class="text-body-sm font-semibold text-on-dark-muted">
                    {{ __('Alamat Email') }}
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@email.com"
                    class="w-full bg-brand-teal text-on-dark text-body-md rounded-md px-md py-sm border border-hairline-dark focus:border-brand-green focus:outline-hidden focus:ring-1 focus:ring-brand-green h-[44px] placeholder:text-on-dark-muted/50"
                />
                @error('email')
                    <span class="text-xs text-red-400 mt-[2px] font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-xxs relative">
                <div class="flex items-center justify-between">
                    <label for="password" class="text-body-sm font-semibold text-on-dark-muted">
                        {{ __('Password') }}
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-micro font-bold text-brand-green hover:underline cursor-pointer" href="{{ route('password.request') }}" wire:navigate>
                            {{ __('Lupa password?') }}
                        </a>
                    @endif
                </div>
                
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Masukkan password"
                    class="w-full bg-brand-teal text-on-dark text-body-md rounded-md px-md py-sm border border-hairline-dark focus:border-brand-green focus:outline-hidden focus:ring-1 focus:ring-brand-green h-[44px] placeholder:text-on-dark-muted/50"
                />
                @error('password')
                    <span class="text-xs text-red-400 mt-[2px] font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <label class="inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded-sm bg-brand-teal border-hairline-dark text-brand-green-dark focus:ring-brand-green" {{ old('remember') ? 'checked' : '' }} />
                    <span class="ml-sm text-body-sm font-semibold text-on-dark-muted">{{ __('Ingat Saya') }}</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="mt-xs">
                <button type="submit" class="w-full btn-primary text-center font-bold text-body-sm" data-test="login-button">
                    {{ __('Masuk') }}
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center text-on-dark-muted border-t border-hairline-dark pt-md">
                <span>{{ __('Belum punya akun?') }}</span>
                <a href="{{ route('register') }}" class="text-brand-green hover:underline font-bold" wire:navigate>{{ __('Daftar') }}</a>
            </div>
        @endif
    </div>
</x-layouts::auth>
