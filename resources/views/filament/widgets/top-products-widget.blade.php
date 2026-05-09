<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <x-heroicon-o-eye class="w-5 h-5 text-blue-500"/>
                    Produk Paling Sering Dilihat
                </h3>
                <div class="space-y-3">
                    @forelse($this->getViewData()['topViews'] as $index => $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 hover:border-blue-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($index === 0)
                                        <x-heroicon-s-trophy class="w-6 h-6 text-yellow-500 drop-shadow-sm"/>
                                    @elseif($index === 1)
                                        <x-heroicon-s-trophy class="w-5 h-5 text-gray-400"/>
                                    @elseif($index === 2)
                                        <x-heroicon-s-trophy class="w-5 h-5 text-orange-400"/>
                                    @else
                                        <span class="w-5 text-center text-xs font-bold text-gray-300">#{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <span class="font-bold text-gray-700">{{ $item->car->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-black tracking-wider">{{ $item->total }} VIEWS</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic">Belum ada data kunjungan produk.</p>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <x-heroicon-o-cursor-arrow-rays class="w-5 h-5 text-green-500"/>
                    Interaksi Tertinggi (WA/Booking)
                </h3>
                <div class="space-y-3">
                    @forelse($this->getViewData()['topInteractions'] as $index => $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 hover:border-green-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    @if($index === 0)
                                        <x-heroicon-s-fire class="w-6 h-6 text-red-500 animate-pulse"/>
                                    @elseif($index === 1)
                                        <x-heroicon-s-bolt class="w-5 h-5 text-yellow-500"/>
                                    @elseif($index === 2)
                                        <x-heroicon-s-star class="w-5 h-5 text-blue-400"/>
                                    @else
                                        <span class="w-5 text-center text-xs font-bold text-gray-300">#{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <span class="font-bold text-gray-700">{{ $item->car->name ?? 'Unknown' }}</span>
                            </div>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black tracking-wider">{{ $item->total }} LEADS</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic">Belum ada interaksi lead masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
