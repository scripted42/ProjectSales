<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mb-4">
            <h2 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Heatmap</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400">Peta Aktivitas Kunjungan Per Jam</p>
        </div>
        
        <div 
            x-data="{
                chartData: {{ $chartData }},
                maxValue: {{ $maxValue }},
                init() {
                    let chartDom = this.$refs.heatmapChart;
                    let myChart = echarts.init(chartDom);
                    
                    const hours = [
                        '12a', '1a', '2a', '3a', '4a', '5a', '6a',
                        '7a', '8a', '9a', '10a', '11a',
                        '12p', '1p', '2p', '3p', '4p', '5p',
                        '6p', '7p', '8p', '9p', '10p', '11p'
                    ];
                    // Gunakan nama singkat agar tidak terpotong di Mobile
                    const days = [
                        'Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'
                    ];

                    let option = {
                        tooltip: {
                            position: 'top',
                            formatter: function (params) {
                                return days[params.value[1]] + ' ' + hours[params.value[0]] + '<br/>Total: ' + params.value[2];
                            }
                        },
                        grid: {
                            height: '70%',
                            top: '5%',
                            left: '2%',
                            right: '2%',
                            containLabel: true // Memastikan label tidak terpotong
                        },
                        xAxis: {
                            type: 'category',
                            data: hours,
                            axisLabel: {
                                interval: 'auto', // Otomatis sembunyikan label jika sempit
                                fontSize: 9
                            },
                            splitArea: {
                                show: true
                            }
                        },
                        yAxis: {
                            type: 'category',
                            data: days,
                            axisLabel: {
                                fontSize: 10
                            },
                            splitArea: {
                                show: true
                            }
                        },
                        visualMap: {
                            min: 0,
                            max: this.maxValue,
                            calculable: true,
                            orient: 'horizontal',
                            left: 'center',
                            bottom: '0%',
                            itemWidth: 10,
                            itemHeight: 120,
                            inRange: {
                                color: ['#ebedf0', '#9be9a8', '#40c463', '#30a14e', '#216e39']
                            }
                        },
                        series: [{
                            name: 'Kunjungan',
                            type: 'heatmap',
                            data: this.chartData,
                            label: {
                                show: false
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }]
                    };

                    myChart.setOption(option);
                    window.addEventListener('resize', function() {
                        myChart.resize();
                    });
                }
            }"
        >
            <div x-ref="heatmapChart" class="w-full" style="height: 280px;"></div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
