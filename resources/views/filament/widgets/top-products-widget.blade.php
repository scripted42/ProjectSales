<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <x-heroicon-o-eye class="w-5 h-5 text-blue-500"/>
                    Produk Paling Sering Dilihat
                </h3>
                <div class="space-y-3">
                    @foreach($this->getViewData()['topViews'] as $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="font-medium text-gray-700">{{ $item->car->name ?? 'Unknown' }}</span>
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">{{ $item->total }} Views</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <x-heroicon-o-cursor-arrow-rays class="w-5 h-5 text-green-500"/>
                    Interaksi Tertinggi (WA/Booking)
                </h3>
                <div class="space-y-3">
                    @foreach($this->getViewData()['topInteractions'] as $item)
                        <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <span class="font-medium text-gray-700">{{ $item->car->name ?? 'Unknown' }}</span>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">{{ $item->total }} Leads</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
