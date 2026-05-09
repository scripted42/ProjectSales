<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($this->getClients() as $client)
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition-all group overflow-hidden relative">
                <!-- Background Decorative Gradient -->
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
                
                <div class="flex justify-between items-start mb-4 relative">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl">
                            <x-heroicon-o-globe-alt class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $client->domain }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $client->token }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end">
                        <span @class([
                            'px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase mb-1',
                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $client->status === 'active',
                            'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' => $client->status === 'suspended',
                        ])>
                            {{ $client->status }}
                        </span>
                        <span @class([
                            'px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase',
                            'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' => $client->plan === 'pro',
                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' => $client->plan === 'regular',
                        ])>
                            {{ $client->plan }}
                        </span>
                    </div>
                </div>

                <div class="space-y-3 mb-6 relative">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Expiration</span>
                        <span class="font-bold text-gray-700 dark:text-gray-200">{{ $client->expired_at ?? 'Lifetime' }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-indigo-600 h-full w-[100%] rounded-full shadow-[0_0_8px_rgba(79,70,229,0.5)]"></div>
                    </div>
                </div>

                <div class="flex gap-2 relative">
                    <a href="http://{{ $client->domain }}" target="_blank" class="flex-1 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 py-2 rounded-xl text-xs font-bold text-center transition-colors border border-gray-200 dark:border-gray-600">
                        Visit Site
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 bg-indigo-900/10 border border-indigo-500/20 rounded-3xl p-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="animate-pulse w-3 h-3 bg-green-500 rounded-full shadow-[0_0_12px_rgba(34,197,94,0.8)]"></div>
            <p class="text-indigo-900 dark:text-indigo-300 font-medium">Mothership Central is online. Monitoring all client nodes.</p>
        </div>
        <button wire:click="$refresh" class="text-xs font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:underline">
            Refresh All
        </button>
    </div>
</x-filament-panels::page>
