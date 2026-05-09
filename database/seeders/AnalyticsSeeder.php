<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteLog;
use App\Models\TestDriveBooking;
use App\Models\Car;

class AnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        SiteLog::truncate();
        TestDriveBooking::truncate();

        $cars = Car::all();
        if($cars->count() == 0) return;

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
}
