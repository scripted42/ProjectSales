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
                'name' => 'STARGAZER',
                'slug' => 'stargazer',
                'category' => 'MPV',
                'price' => 262300000,
                'description' => 'Bintang Baru Keluarga. MPV yang dirancang khusus untuk kenyamanan dan keamanan berkendara bersama keluarga tercinta.',
                'features' => ['Captain Seat', 'Tire Pressure Monitoring', 'Spacious Cabin', 'Hyundai SmartSense'],
                'image' => 'cars/stargazer.png',
                'hero_image' => 'cars/hero/stargazer_hero.jpg',
                'variants' => [
                    [
                        'name' => 'Active MT',
                        'price' => 262300000,
                        'transmission' => 'Manual 6-Speed',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Halogen Headlamps', '15-inch Steel Wheels', '2 Speakers Audio', 'Dual Airbags']
                    ],
                    [
                        'name' => 'Trend IVT',
                        'price' => 280200000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['8-inch Display Audio', '16-inch Alloy Wheels', 'Rear View Monitor', 'Hyundai Bluelink']
                    ],
                    [
                        'name' => 'Style IVT',
                        'price' => 305300000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Full LED Headlamps', '4.2-inch Supervision Cluster', '6 Speakers Audio', 'Cruise Control']
                    ],
                    [
                        'name' => 'Prime IVT',
                        'price' => 320900000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Hyundai SmartSense Safety', 'Ambient Mood Lighting', 'Leather Seats', 'Bose Premium Sound System']
                    ]
                ]
            ],
            [
                'name' => 'The New CRETA',
                'slug' => 'the-new-creta',
                'category' => 'SUV',
                'price' => 297300000,
                'description' => 'Spotlight in every move. SUV compact yang dinamis dan berjiwa muda, dilengkapi dengan konektivitas tercanggih.',
                'features' => ['Hyundai Bluelink', 'Bose Sound System', 'Ventilated Seats', 'Panoramic Sunroof'],
                'image' => 'cars/creta.png',
                'hero_image' => 'cars/hero/creta_hero.jpg',
                'variants' => [
                    [
                        'name' => 'Active MT',
                        'price' => 297300000,
                        'transmission' => 'Manual 6-Speed',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['8-inch Touchscreen', '16-inch Silver Alloy Wheels', 'Dual Airbags', 'Tilt Steering']
                    ],
                    [
                        'name' => 'Trend IVT',
                        'price' => 319300000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Hyundai Bluelink', 'Wireless Smartphone Charger', 'Rear View Monitor', 'Rear USB Charger']
                    ],
                    [
                        'name' => 'Style IVT',
                        'price' => 383800000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['LED Headlamps', 'Panoramic Sunroof', 'Bose Sound System (8 Speakers)', 'Ventilated Seats']
                    ],
                    [
                        'name' => 'Prime IVT',
                        'price' => 416800000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Hyundai SmartSense Safety', 'Red Accent Exterior Lines', 'Tire Pressure Monitoring System', '10.25-inch TFT Cluster']
                    ]
                ]
            ],
            [
                'name' => 'IONIQ 5',
                'slug' => 'ioniq-5',
                'category' => 'EV',
                'price' => 782000000,
                'description' => 'Power your world with the IONIQ 5. Era baru mobil listrik murni yang menggabungkan desain retro-futuristis dengan teknologi V2L revolusioner.',
                'features' => ['V2L Technology', 'Ultra-fast Charging', 'Sustainable Materials', 'Vision Roof'],
                'image' => 'cars/ioniq5.png',
                'hero_image' => 'cars/hero/ioniq5_hero.jpg',
                'variants' => [
                    [
                        'name' => 'Standard Range Prime',
                        'price' => 782000000,
                        'transmission' => 'Single Speed Reduction Gear',
                        'engine' => 'Electric Motor (58 kWh Battery)',
                        'key_features' => ['Range 384 km', 'V2L (Vehicle-to-Load) Outlets', 'Hyundai SmartSense', 'LED Headlamps']
                    ],
                    [
                        'name' => 'Standard Range Signature',
                        'price' => 823000000,
                        'transmission' => 'Single Speed Reduction Gear',
                        'engine' => 'Electric Motor (58 kWh Battery)',
                        'key_features' => ['Range 384 km', 'Bose Premium Audio', 'Premium Relaxation Seats', 'Parametric Pixel LED Light']
                    ],
                    [
                        'name' => 'Long Range Prime',
                        'price' => 809000000,
                        'transmission' => 'Single Speed Reduction Gear',
                        'engine' => 'Electric Motor (72.6 kWh Battery)',
                        'key_features' => ['Range 481 km', 'V2L (Vehicle-to-Load) Outlets', 'Hyundai SmartSense', '19-inch Alloy Wheels']
                    ],
                    [
                        'name' => 'Long Range Signature',
                        'price' => 895000000,
                        'transmission' => 'Single Speed Reduction Gear',
                        'engine' => 'Electric Motor (72.6 kWh Battery)',
                        'key_features' => ['Range 481 km', 'Vision Roof', 'Bose Premium Sound System', 'Premium Relaxation Seats']
                    ]
                ]
            ],
            [
                'name' => 'STARGAZER X',
                'slug' => 'stargazer-x',
                'category' => 'Crossover',
                'price' => 335800000,
                'description' => 'Unleash the X in you. Perpaduan sempurna antara kelegaan MPV keluarga dengan kegagahan karakter SUV.',
                'features' => ['Bold Crossover Design', 'High Ground Clearance 200mm', 'Premium Roof Rails', 'Bose Audio'],
                'image' => 'cars/stargazer_x.png',
                'hero_image' => 'cars/hero/stargazer_x_hero.jpg',
                'variants' => [
                    [
                        'name' => 'Style IVT',
                        'price' => 335800000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['17-inch Diamond Cut Alloy Wheels', 'Crossover Body Kit', 'Ground Clearance 200mm', '8-inch Audio']
                    ],
                    [
                        'name' => 'Prime IVT',
                        'price' => 346400000,
                        'transmission' => 'IVT (Intelligent Variable)',
                        'engine' => 'Smartstream G1.5 MPI',
                        'key_features' => ['Hyundai SmartSense Safety', 'Bose Premium Sound System', 'Leatherette Seats with Red Stitching', 'Captain Seat Optional']
                    ]
                ]
            ],
            [
                'name' => 'STARIA',
                'slug' => 'staria',
                'category' => 'Luxury MPV',
                'price' => 924000000,
                'description' => 'Lounge on wheels. Van premium futuristik dengan kabin super lapang dan kemewahan sekelas kabin pesawat kelas bisnis.',
                'features' => ['Futuristic Exterior', 'Lounge-style Cabin', 'Swiveling Independent Chairs', 'Dual Sunroof'],
                'image' => 'cars/staria.png',
                'hero_image' => 'cars/hero/staria_hero.jpg',
                'variants' => [
                    [
                        'name' => 'Signature 9-Seater',
                        'price' => 924000000,
                        'transmission' => '8-Speed Automatic',
                        'engine' => 'R 2.2 CRDi Diesel VGT',
                        'key_features' => ['9 Premium Leather Seats', 'Swiveling Independent Seats (2nd row)', 'Dual Sunroof', 'Smart Power Sliding Door']
                    ],
                    [
                        'name' => 'Signature 7-Seater',
                        'price' => 1060000000,
                        'transmission' => '8-Speed Automatic',
                        'engine' => 'R 2.2 CRDi Diesel VGT',
                        'key_features' => ['Premium Relaxation Seats (7 seats)', '12 Bose Premium Speakers', 'Surround View Monitor (360 Camera)', 'Full TFT Supervision Cluster']
                    ]
                ]
            ],
        ];

        foreach ($cars as $car) {
            Car::updateOrCreate(['slug' => $car['slug']], $car);
        }
    }
}
