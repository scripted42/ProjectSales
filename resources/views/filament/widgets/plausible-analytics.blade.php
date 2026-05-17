<x-filament-widgets::widget>
    <style>
        .stats-item { border-right: 1px solid #f3f4f6; }
        .dark .stats-item { border-right: 1px solid #374151; }
        @media (max-width: 768px) {
            .stats-item { border-right: none !important; border-bottom: 1px solid #f3f4f6; width: 50% !important; flex: 0 0 50% !important; }
            .chart-container { flex-direction: column !important; }
            .chart-left, .chart-right { width: 100% !important; max-width: 100% !important; border-right: none !important; }
        }
        @media (min-width: 769px) {
            .chart-left { flex: 0 0 70% !important; width: 70% !important; border-right: 1px solid #f3f4f6 !important; }
            .chart-right { flex: 0 0 30% !important; width: 30% !important; }
        }
    </style>

    <x-filament::section class="!p-0 overflow-hidden shadow-sm">
        @if(auth()->user()?->role === 'developer')
            <div class="flex justify-between items-center p-3 border-b border-gray-100 dark:border-gray-800 bg-amber-50/40 dark:bg-amber-950/10">
                <div class="flex items-center gap-2">
                    <span class="flex h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-[11px] font-bold text-amber-800 dark:text-amber-400 tracking-wider uppercase">⚡ Developer Analytics Sandbox</span>
                </div>
                <div class="flex gap-2">
                    <x-filament::button wire:click="populateDummyData" size="xs" color="gray" icon="heroicon-o-arrow-path" class="text-xs">
                        Isi Data Dummy
                    </x-filament::button>
                    <x-filament::button wire:click="resetToRealData" size="xs" color="danger" icon="heroicon-o-trash" class="text-xs">
                        Reset Data Real (Kosong)
                    </x-filament::button>
                </div>
            </div>
        @endif
        
        <!-- Stats Header -->
        <div class="flex flex-wrap items-center border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
            @php
                $statItems = [
                    ['label' => 'Total Visits', 'data' => $stats['visits']],
                    ['label' => 'Klik WhatsApp', 'data' => $stats['wa']],
                    ['label' => 'Total Booking', 'data' => $stats['bookings']],
                    ['label' => 'Conversion Rate', 'data' => $stats['conversion']],
                    ['label' => 'Bounce Rate', 'data' => $stats['bounce']],
                ];
            @endphp
            
            @foreach($statItems as $index => $item)
                <div class="stats-item flex-1 p-4">
                    <h3 class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 tracking-wider uppercase mb-1">{{ $item['label'] }}</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">{{ $item['data']['value'] }}</span>
                        <div class="flex items-center text-xs font-bold {{ $item['data']['trend']['color'] }}">
                            @if($item['data']['trend']['dir'] === 'up')
                                <x-heroicon-o-arrow-trending-up class="w-3 h-3 mr-1" />
                            @else
                                <x-heroicon-o-arrow-trending-down class="w-3 h-3 mr-1" />
                            @endif
                            {{ $item['data']['trend']['pct'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Main Content -->
        <div class="chart-container flex w-full">
            
            <!-- Chart Area -->
            <div class="chart-left p-4 pt-6"
                x-data="{
                    chartData: {{ $chartData }},
                    init() {
                        let chartDom = this.$refs.lineChart;
                        let myChart = echarts.init(chartDom);
                        let option = {
                            tooltip: { trigger: 'axis' },
                            legend: {
                                data: ['Pengunjung', 'Klik WhatsApp', 'Booking'],
                                bottom: 0,
                                textStyle: { fontSize: 11, color: '#6b7280' }
                            },
                            grid: { left: '2%', right: '4%', bottom: '15%', top: '5%', containLabel: true },
                            xAxis: {
                                type: 'category',
                                boundaryGap: false,
                                data: this.chartData.labels,
                                axisLabel: { color: '#9ca3af', fontSize: 10 },
                                axisLine: { show: false },
                                splitLine: { show: true, lineStyle: { color: '#f3f4f6' } }
                            },
                            yAxis: {
                                type: 'value',
                                axisLabel: { color: '#9ca3af', fontSize: 10 },
                                splitLine: { show: true, lineStyle: { color: '#f3f4f6' } }
                            },
                            series: [
                                { name: 'Pengunjung', type: 'line', smooth: true, data: this.chartData.visits, symbol: 'none', itemStyle: { color: '#6366f1' }, areaStyle: { color: 'rgba(99, 102, 241, 0.1)' } },
                                { name: 'Klik WhatsApp', type: 'line', smooth: true, data: this.chartData.waClicks, symbol: 'none', itemStyle: { color: '#10b981' }, areaStyle: { color: 'rgba(16, 185, 129, 0.1)' } },
                                { name: 'Booking', type: 'line', smooth: true, data: this.chartData.bookings, symbol: 'none', itemStyle: { color: '#f59e0b' }, areaStyle: { color: 'rgba(245, 158, 11, 0.1)' } }
                            ]
                        };
                        myChart.setOption(option);
                        window.addEventListener('resize', () => myChart.resize());
                    }
                }"
            >
                <div x-ref="lineChart" style="width: 100%; height: 320px;"></div>
            </div>

            <!-- Sidebar Area -->
            <div class="chart-right flex flex-col bg-white dark:bg-gray-900">
                <div class="p-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xs font-bold tracking-wider text-gray-500 uppercase">Top Sources</h2>
                        <span class="text-xs text-gray-400">Visitors</span>
                    </div>
                    @foreach($sources as $s)
                        <div class="relative flex items-center justify-between p-1.5 mb-1 rounded text-sm">
                            <div class="absolute left-0 top-0 bottom-0 bg-indigo-50 dark:bg-indigo-900/30 rounded" style="width: {{ $s->percentage }}%; z-index: 0;"></div>
                            <span class="relative z-10 text-gray-700 dark:text-gray-300">{{ ucfirst($s->source ?: 'Direct') }}</span>
                            <span class="relative z-10 font-bold">{{ $s->total }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="p-4">
                    <h2 class="text-xs font-bold tracking-wider text-gray-500 uppercase mb-4">Devices</h2>
                    @foreach($devices as $d)
                        <div class="relative flex items-center justify-between p-1.5 mb-2 rounded text-sm">
                            <div class="absolute left-0 top-0 bottom-0 bg-emerald-50 dark:bg-emerald-900/20 rounded" style="width: {{ $d->percentage }}%; z-index: 0;"></div>
                            <span class="relative z-10 text-gray-700 dark:text-gray-300">{{ $d->name }}</span>
                            <span class="relative z-10 font-bold">{{ $d->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
