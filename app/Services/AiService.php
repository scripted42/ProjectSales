<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * Generate dynamic AI insights based on analytics data.
     *
     * @param array $analyticsData
     * @return array
     */
    public function generateInsights(array $analyticsData): array
    {
        $provider = Setting::get('ai_provider') ?: env('AI_DEFAULT_PROVIDER', 'openrouter');
        
        if ($provider === 'disabled') {
            return [];
        }

        $model = Setting::get('ai_model') ?: env('AI_DEFAULT_MODEL', 'openrouter/free');
        
        // 1. Resolve API Key (Database or Env fallback)
        $encryptedKey = Setting::get('ai_api_key');
        $apiKey = null;

        if ($encryptedKey) {
            try {
                $apiKey = Crypt::decryptString($encryptedKey);
            } catch (\Exception $e) {
                Log::warning('AiService: Failed to decrypt API Key from database settings: ' . $e->getMessage());
            }
        }

        if (empty($apiKey)) {
            if ($provider === 'openrouter') {
                $apiKey = env('OPENROUTER_API_KEY');
            } elseif ($provider === 'deepseek') {
                $apiKey = env('DEEPSEEK_API_KEY');
            } elseif ($provider === 'gemini') {
                $apiKey = env('GEMINI_API_KEY');
            }
        }

        if (empty($apiKey)) {
            Log::info("AiService: API Key is not configured for provider [{$provider}]. Falling back to static insights.");
            return [];
        }

        // 2. Build Prompt
        $prompt = $this->buildPrompt($analyticsData);

        try {
            $textResponse = '';

            // 3. Make API Request
            if ($provider === 'openrouter') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => url('/'),
                    'X-Title' => 'AutoShow Pro',
                ])->timeout(15)->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model ?: 'openrouter/free',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('choices.0.message.content') ?? '';
                } else {
                    Log::error("AiService: OpenRouter request failed. Status: {$response->status()}, Body: {$response->body()}");
                }

            } elseif ($provider === 'deepseek') {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ])->timeout(15)->post('https://api.deepseek.com/chat/completions', [
                    'model' => $model ?: 'deepseek-chat',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('choices.0.message.content') ?? '';
                } else {
                    Log::error("AiService: DeepSeek request failed. Status: {$response->status()}, Body: {$response->body()}");
                }

            } elseif ($provider === 'gemini') {
                $modelName = $model ?: 'gemini-1.5-flash';
                $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7
                    ]
                ]);

                if ($response->successful()) {
                    $textResponse = $response->json('candidates.0.content.parts.0.text') ?? '';
                } else {
                    Log::error("AiService: Gemini request failed. Status: {$response->status()}, Body: {$response->body()}");
                }
            }

            if (empty($textResponse)) {
                return [];
            }

            // 4. Parse JSON Response
            return $this->parseJsonResponse($textResponse);

        } catch (\Exception $e) {
            Log::error('AiService: Error occurred while fetching AI insights: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Build the structured prompt for the AI model.
     *
     * @param array $data
     * @return string
     */
    private function buildPrompt(array $data): string
    {
        $visits = $data['total_visits'] ?? 0;
        $bookings = $data['total_bookings'] ?? 0;
        $convRate = $data['conversion_rate'] ?? 0;

        $peakHourVal = ($data['peak_hour'] === 'Tidak ada data')
            ? 'Belum ada data kunjungan'
            : "{$data['peak_hour']} WIB";

        $trendingCar = ($data['trending_car'] === 'Tidak ada data')
            ? 'Belum ada data kunjungan'
            : $data['trending_car'];

        $daysSinceGallery = $data['days_since_gallery'] ?? null;
        $galleryStatus = is_null($daysSinceGallery)
            ? 'Belum pernah mengunggah foto (Galeri kosong)'
            : "{$daysSinceGallery} hari yang lalu";

        return "Anda adalah AutoShow AI, asisten analis bisnis otomotif profesional yang bertugas menganalisis data performa dealer mobil dan memberikan rekomendasi taktis bagi sales/dealer owner.
Tugas Anda adalah menganalisis data berikut dan memberikan 3 hingga 4 saran pemasaran/penjualan taktis dalam Bahasa Indonesia yang alami, profesional, mudah dipahami, dan relevan dengan industri otomotif.

=== DATA ANALITIK DEALER ===
- Total Kunjungan Website (30 hari terakhir): {$visits}
- Total Pengajuan Test Drive / Booking (30 hari terakhir): {$bookings}
- Rasio Konversi Kunjungan ke Booking: {$convRate}%
- Jam Kunjungan Terramai: {$peakHourVal}
- Mobil Paling Populer (paling sering dilihat minggu ini): {$trendingCar}
- Terakhir Update Galeri Foto: {$galleryStatus}

=== ATURAN PENTING BAHASA & KONTEN ===
1. Gunakan Bahasa Indonesia formal dan profesional yang alami untuk sales mobil (contoh: gunakan istilah seperti 'calon pembeli', 'prospek', 'konversi', 'tipe mobil', 'leads').
2. JANGAN gunakan kata-kata rancu atau hasil terjemahan harfiah dari bahasa asing (seperti: 'mengurangkan pertahanan user', 'penerangan', 'perjalanan berkuat ciri', 'kesempurnaan transaksi').
3. Tulis rekomendasi yang konkret dan realistis. Contoh kalimat yang baik:
   - 'Optimalkan Jam Padat Kunjungan': 'Posting brosur promo atau update foto mobil di media sosial pada pukul {$peakHour} untuk menjangkau prospek saat mereka sedang aktif browsing.'
   - 'Fokus Promosi Unit Trending': 'Mobil {$trendingCar} sedang banyak diminati calon pembeli. Buat penawaran diskon khusus atau cicilan ringan untuk tipe ini di Instagram.'
   - 'Segarkan Konten Galeri': 'Update foto unit ready stock di galeri untuk meyakinkan calon pembeli bahwa unit yang mereka cari tersedia.'
4. Setiap saran harus terdiri dari 'title' (judul singkat, maks 4 kata) dan 'text' (detail saran konkrit, maks 2 kalimat).

=== FORMAT KELUARAN ===
Anda WAJIB mengembalikan respon dalam format JSON ARRAY murni (tanpa pembungkus markdown ```json, tanpa teks pembuka/penutup).
Struktur objek dalam array:
[
  {
    \"title\": \"Judul Taktis (Maks 4 Kata)\",
    \"text\": \"Penjelasan saran detail dan konkrit (Maks 2 kalimat).\",
    \"icon\": \"heroicon-o-nama-icon-heroicons-v3\", (PILIH ICON YANG SESUAI DARI: heroicon-o-light-bulb, heroicon-o-presentation-chart-line, heroicon-o-camera, heroicon-o-megaphone, heroicon-o-fire, heroicon-o-arrow-trending-up)
    \"color\": \"warna-badge\" (PILIH WARNA SESUAI DARI: success, warning, danger, info, primary, gray)
  }
]
Format keluaran Anda harus valid JSON yang dapat langsung diparsing oleh PHP json_decode.";
    }

    /**
     * Parse the raw AI text response into a structured array.
     *
     * @param string $text
     * @return array
     */
    private function parseJsonResponse(string $text): array
    {
        // Strip markdown codeblock wrappers if they are returned
        $cleaned = trim($text);
        if (preg_match('/\[\s*\{.*\}\s*\]/s', $cleaned, $matches)) {
            $cleaned = $matches[0];
        }

        $decoded = json_decode($cleaned, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            Log::warning('AiService: Failed to parse JSON response from AI. Raw response: ' . $text);
            return [];
        }

        // Validate the structure of each insight
        $validInsights = [];
        foreach ($decoded as $item) {
            if (isset($item['title'], $item['text'])) {
                $validInsights[] = [
                    'title' => (string) $item['title'],
                    'text' => (string) $item['text'],
                    'icon' => (string) ($item['icon'] ?? 'heroicon-o-light-bulb'),
                    'color' => (string) ($item['color'] ?? 'primary'),
                ];
            }
        }

        return $validInsights;
    }
}
