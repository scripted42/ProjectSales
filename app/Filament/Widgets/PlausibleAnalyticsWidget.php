<?php

namespace App\Filament\Widgets;

use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use App\Models\Setting;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PlausibleAnalyticsWidget extends Widget
{
    protected static string $view = 'filament.widgets.plausible-analytics';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Setting::isPro();
    }

    protected function getViewData(): array
    {
        $now = now();
        $thirtyDaysAgo = now()->subDays(30);
        $sixtyDaysAgo = now()->subDays(60);

        // --- CHART DATA (Last 30 Days) ---
        $days = collect(range(29, 0))->map(fn($i) => now()->subDays($i)->format('Y-m-d'));

        $visits = SiteLog::where('log_type', 'visit')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $waClicks = SiteLog::where('log_type', 'wa_click')
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $bookings = TestDriveBooking::where('created_at', '>=', $thirtyDaysAgo)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as aggregate'))
            ->groupBy('date')
            ->pluck('aggregate', 'date');

        $labels = $days->map(fn($date) => Carbon::parse($date)->format('d M'))->toArray();
        $visitData = $days->map(fn($date) => $visits[$date] ?? 0)->toArray();
        $waData = $days->map(fn($date) => $waClicks[$date] ?? 0)->toArray();
        $bookingData = $days->map(fn($date) => $bookings[$date] ?? 0)->toArray();

        // --- STATS (Last 30 Days) ---
        $currentVisits = SiteLog::where('log_type', 'visit')->where('created_at', '>=', $thirtyDaysAgo)->count();
        $currentWa = SiteLog::where('log_type', 'wa_click')->where('created_at', '>=', $thirtyDaysAgo)->count();
        $currentBookings = TestDriveBooking::where('created_at', '>=', $thirtyDaysAgo)->count();
        $currentConv = $currentVisits > 0 ? ($currentBookings / $currentVisits) * 100 : 0;
        $currentBounce = $currentVisits > 0 ? (($currentVisits - $currentWa) / $currentVisits) * 100 : 0;

        // --- STATS (Previous 30 Days) ---
        $prevVisits = SiteLog::where('log_type', 'visit')->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])->count();
        $prevWa = SiteLog::where('log_type', 'wa_click')->whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])->count();
        $prevBookings = TestDriveBooking::whereBetween('created_at', [$sixtyDaysAgo, $thirtyDaysAgo])->count();
        $prevConv = $prevVisits > 0 ? ($prevBookings / $prevVisits) * 100 : 0;
        $prevBounce = $prevVisits > 0 ? (($prevVisits - $prevWa) / $prevVisits) * 100 : 0;

        // --- TOP SOURCES ---
        $sourcesQuery = SiteLog::where('log_type', 'visit')
            ->select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
            
        $totalAllTimeVisits = $sourcesQuery->sum('total');
        $sources = $sourcesQuery->map(function($item) use ($totalAllTimeVisits) {
            $item->percentage = $totalAllTimeVisits > 0 ? ($item->total / $totalAllTimeVisits) * 100 : 0;
            return $item;
        });

        // --- DEVICES ---
        $logs = SiteLog::select('user_agent')->get();
        $mobileCount = 0;
        $desktopCount = 0;
        foreach ($logs as $log) {
            $ua = strtolower($log->user_agent ?? '');
            if (str_contains($ua, 'mobi') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $mobileCount++;
            } else {
                $desktopCount++;
            }
        }
        $totalDevices = $mobileCount + $desktopCount;
        $devices = collect([
            (object)['name' => 'Desktop', 'total' => $desktopCount, 'percentage' => $totalDevices > 0 ? ($desktopCount / $totalDevices) * 100 : 0],
            (object)['name' => 'Mobile', 'total' => $mobileCount, 'percentage' => $totalDevices > 0 ? ($mobileCount / $totalDevices) * 100 : 0]
        ])->sortByDesc('total');

        return [
            'chartData' => json_encode([
                'labels' => $labels,
                'visits' => $visitData,
                'waClicks' => $waData,
                'bookings' => $bookingData
            ]),
            'stats' => [
                'visits' => [
                    'value' => number_format($currentVisits, 0, ',', '.'),
                    'trend' => $this->calculateTrend($currentVisits, $prevVisits)
                ],
                'wa' => [
                    'value' => number_format($currentWa, 0, ',', '.'),
                    'trend' => $this->calculateTrend($currentWa, $prevWa)
                ],
                'bookings' => [
                    'value' => number_format($currentBookings, 0, ',', '.'),
                    'trend' => $this->calculateTrend($currentBookings, $prevBookings)
                ],
                'conversion' => [
                    'value' => round($currentConv, 1) . '%',
                    'trend' => $this->calculateTrend($currentConv, $prevConv)
                ],
                'bounce' => [
                    'value' => round($currentBounce, 1) . '%',
                    'trend' => $this->calculateTrend($currentBounce, $prevBounce, true)
                ]
            ],
            'sources' => $sources,
            'devices' => $devices
        ];
    }

    public function populateDummyData(): void
    {
        if (auth()->user()?->role !== 'developer') {
            return;
        }

        // Clean out any previous dummy logs first to prevent overflow
        SiteLog::where('ip_address', '127.0.0.1')->delete();
        TestDriveBooking::where('email', 'like', '%@example.com')->delete();

        $seeder = new \Database\Seeders\AnalyticsSeeder();
        // Since the seeder has its own truncate calls inside, let's call it.
        // Wait, to keep real data safe, let's make sure our seeder doesn't truncate the whole table.
        // We can run the seeder's generation loops directly here to be absolutely safe!
        $cars = \App\Models\Car::all();
        if ($cars->count() > 0) {
            $sources = ['facebook', 'instagram', 'google', 'direct'];
            $regions = ['Jakarta', 'Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'Banten', 'Bali', 'Sumatera Utara'];

            for ($i=0; $i<150; $i++) {
                SiteLog::create([
                    'log_type' => 'visit',
                    'source' => $sources[array_rand($sources)],
                    'region' => $regions[array_rand($regions)],
                    'car_id' => $cars->random()->id,
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
                ]);
            }

            for ($i=0; $i<50; $i++) {
                SiteLog::create([
                    'log_type' => 'wa_click',
                    'source' => $sources[array_rand($sources)],
                    'region' => $regions[array_rand($regions)],
                    'car_id' => $cars->random()->id,
                    'ip_address' => '127.0.0.1',
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
                ]);
            }

            for ($i=0; $i<20; $i++) {
                TestDriveBooking::create([
                    'name' => 'User ' . $i,
                    'phone' => '0812345678' . $i,
                    'email' => 'user'.$i.'@example.com',
                    'car_id' => $cars->random()->id,
                    'booking_date' => now()->addDays(rand(1, 10)),
                    'status' => (rand(0,1) == 1) ? 'pending' : 'confirmed',
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59))
                ]);
            }
        }

        \Filament\Notifications\Notification::make()
            ->title('Demo Dummy Data Populated!')
            ->success()
            ->send();
    }

    public function resetToRealData(): void
    {
        if (auth()->user()?->role !== 'developer') {
            return;
        }

        // ONLY delete dummy analytics logs (identified by 127.0.0.1 IP)
        SiteLog::where('ip_address', '127.0.0.1')->delete();

        // ONLY delete dummy bookings (identified by example.com email)
        TestDriveBooking::where('email', 'like', '%@example.com')->delete();

        \Filament\Notifications\Notification::make()
            ->title('Dummy Data Cleared (Real Data Preserved)!')
            ->warning()
            ->send();
    }

    private function calculateTrend($current, $prev, $inverse = false): array
    {
        if ($prev == 0) return ['dir' => 'up', 'pct' => '100%', 'color' => 'text-emerald-500'];
        
        $diff = (($current - $prev) / $prev) * 100;
        $dir = $diff >= 0 ? 'up' : 'down';
        
        $isGood = $inverse ? $diff <= 0 : $diff >= 0;
        $color = $isGood ? 'text-emerald-500' : 'text-rose-500';

        return [
            'dir' => $dir,
            'pct' => round(abs($diff), 1) . '%',
            'color' => $color
        ];
    }
}
