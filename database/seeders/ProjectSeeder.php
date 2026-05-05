<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Consultant;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // Consultant
        Consultant::create([
            'name' => 'LUQMAN NAWANG',
            'phone' => '081336046363',
            'email' => 'luqmannawangk@gmail.com',
            'address' => 'Jl. Mayjen HR. Muhammad No.35C, Kec. Sukomanunggal, Surabaya.',
            'bio' => 'Sales Konsultan Hyundai Surabaya yang siap membantu Anda mendapatkan mobil impian dengan penawaran terbaik.',
        ]);

        // Cars
        $cars = [
            [
                'name' => 'All New Kona',
                'slug' => 'all-new-kona',
                'category' => 'EV',
                'price' => 570250000,
                'description' => 'The first all-electric SUV from Hyundai.',
                'features' => ['Electric Engine', 'SmartSense', 'Panoramic Sunroof'],
            ],
            [
                'name' => 'IONIQ 5',
                'slug' => 'ioniq-5',
                'category' => 'EV',
                'price' => 813500000,
                'description' => 'Power your world with the IONIQ 5.',
                'features' => ['V2L Technology', 'Ultra-fast Charging', 'Sustainable Materials'],
            ],
            [
                'name' => 'The New CRETA',
                'slug' => 'the-new-creta',
                'category' => 'SUV',
                'price' => 312650000,
                'description' => 'Spotlight in every move.',
                'features' => ['Hyundai Bluelink', 'Bose Sound System', 'Ventilated Seats'],
            ],
            [
                'name' => 'STARGAZER',
                'slug' => 'stargazer',
                'category' => 'MPV',
                'price' => 265000000,
                'description' => 'Bintang Baru Keluarga.',
                'features' => ['Captain Seat', 'Tire Pressure Monitoring', 'Spacious Cabin'],
            ],
        ];

        foreach ($cars as $car) {
            Car::create($car);
        }
    }
}
