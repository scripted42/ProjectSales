<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'title' => 'Direktur PT Maju Jaya',
                'quote' => 'Pelayanan di dealer Hyundai ini sangat luar biasa! Proses pembelian mobil baru untuk operasional kantor kami berjalan sangat cepat dan transparan. Konsultan sangat informatif.',
                'is_active' => true,
            ],
            [
                'name' => 'Siti Aminah',
                'title' => 'Pengusaha Kuliner',
                'quote' => 'Saya sangat puas dengan Stargazer baru saya. Fiturnya sangat canggih dan nyaman untuk keluarga. Terima kasih banyak atas bantuan dan kesabaran tim sales yang membantu dari awal hingga mobil dikirim ke rumah.',
                'is_active' => true,
            ],
            [
                'name' => 'Reza Rahardian',
                'title' => 'Arsitek',
                'quote' => 'Membeli Hyundai Creta di sini adalah keputusan terbaik. Desainnya sangat stylish, cocok dengan selera saya. Proses kreditnya juga dibantu sampai tuntas dengan bunga yang bersaing. Sangat direkomendasikan!',
                'is_active' => true,
            ],
            [
                'name' => 'Linda Wijaya',
                'title' => 'Dokter Umum',
                'quote' => 'Pelayanan after-sales yang sangat menjanjikan. Saya dijelaskan dengan sangat detail mengenai fitur-fitur keselamatan Hyundai IONIQ 5. Serah terima unit juga sangat meriah dan berkesan.',
                'is_active' => true,
            ],
            [
                'name' => 'Ahmad Faisal',
                'title' => 'Dosen Universitas',
                'quote' => 'Dealer yang sangat profesional. Respon cepat, test drive bisa diatur di rumah, dan tidak ada biaya tersembunyi. Pengalaman membeli mobil jadi sangat menyenangkan dan tidak pusing.',
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $data) {
            \App\Models\Testimonial::create($data);
        }
    }
}
