<x-filament-panels::page>
    <div>
        @if(!$isVerified)
            <x-filament::section>
                <div class="max-w-md mx-auto text-center space-y-6 py-8">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto text-indigo-600">
                        <x-heroicon-s-shield-check class="w-8 h-8"/>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black">Developer OTP</h2>
                        <p class="text-gray-500">Masukkan kode OTP pengembang untuk mengakses pengaturan lisensi.</p>
                        <p class="text-xs text-indigo-500 mt-1 font-mono">(Simulasi: Masukkan 123456)</p>
                    </div>
                    
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="text"
                            wire:model="otpInput"
                            placeholder="000000"
                            class="text-center text-2xl tracking-[1em] font-black"
                        />
                    </x-filament::input.wrapper>

                    <x-filament::button 
                        size="xl" 
                        class="w-full"
                        wire:click="verifyOtp">
                        Verify OTP & Access
                    </x-filament::button>
                </div>
            </x-filament::section>
        @else
            <div class="space-y-6">
                <x-filament::section>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">Master License Control</h3>
                            <p class="text-sm text-gray-500">Kontrol status paket aplikasi ini secara remote melalui Mother Ship.</p>
                        </div>
                        <div class="flex gap-4">
                            <x-filament::button 
                                wire:click="logoutDeveloper" 
                                color="gray" 
                                variant="outline">
                                Lock Panel
                            </x-filament::button>
                            <x-filament::button 
                                wire:click="toggleLicense" 
                                color="warning" 
                                icon="heroicon-m-arrow-path">
                                Toggle Remote Plan
                            </x-filament::button>
                        </div>
                    </div>
                </x-filament::section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-filament::section>
                        <x-slot name="heading">Connection Settings</x-slot>
                        <div class="space-y-4">
                            <x-filament::input.wrapper label="Mothership API Token">
                                <x-filament::input
                                    type="password"
                                    wire:model="mothershipToken"
                                    placeholder="Paste your secret token here..."
                                />
                            </x-filament::input.wrapper>
                            
                            <div class="flex gap-4">
                                <x-filament::button wire:click="saveToken">
                                    Save Token
                                </x-filament::button>
                                
                                <x-filament::button wire:click="checkConnection" color="gray" icon="heroicon-m-signal">
                                    Check Connection
                                </x-filament::button>
                            </div>
                        </div>
                    </x-filament::section>

                    <div class="space-y-6">
                        <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl dark:bg-blue-900/20 dark:border-blue-800">
                            <h4 class="font-bold text-blue-800 dark:text-blue-300 mb-2">Remote Host (Mother Ship)</h4>
                            <code class="text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded">http://127.0.0.1:8001</code>
                        </div>
                        <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl dark:bg-indigo-900/20 dark:border-indigo-800">
                            <h4 class="font-bold text-indigo-800 dark:text-indigo-300 mb-2">Client Domain (Current)</h4>
                            <code class="text-xs bg-white dark:bg-gray-800 px-2 py-1 rounded">127.0.0.1:8000</code>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
