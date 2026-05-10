<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Masa Depan Berkendara: Menjelajahi Teknologi E-GMP pada Hyundai Ioniq Series',
                'category' => 'News',
                'image' => 'posts/thumbnails/ioniq.png',
                'content' => '<p>Hyundai terus memimpin revolusi kendaraan listrik dengan platform E-GMP (Electric Global Modular Platform). Teknologi ini bukan sekadar baterai besar, melainkan arsitektur cerdas yang memungkinkan pengisian daya ultra-cepat dan ruang kabin yang jauh lebih luas.</p><p>Dengan sistem pengisian 800V, Ioniq 5 dapat terisi dari 10% ke 80% hanya dalam waktu 18 menit. Ini adalah standar baru dalam kenyamanan berkendara jarak jauh tanpa rasa khawatir akan waktu pengisian yang lama.</p>',
            ],
            [
                'title' => '5 Rahasia Agar Performa Mesin Mobil Tetap Prima di Segala Kondisi Jalan',
                'category' => 'Tips',
                'image' => 'posts/thumbnails/maintenance.png',
                'content' => '<p>Menjaga performa mesin tidak harus sulit. Kuncinya ada pada konsistensi perawatan rutin. Pertama, pastikan oli mesin diganti sesuai jadwal. Kedua, periksa tekanan ban secara berkala untuk efisiensi bahan bakar yang maksimal.</p><p>Selain itu, menggunakan bahan bakar dengan oktan yang sesuai rekomendasi pabrikan akan membantu menjaga kebersihan ruang bakar dan mencegah knocking pada mesin modern Hyundai Anda.</p>',
            ],
            [
                'title' => 'Eksklusif Bulan Ini: Penawaran Bunga 0% dan Hadiah Langsung untuk Pembelian STARGAZER',
                'category' => 'Promo',
                'image' => 'posts/thumbnails/stargazer.png',
                'content' => '<p>Wujudkan impian keluarga Anda memiliki Hyundai STARGAZER bulan ini. Dapatkan paket cicilan ringan dengan bunga 0% hingga 2 tahun. Tidak hanya itu, setiap pembelian unit STARGAZER juga berhak mendapatkan voucher belanja senilai jutaan rupiah.</p><p>Promo ini berlaku terbatas hingga akhir bulan. Hubungi konsultan sales kami sekarang untuk mendapatkan simulasi kredit yang sesuai dengan budget Anda.</p>',
            ],
            [
                'title' => 'Keseruan Weekend Drive Bersama Komunitas Hyundai Gowa: Kebersamaan dalam Kenyamanan',
                'category' => 'Event',
                'image' => 'posts/thumbnails/event.png',
                'content' => '<p>Pekan lalu, lebih dari 50 pemilik unit Hyundai berkumpul untuk acara Weekend Drive menuju pegunungan. Acara ini bertujuan untuk mempererat tali silaturahmi antar pemilik sekaligus mencoba ketangguhan fitur SmartSense di medan yang menantang.</p><p>Kenyamanan kabin dan fitur hiburan yang mumpuni membuat perjalanan jauh terasa singkat dan menyenangkan bagi seluruh keluarga peserta.</p>',
            ],
        ];

        foreach ($posts as $post) {
            Post::updateOrCreate(
                ['slug' => Str::slug($post['title'])],
                [
                    'title' => $post['title'],
                    'content' => $post['content'],
                    'category' => $post['category'],
                    'is_published' => true,
                    'published_at' => now(),
                    'image' => $post['image'],
                ]
            );
        }
    }
}
