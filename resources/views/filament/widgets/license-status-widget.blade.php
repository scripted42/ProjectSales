@php
    $info = $this->getLicenseInfo();
@endphp

<x-filament-widgets::widget>
    <div class="license-status-card relative overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <!-- Background Decoration -->
        <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-{{ $info['status_color'] }}-500/5 blur-3xl"></div>
        
        <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-{{ $info['status_color'] }}-100 text-{{ $info['status_color'] }}-600 dark:bg-{{ $info['status_color'] }}-900/30 dark:text-{{ $info['status_color'] }}-400">
                    @if($info['is_expired'])
                        <x-heroicon-o-x-circle class="h-7 w-7" />
                    @elseif($info['is_near_expiry'])
                        <x-heroicon-o-exclamation-triangle class="h-7 w-7" />
                    @else
                        <x-heroicon-o-check-badge class="h-7 w-7" />
                    @endif
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">System License Status</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $info['is_pro'] ? 'AutoShow PRO' : 'AutoShow Regular' }}
                        </span>
                        <span class="inline-flex items-center rounded-md bg-{{ $info['status_color'] }}-50 px-2 py-1 text-xs font-bold text-{{ $info['status_color'] }}-700 ring-1 ring-inset ring-{{ $info['status_color'] }}-600/20 dark:bg-{{ $info['status_color'] }}-900/30 dark:text-{{ $info['status_color'] }}-400">
                            {{ $info['status_label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-end gap-1 text-right">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Valid Until</p>
                <p class="text-lg font-black text-gray-900 dark:text-white">{{ $info['expired_at'] }}</p>
                @if(!$info['is_expired'])
                    <p class="text-[10px] font-medium text-gray-500">
                        {{ $info['days_left'] }} days remaining
                    </p>
                @endif
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
