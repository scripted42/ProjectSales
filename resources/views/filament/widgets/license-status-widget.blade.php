@php
    $info = $this->getLicenseInfo();
@endphp

<x-filament-widgets::widget>
    <div class="license-status-card relative overflow-hidden rounded-lg border border-gray-100 bg-white p-2 md:p-3 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <!-- Flex Row dipaksa mendatar di semua device -->
        <div class="flex items-center justify-between gap-2 px-1">
            
            <!-- Kiri: Brand & Status -->
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 md:h-9 md:w-9 items-center justify-center rounded-md bg-{{ $info['status_color'] }}-100 text-{{ $info['status_color'] }}-600 dark:bg-{{ $info['status_color'] }}-900/30 dark:text-{{ $info['status_color'] }}-400">
                    @if($info['is_expired'])
                        <x-heroicon-o-x-circle class="h-4 w-4 md:h-5 md:w-5" />
                    @elseif($info['is_near_expiry'])
                        <x-heroicon-o-exclamation-triangle class="h-4 w-4 md:h-5 md:w-5" />
                    @else
                        <x-heroicon-o-check-badge class="h-4 w-4 md:h-5 md:w-5" />
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs md:text-sm font-black tracking-tight text-gray-900 dark:text-white whitespace-nowrap">
                            {{ $info['is_pro'] ? 'PRO' : 'Regular' }}
                        </span>
                        <span class="inline-flex items-center rounded bg-{{ $info['status_color'] }}-50 px-1.5 py-0.5 text-[8px] md:text-[10px] font-bold text-{{ $info['status_color'] }}-700 ring-1 ring-inset ring-{{ $info['status_color'] }}-600/20 dark:bg-{{ $info['status_color'] }}-900/30 dark:text-{{ $info['status_color'] }}-400 uppercase">
                            {{ $info['status_label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kanan: Masa Berlaku (Super Compact) -->
            <div class="flex items-center gap-2 text-right">
                <div class="h-6 w-px bg-gray-100 dark:bg-gray-800"></div>
                <div>
                    <div class="flex flex-col md:flex-row md:items-baseline md:gap-2">
                        <span class="text-[10px] md:text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">{{ $info['expired_at'] }}</span>
                        @if(!$info['is_expired'])
                            <span class="text-[9px] md:text-[10px] font-medium text-gray-400">
                                ({{ $info['days_left'] }} hr)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
