<x-filament-widgets::widget>
    <x-filament::section class="!p-0 overflow-hidden shadow-sm">
        
        <!-- Plausible-style Horizontal Stats Header -->
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
                <div class="flex-1 min-w-[120px] p-4 {{ $index > 0 ? 'border-l border-gray-200 dark:border-gray-800' : '' }}">
                    <h3 class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 tracking-wider uppercase mb-1">{{ $item['label'] }}</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $item['data']['value'] }}</span>
                        
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

        <!-- Main Chart & Sidebar Area -->
        <div class="flex flex-col md:flex-row">
            
            <!-- Left Area: Line Chart (70%) -->
            <div class="p-4 pt-6 border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-800" style="flex: 0 0 70%; width: 70%; max-width: 70%;"
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
                                itemGap: 20,
                                textStyle: { fontSize: 11, color: '#6b7280' }
                            },
                            grid: { left: '2%', right: '3%', bottom: '10%', top: '5%', containLabel: true },
                            xAxis: {
                                type: 'category',
                                boundaryGap: false,
                                data: this.chartData.labels,
                                axisLine: { show: false },
                                axisTick: { show: false },
                                splitLine: { show: true, lineStyle: { type: 'solid', color: '#f3f4f6' } },
                                axisLabel: { color: '#9ca3af', fontSize: 10 }
                            },
                            yAxis: {
                                type: 'value',
                                axisLine: { show: false },
                                axisTick: { show: false },
                                splitLine: { show: true, lineStyle: { type: 'solid', color: '#f3f4f6' } },
                                axisLabel: { color: '#9ca3af', fontSize: 10 }
                            },
                            series: [
                                {
                                    name: 'Pengunjung',
                                    type: 'line',
                                    smooth: true,
                                    data: this.chartData.visits,
                                    symbol: 'none',
                                    itemStyle: { color: '#6366f1' },
                                    areaStyle: {
                                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            { offset: 0, color: 'rgba(99, 102, 241, 0.2)' },
                                            { offset: 1, color: 'rgba(99, 102, 241, 0)' }
                                        ])
                                    }
                                },
                                {
                                    name: 'Klik WhatsApp',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'none',
                                    data: this.chartData.waClicks,
                                    itemStyle: { color: '#10b981' },
                                    areaStyle: {
                                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            { offset: 0, color: 'rgba(16, 185, 129, 0.2)' },
                                            { offset: 1, color: 'rgba(16, 185, 129, 0)' }
                                        ])
                                    }
                                },
                                {
                                    name: 'Booking',
                                    type: 'line',
                                    smooth: true,
                                    symbol: 'none',
                                    data: this.chartData.bookings,
                                    itemStyle: { color: '#f59e0b' },
                                    areaStyle: {
                                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            { offset: 0, color: 'rgba(245, 158, 11, 0.2)' },
                                            { offset: 1, color: 'rgba(245, 158, 11, 0)' }
                                        ])
                                    }
                                }
                            ]
                        };
                        myChart.setOption(option);
                        window.addEventListener('resize', () => myChart.resize());
                    }
                }"
            >
                <div x-ref="lineChart" style="width: 100%; height: 320px;"></div>
            </div>

            <!-- Right Area: Sidebar Lists (30%) -->
            <div class="flex flex-col bg-white dark:bg-gray-900" style="flex: 0 0 30%; width: 30%; max-width: 30%;">
                
                <!-- Top Sources -->
                <div class="flex-1 flex flex-col border-b border-gray-100 dark:border-gray-800">
                    <div class="p-4 pb-2 flex justify-between items-center">
                        <h2 class="text-xs font-bold tracking-wider text-gray-500 uppercase">Top Sources</h2>
                        <span class="text-xs text-gray-400">Visitors</span>
                    </div>
                    
                    <div class="p-4 pt-0 space-y-2 flex-1 overflow-y-auto" style="max-height: 200px;">
                        @forelse($sources as $s)
                            @php
                                $sName = $s->source ?: 'Direct / None';
                                $iconUrl = null;
                                $n = strtolower($sName);
                                if (str_contains($n, 'google')) $iconUrl = 'https://www.google.com/s2/favicons?domain=google.com&sz=32';
                                elseif (str_contains($n, 'facebook') || str_contains($n, 'fb')) $iconUrl = 'https://www.google.com/s2/favicons?domain=facebook.com&sz=32';
                                elseif (str_contains($n, 'instagram') || str_contains($n, 'ig')) $iconUrl = 'https://www.google.com/s2/favicons?domain=instagram.com&sz=32';
                            @endphp
                            
                            <div class="relative flex items-center justify-between p-1.5 rounded text-sm group cursor-default">
                                <div class="absolute left-0 top-0 bottom-0 bg-indigo-50 dark:bg-indigo-900/30 rounded transition-all duration-500" style="width: {{ $s->percentage }}%; z-index: 0;"></div>
                                
                                <div class="relative z-10 flex items-center gap-2">
                                    @if($iconUrl)
                                        <img src="{{ $iconUrl }}" alt="{{ $sName }}" class="w-3.5 h-3.5 rounded-sm opacity-70 group-hover:opacity-100">
                                    @else
                                        <x-heroicon-o-link class="w-3.5 h-3.5 text-gray-400" />
                                    @endif
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ ucfirst($sName) }}</span>
                                </div>
                                <span class="relative z-10 font-semibold text-gray-900 dark:text-white">{{ number_format($s->total, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-center text-xs text-gray-400 py-4">No data</div>
                        @endforelse
                    </div>
                </div>

                <!-- Devices -->
                <div class="flex-1 flex flex-col">
                    <div class="p-4 pb-2 flex justify-between items-center">
                        <h2 class="text-xs font-bold tracking-wider text-gray-500 uppercase">Devices</h2>
                        <span class="text-xs text-gray-400">Visitors</span>
                    </div>
                    <div class="p-4 pt-0 space-y-2">
                        @foreach($devices as $d)
                            <div class="relative flex items-center justify-between p-1.5 rounded text-sm group cursor-default">
                                <div class="absolute left-0 top-0 bottom-0 bg-emerald-50 dark:bg-emerald-900/20 rounded transition-all duration-500" style="width: {{ $d->percentage }}%; z-index: 0;"></div>
                                <div class="relative z-10 flex items-center gap-2">
                                    @if($d->name === 'Desktop')
                                        <x-heroicon-o-computer-desktop class="w-3.5 h-3.5 text-gray-400" />
                                    @else
                                        <x-heroicon-o-device-phone-mobile class="w-3.5 h-3.5 text-gray-400" />
                                    @endif
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $d->name }}</span>
                                </div>
                                <span class="relative z-10 font-semibold text-gray-900 dark:text-white">{{ number_format($d->total, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
