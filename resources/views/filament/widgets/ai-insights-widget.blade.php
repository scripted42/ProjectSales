<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 via-white to-blue-50 p-6 shadow-sm dark:border-indigo-900/50 dark:from-gray-900 dark:via-gray-900 dark:to-indigo-900/20">
        <!-- Decoration -->
        <div class="absolute -right-12 -top-12 h-40 w-40 rounded-full bg-indigo-500/10 blur-3xl"></div>
        <div class="absolute -bottom-12 -left-12 h-40 w-40 rounded-full bg-blue-500/10 blur-3xl"></div>

        <div class="relative">
            <div class="mb-6 flex items-center gap-3 border-b border-indigo-100 pb-4 dark:border-indigo-800/30">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-500/30">
                    <x-heroicon-s-sparkles class="h-6 w-6" />
                </div>
                <div>
                    <h2 class="text-lg font-black tracking-tight text-gray-900 dark:text-white">AutoShow AI Insights</h2>
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Analitik & Kesimpulan Otomatis</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @forelse($insights as $insight)
                    <div class="flex items-start gap-4 rounded-xl bg-white/60 p-4 backdrop-blur-md border border-white dark:bg-gray-800/50 dark:border-gray-700/50 transition-all hover:shadow-md hover:-translate-y-1">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-{{ $insight['color'] }}-100 text-{{ $insight['color'] }}-600 dark:bg-{{ $insight['color'] }}-900/30 dark:text-{{ $insight['color'] }}-400 mt-1">
                            @svg($insight['icon'], 'h-6 w-6')
                        </div>
                        <div>
                            <h3 class="mb-1 text-sm font-bold text-gray-900 dark:text-white">{{ $insight['title'] }}</h3>
                            <p class="text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                                {!! str_replace(['**', '**'], ['<strong class="text-gray-900 dark:text-white font-black">', '</strong>'], $insight['text']) !!}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 py-6 text-center text-sm text-gray-500">
                        Belum cukup data untuk menghasilkan kesimpulan AI.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
