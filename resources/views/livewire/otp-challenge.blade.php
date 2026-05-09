<div class="flex min-h-screen items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                Developer Security Check
            </h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Kami telah mengirimkan 6 digit kode OTP ke email Anda. Silakan masukkan kode tersebut di bawah ini.
            </p>
            @if(auth()->user()->role === 'developer')
                <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-widest mb-1">Testing Mode</p>
                    <p class="text-xl font-black text-amber-900 dark:text-amber-100 font-mono">{{ auth()->user()->otp_code }}</p>
                </div>
            @endif
        </div>

        <x-filament::section>
            <form wire:submit="verify" class="space-y-6">
                
                <div class="text-center">
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="text"
                            wire:model="otp"
                            id="otp"
                            placeholder="------"
                            maxlength="6"
                            required
                            autofocus
                            autocomplete="off"
                            style="text-align: center; font-size: 1.5rem; letter-spacing: 0.5em; font-family: monospace; padding: 0.75rem;"
                        />
                    </x-filament::input.wrapper>
                </div>

                <x-filament::button type="submit" size="lg" class="w-full" style="width: 100%; justify-content: center;">
                    Verifikasi OTP
                </x-filament::button>
                
            </form>
        </x-filament::section>

        <div class="mt-6 text-center">
            <x-filament::link wire:click="logout" color="gray" class="cursor-pointer">
                Batal & Kembali ke Login
            </x-filament::link>
        </div>

    </div>
</div>
